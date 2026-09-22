<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfflineSale;
use App\Models\OfflineSaleItem;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OfflineSaleController extends Controller
{
    public function index(Request $request)
    {
        $query = OfflineSale::with(['items.variant.product'])->latest();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('sale_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Payment Method Filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date Filter
        if ($request->filled('date')) {
            $query->whereDate('sale_date', $request->date);
        }

        $sales = $query->paginate(15)->withQueryString();

        // Summary Calculations
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $summary = [
            'today_total'     => OfflineSale::whereDate('sale_date', $today)->where('status', '!=', 'cancelled')->sum('total'),
            'today_count'     => OfflineSale::whereDate('sale_date', $today)->where('status', '!=', 'cancelled')->count(),
            'month_total'     => OfflineSale::where('sale_date', '>=', $thisMonth)->where('status', '!=', 'cancelled')->sum('total'),
            'month_count'     => OfflineSale::where('sale_date', '>=', $thisMonth)->where('status', '!=', 'cancelled')->count(),
            'lifetime_total'  => OfflineSale::where('status', '!=', 'cancelled')->sum('total'),
            'lifetime_count'  => OfflineSale::where('status', '!=', 'cancelled')->count(),
            'cancelled_count' => OfflineSale::where('status', 'cancelled')->count(),
        ];

        return view('admin.offline-sales.index', compact('sales', 'summary'));
    }

    public function create()
    {
        $variants = ProductVariant::with('product')
            ->where('stock', '>', 0)
            ->get();

        return view('admin.offline-sales.create', compact('variants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'      => 'nullable|string|max:255',
            'customer_phone'     => [
                'nullable',
                'string',
                'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/',
            ],
            'payment_method'     => 'required|string|in:cash,bkash,nagad,card,bank,other',
            'discount'           => 'nullable|numeric|min:0',
            'notes'              => 'nullable|string|max:500',
            'items'              => 'required|array|min:1',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'required|numeric|min:0',
        ], [
            'customer_phone.regex' => 'সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (যেমন: 017XXXXXXXX বা +88017XXXXXXXX)।',
        ]);

        // ফোন নম্বর ক্লিন করা (+88 মুছে স্ট্যান্ডার্ড ১১ ডিজিট রাখা)
        $customerPhone = $request->customer_phone;
        if (!empty($customerPhone)) {
            $customerPhone = preg_replace('/[^0-9]/', '', $customerPhone);
            if (strlen($customerPhone) > 11) {
                $customerPhone = substr($customerPhone, -11);
            }
        }

        try {
            $offlineSale = DB::transaction(function () use ($request, $customerPhone) {
                $subtotal = 0;

                // ১. সাবটোটাল হিসাব ও স্টক ভ্যালিডেশন
                foreach ($request->items as $item) {
                    $variant = ProductVariant::where('id', $item['variant_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($variant->stock < $item['quantity']) {
                        $pName = $variant->product->name ?? 'Item';
                        throw new \Exception("Product '{$pName}' does not have enough stock.");
                    }

                    $subtotal += ((float) $item['price']) * ((int) $item['quantity']);
                }

                $discount = (float) ($request->discount ?? 0);
                $total = max(0, $subtotal - $discount);

                $nextId = (OfflineSale::max('id') ?? 0) + 1;
                $saleId = 'OFF-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

                // ২. মূল সেল রেকর্ড তৈরি
                $offlineSale = OfflineSale::create([
                    'sale_id'        => $saleId,
                    'customer_name'  => $request->customer_name ?? 'Walk-in Customer',
                    'customer_phone' => $customerPhone, // ফরম্যাট করা নম্বর সেভ হবে
                    'sale_date'      => Carbon::today(),
                    'subtotal'       => $subtotal,
                    'discount'       => $discount,
                    'total'          => $total,
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'paid',
                    'notes'          => $request->notes,
                    'status'         => 'completed',
                    'created_by'     => Auth::guard('admin')->id(),
                ]);

                // ৩. আইটেম সংরক্ষণ
                foreach ($request->items as $item) {
                    $variant = ProductVariant::with('product')->findOrFail($item['variant_id']);
                    $itemPrice = (float) $item['price'];
                    $itemQty = (int) $item['quantity'];
                    $itemSubtotal = $itemPrice * $itemQty;

                    OfflineSaleItem::create([
                        'offline_sale_id'    => $offlineSale->id,
                        'product_id'         => $variant->product_id,
                        'product_variant_id' => $variant->id,
                        'product_name'       => $variant->product->name ?? 'Product',
                        'variant_name'       => trim(($variant->size ?? '') . ' ' . ($variant->color ?? '')),
                        'quantity'           => $itemQty,
                        'unit_price'         => $itemPrice,
                        'subtotal'           => $itemSubtotal,
                    ]);

                    $variant->decrement('stock', $itemQty);

                    StockMovement::create([
                        'product_variant_id' => $variant->id,
                        'type'               => 'out',
                        'quantity'           => $itemQty,
                        'remarks'            => 'Offline POS Sale ' . $offlineSale->sale_id,
                    ]);
                }

                return $offlineSale;
            });

            return redirect()->route('admin.offline-sales.show', $offlineSale->id)
                ->with('success', 'Sale completed successfully!');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(OfflineSale $offlineSale)
    {
        $offlineSale->load(['items.variant.product']);

        return view('admin.offline-sales.show', compact('offlineSale'));
    }

    public function receipt(OfflineSale $offlineSale)
    {
        $offlineSale->load(['items.variant.product']);

        return view('admin.offline-sales.receipt', compact('offlineSale'));
    }

    public function cancel(OfflineSale $offlineSale)
    {
        if ($offlineSale->status === 'cancelled') {
            return back()->with('error', 'This sale is already cancelled.');
        }

        DB::transaction(function () use ($offlineSale) {
            foreach ($offlineSale->items as $item) {
                if ($item->variant) {
                    $item->variant->increment('stock', $item->quantity);

                    StockMovement::create([
                        'product_variant_id' => $item->product_variant_id,
                        'type'               => 'in',
                        'quantity'           => $item->quantity,
                        'remarks'            => 'Restocked: Offline Sale ' . $offlineSale->sale_id . ' cancelled',
                    ]);
                }
            }

            $offlineSale->update([
                'status'        => 'cancelled',
                'cancelled_by'  => Auth::guard('admin')->id(),
                'cancelled_at'  => Carbon::now(),
            ]);
        });

        return back()->with('success', 'Sale cancelled and all products have been restocked to inventory.');
    }

    public function report()
    {
        return redirect()->route('admin.offline-sales.index');
    }
}