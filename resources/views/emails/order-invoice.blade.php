```blade
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>

    <h2>
        Thank you for your order!
    </h2>

    <p>
        Dear {{ $order->customer_name }},
    </p>

    <p>
        Your order has been successfully placed with Hypeline.
    </p>

    <p>
        <strong>Order ID:</strong>
        {{ $order->order_code }}
    </p>

    <p>
        <strong>Total Amount:</strong>
        {{ number_format($order->total_amount, 2) }}
    </p>

    <p>
        Your invoice is attached to this email as a PDF.
    </p>

    <p>
        Thank you for shopping with Hypeline.
    </p>

</body>

</html>
```
