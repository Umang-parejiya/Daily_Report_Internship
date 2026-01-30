<?php
// Products Data Array
// Each product contains: id, name, price, category, brand, description, image, gallery, shipping_type

$products = [
    [
        'id' => 1,
        'name' => 'Wireless Bluetooth Headphones',
        'price' => 150,
        'category' => 'Electronics',
        'brand' => 'Sony',
        'description' => 'Premium wireless headphones with noise cancellation, 30-hour battery life, and superior sound quality. Perfect for music lovers and professionals.',
        'image' => 'images/Wireless Bluetooth Headphones.jpg',
        'gallery' => [
            'https://images.unsplash.com/photo-1505739718967-6df30ff369c7?q=80&w=1000',
            'https://images.unsplash.com/photo-1674989844487-722ec77b9b81?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1679533662345-b321cf2d8792?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'express'
    ],
    [
        'id' => 2,
        'name' => 'Smart Watch Series 5',
        'price' => 800,
        'category' => 'Electronics',
        'brand' => 'Apple',
        'description' => 'Advanced smartwatch with health tracking, GPS, water resistance, and seamless integration with your devices.',
        'image' => 'images/Smart Watch Series 5.jpg',
        'gallery' => [
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1000',
            'https://images.unsplash.com/photo-1546868871-7041f2a55e12?q=80&w=1000',
            'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?q=80&w=1000'
        ],
        'shipping_type' => 'freight'
    ],
    [
        'id' => 3,
        'name' => 'Laptop Backpack',
        'price' => 200,
        'category' => 'Accessories',
        'brand' => 'Nike',
        'description' => 'Durable and spacious laptop backpack with multiple compartments, padded laptop sleeve, and ergonomic design.',
        'image' => 'images\laptop-bag.jpg',
        'gallery' => [
            'https://images.unsplash.com/photo-1667411425023-5cdf74d77ede?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1667411424594-672f7a3df708?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1667411425106-9e3aa26517c4?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'express'
    ],
    [
        'id' => 4,
        'name' => 'Wireless Mouse',
        'price' => 350,
        'category' => 'Electronics',
        'brand' => 'Logitech',
        'description' => 'Ergonomic wireless mouse with precision tracking, long battery life, and comfortable grip for all-day use.',
        'image' => 'images/Wireless Mouse.jpg',
        'gallery' => [
            'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?q=80&w=1000',
            'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?q=80&w=1000',
            'https://images.unsplash.com/photo-1707592691247-5c3a1c7ba0e3?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'freight'
    ],
    [
        'id' => 5,
        'name' => 'USB-C Charging Cable',
        'price' => 180,
        'category' => 'Electronics',
        'brand' => 'Samsung',
        'description' => 'Fast charging USB-C cable with durable braided design, compatible with all USB-C devices.',
        'image' => 'images/USB-C Charging Cable.jpg',
        'gallery' => [
            'https://plus.unsplash.com/premium_photo-1669262667978-5d4aafe29dd5?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1705661151073-d0ca8ef518e6?w=500&auto=format&fit=crop',
            'https://plus.unsplash.com/premium_photo-1760502350698-33e058bcde22?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'express'
    ],
    [
        'id' => 6,
        'name' => 'Mechanical Keyboard',
        'price' => 700,
        'category' => 'Electronics',
        'brand' => 'Logitech',
        'description' => 'RGB mechanical gaming keyboard with customizable keys, tactile switches, and premium build quality.',
        'image' => 'images/Mechanical Keyboard.jfif',
        'gallery' => [
            'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?q=80&w=1000',
            'https://images.unsplash.com/photo-1595225476474-87563907a212?q=80&w=1000',
            'https://images.unsplash.com/photo-1626958390898-162d3577f293?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'freight'
    ],
    [
        'id' => 7,
        'name' => 'Portable Speaker',
        'price' => 500,
        'category' => 'Electronics',
        'brand' => 'Marshall',
        'description' => 'Powerful portable Bluetooth speaker with rich sound, long battery life, and rugged design.',
        'image' => 'images/Portable Speaker Marshall.jfif',
        'gallery' => [
            'https://images.unsplash.com/photo-1699567364063-6e3a3768b564?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1692351014024-97edd83a7b5a?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1699567362704-81cf1f7eed94?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'freight'
    ],
    [
        'id' => 8,
        'name' => 'Phone Case',
        'price' => 120,
        'category' => 'Accessories',
        'brand' => 'Apple',
        'description' => 'Premium protective phone case with slim design, shock absorption, and precise cutouts.',
        'image' => 'images/Phone Case.jfif',
        'gallery' => [
            'https://images.unsplash.com/photo-1711033312367-247626a984d1?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1601593346740-925612772716?q=80&w=1000',
            'https://images.unsplash.com/photo-1589895224585-94d71f7873fc?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'express'
    ],
    [
        'id' => 9,
        'name' => 'Laptop Stand',
        'price' => 240,
        'category' => 'Accessories',
        'brand' => 'Generic',
        'description'=> 'Adjustable aluminum laptop stand for better ergonomics and improved airflow.',
        'image' => 'images/Laptop Stand.jfif',
        'gallery' => [
            'https://images.unsplash.com/photo-1623567238235-940ff1311da7?w=500&auto=format&fit=crop',
            'https://plus.unsplash.com/premium_photo-1683736986821-e4662912a70d?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1623251606108-512c7c4a3507?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'express'
    ],
    [
        'id' => 10,
        'name' => 'Webcam HD',
        'price' => 600,
        'category' => 'Electronics',
        'brand' => 'Logitech',
        'description' => 'Full HD 1080p webcam with auto-focus, noise-reducing mic, and wide-angle lens.',
        'image' => 'images/Webcam HD.jfif',
        'gallery' => [
            'https://images.unsplash.com/photo-1614588876378-b2ffa4520c22?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1636569826709-8e07f6104992?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1626581795188-8efb9a00eeec?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'freight'
    ],
    [
        'id' => 11,
        'name' => 'Wireless Charger',
        'price' => 290,
        'category' => 'Electronics',
        'brand' => 'Samsung',
        'description' => 'Fast wireless charging pad compatible with all Qi-enabled devices.',
        'image' => 'images/Wireless Charger.jfif',
        'gallery' => [
            'https://images.unsplash.com/photo-1591290619618-904f6dd935e3?w=500&auto=format&fit=crop',
            'https://plus.unsplash.com/premium_photo-1661481079679-04e8a19e258c?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1615526675159-e248c3021d3f?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'express'
    ],
    [
        'id' => 12,
        'name' => 'Gaming Mouse Pad',
        'price' => 100,
        'category' => 'Accessories',
        'brand' => 'Generic',
        'description' => 'Large gaming mouse pad with smooth surface and non-slip rubber base.',
        'image' => 'images/Gaming Mouse Pad.jfif',
        'gallery' => [
            'https://images.unsplash.com/photo-1629429408209-1f912961dbd8?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1629429408708-3a59f51979c5?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1707858057802-ab1227691ed5?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'express'
    ],
    [
        'id' => 13,
        'name' => 'Nike Air Max',
        'price' => 1000,
        'category' => 'Footwear',
        'brand' => 'Nike',
        'description' => 'Classic Nike sneakers offering reliable comfort and iconic style for everyday wear.',
        'image' => 'images/Nikeshoes.jfif',
        'gallery' => [
            'https://images.unsplash.com/photo-1662411198835-c5a151d2af9e?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1620138546918-2235a6e88ee0?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1660633777105-9521b3757393?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'freight'
    ],
    [
        'id' => 14,
        'name' => 'Puma Running Shoes',
        'price' => 950,
        'category' => 'Footwear',
        'brand' => 'Puma',
        'description' => 'High-performance running shoes from Puma with superior cushioning and grip.',
        'image' => 'images/pumashoes.jfif',
        'gallery' => [
            'https://images.unsplash.com/photo-1581923597046-427a5d83f932?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1714396239552-09ec1c968241?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1714396239552-09ec1c968241?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'freight'
    ],
    [
        'id' => 15,
        'name' => 'Adidas Samba',
        'price' => 550,
        'category' => 'Footwear',
        'brand' => 'Adidas',
        'description' => 'The authentic Adidas Samba shoes, featuring a timeless design and premium materials.',
        'image' => 'images/adidas samba_shoes2.jfif',
        'gallery' => [
            'https://images.unsplash.com/photo-1718220130188-428c7dc27fd2?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1718220095476-7916e897fc55?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1761972693261-57adf95011d6?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'freight'
    ],
    [
        'id' => 16,
        'name' => 'Samsung S25 Ultra',
        'price' => 12500,
        'category' => 'Electronics',
        'brand' => 'Samsung',
        'description' => 'The ultimate Galaxy smartphone with pro-grade camera, powerful performance, and S Pen.',
        'image' => 'images/samsungS25_ultra.jfif',
        'gallery' => [
            'https://images.unsplash.com/photo-1738830274216-20f63b8a0c02?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1709744722656-9b850470293f?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1738830256741-5bc3d0d1571c?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'freight'
    ],
    [
        'id' => 17,
        'name' => 'Adidas Sportswear Set',
        'price' => 220,
        'category' => 'Clothing',
        'brand' => 'Adidas',
        'description' => 'Comfortable and stylish activewear set from Adidas, perfect for workouts or casual wear.',
        'image' => 'images/adidas_clothes.jfif',
        'gallery' => [
            'https://images.unsplash.com/photo-1589178698744-b0c65639636e?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1614231125961-38323d6c485b?w=500&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1555274175-75f4056dfd05?w=500&auto=format&fit=crop'
        ],
        'shipping_type' => 'express'
    ]
];
?>
