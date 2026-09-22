<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Offline Sale - Hypeline Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold">HYPELINE ADMIN</a>
            <a href="{{ route('admin.offline-sales.index') }}" class="btn btn-outline-light btn-sm">← Offline Sales</a>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">

                <div class="mb-4">
                    <h2 class="fw-bold mb-1">New POS / Offline Sale</h2>
                    <p class="text-muted mb-0">Record a direct sale and print receipt.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.offline-sales.store') }}" id="posForm">
                    @csrf

                    <div class="row g-4">
                        <div class="col-12 col-lg-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3">Customer Info</h5>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Customer Name</label>
                                        <input type="text" name="customer_name" class="form-control" value="Walk-in Customer" required>
                                    </div>

                                    <div class="mb-3">
                                            <label for="customer_phone" class="form-label">Phone Number</label>
                                            <input 
                                                type="tel" 
                                                name="customer_phone" 
                                                id="customer_phone" 
                                                class="form-control @error('customer_phone') is-invalid @enderror" 
                                                placeholder="017XXXXXXXX" 
                                                value="{{ old('customer_phone') }}"
                                                pattern="^(?:\+88|88)?01[3-9]\d{8}$"
                                                maxlength="14"
                                            >
                                            @error('customer_phone')
                                                <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Payment Method</label>
                                        <select name="payment_method" class="form-select" required>
                                            <option value="cash">Cash</option>
                                            <option value="bkash">bKash</option>
                                            <option value="nagad">Nagad</option>
                                            <option value="card">Card</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Notes</label>
                                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-8">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold mb-0">Products</h5>
                                        <button type="button" class="btn btn-dark btn-sm" id="addRowBtn">+ Add Product</button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-middle" id="productTable">
                                            <thead>
                                                <tr>
                                                    <th style="min-width: 220px;">Variant</th>
                                                    <th style="width: 100px;">Price</th>
                                                    <th style="width: 90px;">Qty</th>
                                                    <th style="width: 110px;">Subtotal</th>
                                                    <th style="width: 40px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="productRows">
                                                <tr class="item-row">
                                                    <td>
                                                        <select name="items[0][variant_id]" class="form-select variant-select" required>
                                                            <option value="">Select Item</option>
                                                            @foreach ($variants as $variant)
                                                                <option value="{{ $variant->id }}" data-price="{{ $variant->product->sale_price ?? $variant->product->price ?? 0 }}" data-stock="{{ $variant->stock }}">
                                                                    {{ $variant->product->name ?? 'Product' }} ({{ $variant->size }} - {{ $variant->color }}) [Stock: {{ $variant->stock }}]
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" name="items[0][price]" class="form-control item-price" readonly required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="items[0][quantity]" class="form-control item-qty" value="1" min="1" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control item-subtotal bg-light" readonly value="0.00">
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row" disabled>&times;</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="border-top pt-3 mt-3 d-flex justify-content-between align-items-center">
                                        <h5 class="fw-bold mb-0">Total:</h5>
                                        <h3 class="fw-bold text-success mb-0">৳<span id="grandTotal">0.00</span></h3>
                                    </div>

                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-dark px-4 py-2 fw-semibold">
                                            Save Sale & Print Receipt
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        let rowCount = 1;
        const rowsContainer = document.getElementById('productRows');
        const grandTotalSpan = document.getElementById('grandTotal');

        function calculateTotals() {
            let grand = 0;
            document.querySelectorAll('.item-row').forEach(function(row) {
                const price = parseFloat(row.querySelector('.item-price').value) || 0;
                const qty = parseInt(row.querySelector('.item-qty').value) || 0;
                const sub = price * qty;
                row.querySelector('.item-subtotal').value = sub.toFixed(2);
                grand += sub;
            });
            grandTotalSpan.textContent = grand.toFixed(2);
        }

        function setupRow(row) {
            const select = row.querySelector('.variant-select');
            const price = row.querySelector('.item-price');
            const qty = row.querySelector('.item-qty');
            const remove = row.querySelector('.remove-row');

            select.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                const unitPrice = opt.getAttribute('data-price') || 0;
                const maxStock = parseInt(opt.getAttribute('data-stock')) || 1;

                price.value = unitPrice;
                qty.max = maxStock;
                if (parseInt(qty.value) > maxStock) qty.value = maxStock;
                calculateTotals();
            });

            qty.addEventListener('input', function() {
                const maxStock = parseInt(this.max);
                if (maxStock && parseInt(this.value) > maxStock) this.value = maxStock;
                calculateTotals();
            });

            if (remove) {
                remove.addEventListener('click', function() {
                    if (document.querySelectorAll('.item-row').length > 1) {
                        row.remove();
                        calculateTotals();
                    }
                });
            }
        }

        setupRow(document.querySelector('.item-row'));

        document.getElementById('addRowBtn').addEventListener('click', function() {
            const firstRow = document.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelector('.variant-select').name = 'items[' + rowCount + '][variant_id]';
            newRow.querySelector('.variant-select').selectedIndex = 0;
            newRow.querySelector('.item-price').name = 'items[' + rowCount + '][price]';
            newRow.querySelector('.item-price').value = '';
            newRow.querySelector('.item-qty').name = 'items[' + rowCount + '][quantity]';
            newRow.querySelector('.item-qty').value = 1;
            newRow.querySelector('.item-subtotal').value = '0.00';

            const removeBtn = newRow.querySelector('.remove-row');
            removeBtn.disabled = false;

            rowsContainer.appendChild(newRow);
            setupRow(newRow);
            rowCount++;
        });
    </script>
</body>
</html>