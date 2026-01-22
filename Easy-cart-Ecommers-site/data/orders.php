<?php
// Orders Data Array
// Each order contains: order_id, date, total, status

$orders = [
    [
        'order_id' => 'ORD-2025-001',
        'date' => '2025-01-15',
        'total' => 13000,
        'status' => 'Delivered',
        'items' => [
            ['product_id' => 1, 'quantity' => 1, 'price' => 6000],
            ['product_id' => 4, 'quantity' => 2, 'price' => 4000],
            ['product_id' => 5, 'quantity' => 1, 'price' => 1000]
        ]
    ],
    [
        'order_id' => 'ORD-2025-002',
        'date' => '2025-01-18',
        'total' => 24000,
        'status' => 'Shipped',
        'items' => [
            ['product_id' => 2, 'quantity' => 1, 'price' => 24000]
        ]
    ],
    [
        'order_id' => 'ORD-2025-003',
        'date' => '2025-01-20',
        'total' => 12000,
        'status' => 'Processing',
        'items' => [
            ['product_id' => 6, 'quantity' => 1, 'price' => 7000],
            ['product_id' => 7, 'quantity' => 1, 'price' => 5000]
        ]
    ]
];
?>
