-- Create new cart tables as per user requirements
DROP TABLE IF EXISTS sales_cart_items;
DROP TABLE IF EXISTS sales_cart;

CREATE TABLE sales_cart (
    cart_id SERIAL PRIMARY KEY,
    user_id INT NULL, 
    guest_user_id VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE sales_cart_items (
    item_id SERIAL PRIMARY KEY,
    cart_id INT NOT NULL REFERENCES sales_cart(cart_id) ON DELETE CASCADE,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(12, 4) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    brand_id INT NULL,
    image_path VARCHAR(255) NULL,
    attribute_json JSONB NULL,
    subtotal DECIMAL(12, 4) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES catalog_product_entity(entity_id) ON DELETE CASCADE,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL
);
