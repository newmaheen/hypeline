<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\OfflineSale;
use App\Models\OfflineSaleItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OfflineSaleController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | List page with search, filters, pagination + summary cards
    |----------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = OfflineSale::query()->with('creator');

        // Search: Sale ID / customer name / phone
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('sale_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($date = $request->input('date')) {
            $query->whereDate('sale_date', $date);
        }

        if ($method = $request->input('payment_method')) {
            $query->where('payment_method', $method);
        }

        if ($status = $request->input('payment_status')) {
            $query->where('payment_status', $status);
        }

        $sales = $query->latest('sale_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        // Summary for the cards on top of the list page
        $summary = [
            'today_total' => OfflineSale::where('status', 'completed')
                ->whereDate('sale_date', today())->sum('total'),
            'today_count' => OfflineSale::whereDate('sale_date', today())->count(),
            'month_total' => OfflineSale::where('status', 'completed')
                ->whereMonth('sale_date', now()->month)
                ->whereYear('sale_date', now()->year)->sum('total'),
            'cancelled_count' => OfflineSale::where('status', 'cancelled')->count(),
        ];

        return view('admin.offline-sales.index', compact('sales', 'summary'));
    }

    /*
    |----------------------------------------------------------------------
    | Create sale page — sends products + variants to the form
    |----------------------------------------------------------------------
    */
    public function create()
    {
        $products = Product::where('is_active', true)
            ->with(['variants' => function ($q) {
                $q->where('is_active', true)->orderBy('size');
            }])
            ->orderBy('name')
            ->get();

        return view('admin.offline-sales.create', compact('products'));
    }

    /*
    |----------------------------------------------------------------------
    | Store — fully transactional, server-side pricing, row locking
    |----------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'        => 'nullable|string|max:255',
            'customer_phone'       => 'nullable|string|max:30',
            'sale_date'            => 'required|date',
            'discount'             => 'nullable|numeric|min:0',
            'payment_method'       => ['required', Rule::in(['cash', 'bkash', 'nagad', 'bank', 'other'])],
            'payment_status'       => ['required', Rule::in(['paid', 'unpaid', 'partial'])],
            'payment_reference'    => 'nullable|string|max:255',
            'notes'                => 'nullable|string|max:2000',

            'items'                        => 'required|array|min:1',
            'items.*.product_id'           => 'required|exists:products,id',
            'items.*.variant_id'           => 'required|exists:product_variants,id',
            'items.*.quantity'             => 'required|integer|min:1',
            'items.*.unit_price'           => 'required|numeric|min:0',
        ]);

        // Retry loop protects against a rare unique-sale_id collision
        $attempts = 0;

        beginTransaction:
        $attempts++;

        try {
            $sale = DB::transaction(function () use ($validated) {

                // ---- 1. Generate unique human-readable Sale ID ----
                do {
                    $next = OfflineSale::lockForUpdate()->count() + 1;
                    $saleId = 'OFF-' . str_pad($next, 6, '0', STR_PAD_LEFT);
                } while (OfflineSale::where('sale_id', $saleId)->exists());

                // ---- 2. Server-side totals — NEVER trust client math ----
                $subtotal = 0;

                foreach ($validated['items'] as $item) {
                    $subtotal += $item['unit_price'] * $item['quantity'];
                }

                $discount = $validated['discount'] ?? 0;

                if ($discount > $subtotal) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'discount' => 'Discount cannot be greater than the subtotal (৳' . number_format($subtotal, 2) . ').',
                    ]);
                }

                // ---- 3. Create the sale header ----
                $sale = OfflineSale::create([
                    'sale_id'           => $saleId,
                    'customer_name'     => $validated['customer_name'] ?? null,
                    'customer_phone'    => $validated['customer_phone'] ?? null,
                    'sale_date'         => $validated['sale_date'],
                    'subtotal'          => $subtotal,
                    'discount'          => $discount,
                    'total'             => $subtotal - $discount,
                    'payment_method'    => $validated['payment_method'],
                    'payment_status'    => $validated['payment_status'],
                    'payment_reference' => $validated['payment_reference'] ?? null,
                    'notes'             => $validated['notes'] ?? null,
                    'status'            => 'completed',
                    'created_by'        => Auth::guard('admin')->id(),
                ]);

                // ---- 4. Process each item under row lock ----
                foreach ($validated['items'] as $item) {

                    $variant = ProductVariant::where('id', $item['variant_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    // Variant must belong to the selected product (§22)
                    if ($variant->product_id != $item['product_id']) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'items' => 'One of the selected variants does not belong to its product.',
                        ]);
                    }

                    // Stock check — never allow negative stock (§5, §16)
                    if ($variant->stock < $item['quantity']) {
                        $variantLabel = trim(($variant->size ?? '') . ' / ' . ($variant->color ?? ''));

                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'items' => "Insufficient stock for {$variant->product->name} — {$variantLabel}. Available: {$variant->stock}.",
                        ]);
                    }

                    // Snapshot for historical accuracy (§2)
                    $variantLabel = trim(
                        ($variant->size ?? '') . ' / ' . ($variant->color ?? ''),
                        ' /'
                    );

                    $sale->items()->create([
                        'product_id'         => $variant->product_id,
                        'product_variant_id' => $variant->id,
                        'product_name'       => $variant->product->name,
                        'variant_name'       => $variantLabel ?: null,
                        'quantity'           => $item['quantity'],
                        'unit_price'         => $item['unit_price'],
                        'subtotal'           => $item['unit_price'] * $item['quantity'],
                    ]);

                    // ---- 5. Deduct stock ----
                    $variant->decrement('stock', $item['quantity']);

                    // ---- 6. Audit trail ----
                    StockMovement::create([
                        'product_variant_id' => $variant->id,
                        'quantity'           => -$item['quantity'],
                        'type'               => 'offline_sale',
                        'reference'          => $sale->sale_id,
                        'reference_id'       => $sale->id,
                        'created_by'         => Auth::guard('admin')->id(),
                        'notes'              => "Offline sale {$sale->sale_id}",
                    ]);
                }

                return $sale;
            }, 3);
        } catch (\Illuminate\Database\QueryException $e) {
            // Unique sale_id collision — regenerate and retry
            if ($attempts < 3 && str_contains($e->getMessage(), 'offline_sales.sale_id')) {
                goto beginTransaction;
            }
            throw $e;
        }

        return redirect()
            ->route('admin.offline-sales.show', $sale)
            ->with('success', "Offline Sale Completed Successfully — Sale ID: {$sale->sale_id}");
    }

    /*
    |----------------------------------------------------------------------
    | Sale details page
    |----------------------------------------------------------------------
    */
    public function show(OfflineSale $offlineSale)
    {
        $offlineSale->load(['items', 'creator', 'canceller']);

        return view('admin.offline-sales.show', compact('offlineSale'));
    }

    /*
    |----------------------------------------------------------------------
    | Cancel — restores stock EXACTLY once, fully transactional
    |----------------------------------------------------------------------
    */
    public function cancel(Request $request, OfflineSale $offlineSale)
    {
        if ($offlineSale->isCancelled()) {
            return back()->withErrors([
                'cancel' => 'This sale is already cancelled.',
            ]);
        }

        $validated = $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($offlineSale, $validated) {

            // Re-lock the sale row so two admins can't cancel simultaneously
            $locked = OfflineSale::where('id', $offlineSale->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_if($locked->isCancelled(), 409, 'This sale is already cancelled.');

            foreach ($locked->items as $item) {

                if ($item->product_variant_id) {
                    $variant = ProductVariant::where('id', $item->product_variant_id)
                        ->lockForUpdate()
                        ->first();

                    if ($variant) {
                        $variant->increment('stock', $item->quantity);

                        StockMovement::create([
                            'product_variant_id' => $variant->id,
                            'quantity'           => $item->quantity,
                            'type'               => 'offline_cancel',
                            'reference'          => $locked->sale_id,
                            'reference_id'       => $locked->id,
                            'created_by'         => Auth::guard('admin')->id(),
                            'notes'              => 'Stock restored — offline sale cancelled',
                        ]);
                    }
                }
            }

            $locked->update([
                'status'              => 'cancelled',
                'cancelled_by'        => Auth::guard('admin')->id(),
                'cancelled_at'        => now(),
                'cancellation_reason' => $validated['cancellation_reason'] ?? null,
            ]);
        });

        return back()->with('success', "Sale {$offlineSale->sale_id} cancelled and stock restored.");
    }

    /*
    |----------------------------------------------------------------------
    | Printer-friendly receipt
    |----------------------------------------------------------------------
    */
    public function receipt(OfflineSale $offlineSale)
    {
        $offlineSale->load('items');

        return view('admin.offline-sales.receipt', compact('offlineSale'));
    }

    /*
    |----------------------------------------------------------------------
    | Offline sales report (§20)
    |----------------------------------------------------------------------
    */
    public function report(Request $request)
    {
        $period = $request->input('period', 'today');

        $query = OfflineSale::where('status', 'completed');

        $from = $request->input('from');
        $to   = $request->input('to');

        switch ($period) {
            case 'yesterday':
                $query->whereDate('sale_date', today()->subDay());
                break;
            case 'week':
                $query->whereDate('sale_date', '>=', now()->startOfWeek()->toDateString());
                break;
            case 'month':
                $query->whereMonth('sale_date', now()->month)
                      ->whereYear('sale_date', now()->year);
                break;
            case 'custom':
                if ($from) $query->whereDate('sale_date', '>=', $from);
                if ($to)   $query->whereDate('sale_date', '<=', $to);
                break;
            case 'lifetime':
                break;
            default: // today
                $query->whereDate('sale_date', today());
        }

        $stats = [
            'transactions' => (clone $query)->count(),
            'quantity'     => OfflineSaleItem::whereIn('offline_sale_id', (clone $query)->pluck('id'))->sum('quantity'),
            'gross'        => (clone $query)->sum('subtotal'),
            'discount'     => (clone $query)->sum('discount'),
            'net'          => (clone $query)->sum('total'),
        ];

        return view('admin.offline-sales.report', compact('stats', 'period', 'from', 'to'));
    }
}