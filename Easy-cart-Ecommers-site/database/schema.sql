-- =======================================================
-- SCHEMA SETUP SCRIPT
-- =======================================================

-- 1. CATALOG: CATEGORIES
-- -------------------------------------------------------

-- Entity: Main Category Info
CREATE TABLE catalog_category_entity (
    entity_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Attribute: Variable data (e.g., Description)
CREATE TABLE catalog_category_attribute (
    attribute_id SERIAL PRIMARY KEY,
    category_id INT NOT NULL REFERENCES catalog_category_entity(entity_id) ON DELETE CASCADE,
    attribute_code VARCHAR(50) NOT NULL, -- e.g., 'description', 'meta_title'
    value TEXT,
    UNIQUE(category_id, attribute_code)
);


-- 2. CATALOG: PRODUCTS
-- -------------------------------------------------------

-- Entity: Main Product Info
CREATE TABLE catalog_product_entity (
    entity_id SERIAL PRIMARY KEY,
    sku VARCHAR(64) UNIQUE, -- Added SKU for professional structure
    name VARCHAR(255) NOT NULL,
    price DECIMAL(12, 4) NOT NULL DEFAULT 0.0000,
    brand VARCHAR(100),
    shipping_type VARCHAR(50), -- 'express', 'freight', etc.
    image VARCHAR(255),        -- Main thumbnail path
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Attribute: Product Attributes (Gallery, Description, Color, Size, etc.)
CREATE TABLE catalog_product_attribute (
    attribute_id SERIAL PRIMARY KEY,
    product_id INT NOT NULL REFERENCES catalog_product_entity(entity_id) ON DELETE CASCADE,
    attribute_code VARCHAR(50) NOT NULL, -- 'description', 'gallery_item', 'color', 'size'
    value TEXT
);
-- Note: 'gallery_item' can be repeated for multiple images if value is 1 URL per row, 
-- or we can store JSON in one row. Professional EAV usually does row-per-value for multi-selects.
-- Following logic "color, size also add image of gallery", we will store each gallery URL as a separate 'gallery_item' row.


-- 3. LINKING: PRODUCTS TO CATEGORIES
-- -------------------------------------------------------
CREATE TABLE catalog_category_product (
    category_id INT NOT NULL REFERENCES catalog_category_entity(entity_id) ON DELETE CASCADE,
    product_id INT NOT NULL REFERENCES catalog_product_entity(entity_id) ON DELETE CASCADE,
    PRIMARY KEY (category_id, product_id)
);


-- 4. SALES: QUOTE / CART
-- -------------------------------------------------------

-- Main Cart Table
CREATE TABLE sales_cart (
    entity_id SERIAL PRIMARY KEY,
    session_id VARCHAR(255) UNIQUE NOT NULL, -- Matches PHP Session ID
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cart Items (Products)
CREATE TABLE sales_cart_item (
    item_id SERIAL PRIMARY KEY,
    cart_id INT NOT NULL REFERENCES sales_cart(entity_id) ON DELETE CASCADE,
    product_id INT NOT NULL REFERENCES catalog_product_entity(entity_id) ON DELETE CASCADE,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cart Shipping Method Selection
CREATE TABLE sales_cart_shipping (
    entity_id SERIAL PRIMARY KEY,
    cart_id INT NOT NULL REFERENCES sales_cart(entity_id) ON DELETE CASCADE,
    method_code VARCHAR(50), -- e.g., 'express', 'standard'
    carrier_code VARCHAR(50), -- e.g., 'fedex', 'dhl'
    amount DECIMAL(12, 4) DEFAULT 0.0000
);

-- Cart Payment Selection (Billing Info placeholder)
CREATE TABLE sales_cart_payment (
    entity_id SERIAL PRIMARY KEY,
    cart_id INT NOT NULL REFERENCES sales_cart(entity_id) ON DELETE CASCADE,
    method_code VARCHAR(50) -- e.g., 'credit_card', 'paypal'
);

-- Cart Address (Billing & Shipping)
CREATE TABLE sales_cart_address (
    entity_id SERIAL PRIMARY KEY,
    cart_id INT NOT NULL REFERENCES sales_cart(entity_id) ON DELETE CASCADE,
    address_type VARCHAR(20) NOT NULL, -- 'billing' or 'shipping'
    firstname VARCHAR(100),
    lastname VARCHAR(100),
    email VARCHAR(255),
    street TEXT,
    city VARCHAR(100),
    region VARCHAR(100),
    postcode VARCHAR(20),
    telephone VARCHAR(20)
);


-- 5. SALES: ORDERS
-- -------------------------------------------------------

-- Order Main Table
CREATE TABLE sales_order (
    entity_id SERIAL PRIMARY KEY,
    increment_id VARCHAR(32) UNIQUE, -- Friendly Order # (e.g., 100001)
    status VARCHAR(32) DEFAULT 'pending', -- 'processing', 'completed', 'cancelled'
    
    -- Pricing
    subtotal DECIMAL(12, 4) NOT NULL,
    shipping_amount DECIMAL(12, 4) NOT NULL,
    tax_amount DECIMAL(12, 4) NOT NULL,
    grand_total DECIMAL(12, 4) NOT NULL,
    
    -- Customer Info Snapshot
    customer_email VARCHAR(255),
    customer_firstname VARCHAR(100),
    customer_lastname VARCHAR(100),
    
    -- Method snapshots
    shipping_method VARCHAR(100),
    payment_method VARCHAR(50),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order Items
CREATE TABLE sales_order_item (
    item_id SERIAL PRIMARY KEY,
    order_id INT NOT NULL REFERENCES sales_order(entity_id) ON DELETE CASCADE,
    product_id INT, -- Nullable if product is deleted later, we keep record
    
    -- Snapshot data (Price at time of purchase)
    sku VARCHAR(64),
    name VARCHAR(255),
    price DECIMAL(12, 4),
    quantity INT,
    row_total DECIMAL(12, 4)
);


-- =======================================================
-- DATA POPULATION (MIGRATION)
-- =======================================================

-- 1. Insert Categories (From data/categories.php)
-- 'Electronics' (1), 'Clothing' (2), 'Home & Kitchen' (3), 'Sports & Outdoors' (4), 'Books' (5), 'Accessories' (6), 'Footwear' (New based on products)
INSERT INTO catalog_category_entity (entity_id, name) VALUES
(1, 'Electronics'),
(2, 'Clothing'),
(3, 'Home & Kitchen'),
(4, 'Sports & Outdoors'),
(5, 'Books'),
(6, 'Accessories'),
(7, 'Footwear');

-- Reset sequence
SELECT setval('catalog_category_entity_entity_id_seq', (SELECT MAX(entity_id) FROM catalog_category_entity));


-- 2. Insert Products (From data/products.php)
-- Mapped 'sku' using Name logic for professionalism
INSERT INTO catalog_product_entity (entity_id, sku, name, price, brand, shipping_type, image) VALUES
(1, 'SONY-WH-1000XM4', 'Wireless Bluetooth Headphones', 150.00, 'Sony', 'express', 'images/Wireless Bluetooth Headphones.jpg'),
(2, 'APPLE-WATCH-S5', 'Smart Watch Series 5', 800.00, 'Apple', 'freight', 'images/Smart Watch Series 5.jpg'),
(3, 'NIKE-BACKPACK', 'Laptop Backpack', 200.00, 'Nike', 'express', 'images/laptop-bag.jpg'),
(4, 'LOGI-MOUSE-WL', 'Wireless Mouse', 350.00, 'Logitech', 'freight', 'images/Wireless Mouse.jpg'),
(5, 'SAM-USBC-CABLE', 'USB-C Charging Cable', 180.00, 'Samsung', 'express', 'images/USB-C Charging Cable.jpg'),
(6, 'LOGI-MECH-KB', 'Mechanical Keyboard', 700.00, 'Logitech', 'freight', 'images/Mechanical Keyboard.jfif'),
(7, 'MARSHALL-SPK', 'Portable Speaker', 500.00, 'Marshall', 'freight', 'images/Portable Speaker Marshall.jfif'),
(8, 'APPLE-CASE', 'Phone Case', 120.00, 'Apple', 'express', 'images/Phone Case.jfif'),
(9, 'GEN-LAPTOP-STD', 'Laptop Stand', 240.00, 'Generic', 'express', 'images/Laptop Stand.jfif'),
(10, 'LOGI-WEBCAM', 'Webcam HD', 600.00, 'Logitech', 'freight', 'images/Webcam HD.jfif'),
(11, 'SAM-WL-CHG', 'Wireless Charger', 290.00, 'Samsung', 'express', 'images/Wireless Charger.jfif'),
(12, 'GEN-MOUSEPAD', 'Gaming Mouse Pad', 100.00, 'Generic', 'express', 'images/Gaming Mouse Pad.jfif'),
(13, 'NIKE-AIRMAX', 'Nike Air Max', 1000.00, 'Nike', 'freight', 'images/Nikeshoes.jfif'),
(14, 'PUMA-RUN', 'Puma Running Shoes', 950.00, 'Puma', 'freight', 'images/pumashoes.jfif'),
(15, 'ADIDAS-SAMBA', 'Adidas Samba', 550.00, 'Adidas', 'freight', 'images/adidas samba_shoes2.jfif'),
(16, 'SAM-S25U', 'Samsung S25 Ultra', 12500.00, 'Samsung', 'freight', 'images/samsungS25_ultra.jfif'),
(17, 'ADIDAS-SET', 'Adidas Sportswear Set', 220.00, 'Adidas', 'express', 'images/adidas_clothes.jfif');

-- Reset sequence
SELECT setval('catalog_product_entity_entity_id_seq', (SELECT MAX(entity_id) FROM catalog_product_entity));


-- 3. Insert Product Attributes (Description)
INSERT INTO catalog_product_attribute (product_id, attribute_code, value) VALUES
(1, 'description', 'Premium wireless headphones with noise cancellation, 30-hour battery life, and superior sound quality. Perfect for music lovers and professionals.'),
(2, 'description', 'Advanced smartwatch with health tracking, GPS, water resistance, and seamless integration with your devices.'),
(3, 'description', 'Durable and spacious laptop backpack with multiple compartments, padded laptop sleeve, and ergonomic design.'),
(4, 'description', 'Ergonomic wireless mouse with precision tracking, long battery life, and comfortable grip for all-day use.'),
(5, 'description', 'Fast charging USB-C cable with durable braided design, compatible with all USB-C devices.'),
(6, 'description', 'RGB mechanical gaming keyboard with customizable keys, tactile switches, and premium build quality.'),
(7, 'description', 'Powerful portable Bluetooth speaker with rich sound, long battery life, and rugged design.'),
(8, 'description', 'Premium protective phone case with slim design, shock absorption, and precise cutouts.'),
(9, 'description', 'Adjustable aluminum laptop stand for better ergonomics and improved airflow.'),
(10, 'description', 'Full HD 1080p webcam with auto-focus, noise-reducing mic, and wide-angle lens.'),
(11, 'description', 'Fast wireless charging pad compatible with all Qi-enabled devices.'),
(12, 'description', 'Large gaming mouse pad with smooth surface and non-slip rubber base.'),
(13, 'description', 'Classic Nike sneakers offering reliable comfort and iconic style for everyday wear.'),
(14, 'description', 'High-performance running shoes from Puma with superior cushioning and grip.'),
(15, 'description', 'The authentic Adidas Samba shoes, featuring a timeless design and premium materials.'),
(16, 'description', 'The ultimate Galaxy smartphone with pro-grade camera, powerful performance, and S Pen.'),
(17, 'description', 'Comfortable and stylish activewear set from Adidas, perfect for workouts or casual wear.');


-- 4. Insert Product Attributes (Gallery Images)
-- Mapping from original array
INSERT INTO catalog_product_attribute (product_id, attribute_code, value) VALUES
-- Product 1
(1, 'gallery_item', 'https://images.unsplash.com/photo-1505739718967-6df30ff369c7?q=80&w=1000'),
(1, 'gallery_item', 'https://images.unsplash.com/photo-1674989844487-722ec77b9b81?w=500&auto=format&fit=crop'),
(1, 'gallery_item', 'https://images.unsplash.com/photo-1679533662345-b321cf2d8792?w=500&auto=format&fit=crop'),
-- Product 2
(2, 'gallery_item', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1000'),
(2, 'gallery_item', 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?q=80&w=1000'),
(2, 'gallery_item', 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?q=80&w=1000'),
-- Product 3
(3, 'gallery_item', 'https://images.unsplash.com/photo-1667411425023-5cdf74d77ede?w=500&auto=format&fit=crop'),
(3, 'gallery_item', 'https://images.unsplash.com/photo-1667411424594-672f7a3df708?w=500&auto=format&fit=crop'),
(3, 'gallery_item', 'https://images.unsplash.com/photo-1667411425106-9e3aa26517c4?w=500&auto=format&fit=crop'),
-- Product 4
(4, 'gallery_item', 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?q=80&w=1000'),
(4, 'gallery_item', 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?q=80&w=1000'),
(4, 'gallery_item', 'https://images.unsplash.com/photo-1707592691247-5c3a1c7ba0e3?w=500&auto=format&fit=crop'),
-- Product 5
(5, 'gallery_item', 'https://plus.unsplash.com/premium_photo-1669262667978-5d4aafe29dd5?w=500&auto=format&fit=crop'),
(5, 'gallery_item', 'https://images.unsplash.com/photo-1705661151073-d0ca8ef518e6?w=500&auto=format&fit=crop'),
(5, 'gallery_item', 'https://plus.unsplash.com/premium_photo-1760502350698-33e058bcde22?w=500&auto=format&fit=crop'),
-- Product 6
(6, 'gallery_item', 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?q=80&w=1000'),
(6, 'gallery_item', 'https://images.unsplash.com/photo-1595225476474-87563907a212?q=80&w=1000'),
(6, 'gallery_item', 'https://images.unsplash.com/photo-1626958390898-162d3577f293?w=500&auto=format&fit=crop'),
-- Product 7
(7, 'gallery_item', 'https://images.unsplash.com/photo-1699567364063-6e3a3768b564?w=500&auto=format&fit=crop'),
(7, 'gallery_item', 'https://images.unsplash.com/photo-1692351014024-97edd83a7b5a?w=500&auto=format&fit=crop'),
(7, 'gallery_item', 'https://images.unsplash.com/photo-1699567362704-81cf1f7eed94?w=500&auto=format&fit=crop'),
-- Product 8
(8, 'gallery_item', 'https://images.unsplash.com/photo-1711033312367-247626a984d1?w=500&auto=format&fit=crop'),
(8, 'gallery_item', 'https://images.unsplash.com/photo-1601593346740-925612772716?q=80&w=1000'),
(8, 'gallery_item', 'https://images.unsplash.com/photo-1589895224585-94d71f7873fc?w=500&auto=format&fit=crop'),
-- Product 9
(9, 'gallery_item', 'https://images.unsplash.com/photo-1623567238235-940ff1311da7?w=500&auto=format&fit=crop'),
(9, 'gallery_item', 'https://plus.unsplash.com/premium_photo-1683736986821-e4662912a70d?w=500&auto=format&fit=crop'),
(9, 'gallery_item', 'https://images.unsplash.com/photo-1623251606108-512c7c4a3507?w=500&auto=format&fit=crop'),
-- Product 10
(10, 'gallery_item', 'https://images.unsplash.com/photo-1614588876378-b2ffa4520c22?w=500&auto=format&fit=crop'),
(10, 'gallery_item', 'https://images.unsplash.com/photo-1636569826709-8e07f6104992?w=500&auto=format&fit=crop'),
(10, 'gallery_item', 'https://images.unsplash.com/photo-1626581795188-8efb9a00eeec?w=500&auto=format&fit=crop'),
-- Product 11
(11, 'gallery_item', 'https://images.unsplash.com/photo-1591290619618-904f6dd935e3?w=500&auto=format&fit=crop'),
(11, 'gallery_item', 'https://plus.unsplash.com/premium_photo-1661481079679-04e8a19e258c?w=500&auto=format&fit=crop'),
(11, 'gallery_item', 'https://images.unsplash.com/photo-1615526675159-e248c3021d3f?w=500&auto=format&fit=crop'),
-- Product 12
(12, 'gallery_item', 'https://images.unsplash.com/photo-1629429408209-1f912961dbd8?w=500&auto=format&fit=crop'),
(12, 'gallery_item', 'https://images.unsplash.com/photo-1629429408708-3a59f51979c5?w=500&auto=format&fit=crop'),
(12, 'gallery_item', 'https://images.unsplash.com/photo-1707858057802-ab1227691ed5?w=500&auto=format&fit=crop'),
-- Product 13
(13, 'gallery_item', 'https://images.unsplash.com/photo-1662411198835-c5a151d2af9e?w=500&auto=format&fit=crop'),
(13, 'gallery_item', 'https://images.unsplash.com/photo-1620138546918-2235a6e88ee0?w=500&auto=format&fit=crop'),
(13, 'gallery_item', 'https://images.unsplash.com/photo-1660633777105-9521b3757393?w=500&auto=format&fit=crop'),
-- Product 14
(14, 'gallery_item', 'https://images.unsplash.com/photo-1581923597046-427a5d83f932?w=500&auto=format&fit=crop'),
(14, 'gallery_item', 'https://images.unsplash.com/photo-1714396239552-09ec1c968241?w=500&auto=format&fit=crop'),
(14, 'gallery_item', 'https://images.unsplash.com/photo-1714396239552-09ec1c968241?w=500&auto=format&fit=crop'),
-- Product 15
(15, 'gallery_item', 'https://images.unsplash.com/photo-1718220130188-428c7dc27fd2?w=500&auto=format&fit=crop'),
(15, 'gallery_item', 'https://images.unsplash.com/photo-1718220095476-7916e897fc55?w=500&auto=format&fit=crop'),
(15, 'gallery_item', 'https://images.unsplash.com/photo-1761972693261-57adf95011d6?w=500&auto=format&fit=crop'),
-- Product 16
(16, 'gallery_item', 'https://images.unsplash.com/photo-1738830274216-20f63b8a0c02?w=500&auto=format&fit=crop'),
(16, 'gallery_item', 'https://images.unsplash.com/photo-1709744722656-9b850470293f?w=500&auto=format&fit=crop'),
(16, 'gallery_item', 'https://images.unsplash.com/photo-1738830256741-5bc3d0d1571c?w=500&auto=format&fit=crop'),
-- Product 17
(17, 'gallery_item', 'https://images.unsplash.com/photo-1589178698744-b0c65639636e?w=500&auto=format&fit=crop'),
(17, 'gallery_item', 'https://images.unsplash.com/photo-1614231125961-38323d6c485b?w=500&auto=format&fit=crop'),
(17, 'gallery_item', 'https://images.unsplash.com/photo-1555274175-75f4056dfd05?w=500&auto=format&fit=crop');


-- 5. Insert Category-Product Links
INSERT INTO catalog_category_product (product_id, category_id) VALUES
(1, 1), -- Electronics
(2, 1),
(3, 6), -- Accessories
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 6),
(9, 6),
(10, 1),
(11, 1),
(12, 6),
(13, 7), -- Footwear
(14, 7),
(15, 7),
(16, 1),
(17, 2); -- Clothing
