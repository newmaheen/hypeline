```blade
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>

    <h2>
        Hypeline Order Status Update
    </h2>

    <p>
        Dear {{ $order->customer_name }},
    </p>

    <p>
        Your order status has been updated.
    </p>

    <p>
        <strong>Order ID:</strong>
        {{ $order->order_code }}
    </p>

    <p>
        <strong>Current Status:</strong>
        {{ ucfirst($order->status) }}
    </p>

    <p>
        <strong>Total Amount:</strong>
        {{ number_format($order->total_amount, 2) }}
    </p>

    <p>
        Thank you for shopping with Hypeline.
    </p>

</body>

</html>
```
