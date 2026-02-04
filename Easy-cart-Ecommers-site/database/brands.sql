-- Create Brands Table
-- Renamed brand_id to id as requested
CREATE TABLE IF NOT EXISTS brands (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    logo_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Brand Data
INSERT INTO brands (name, logo_url) VALUES
('Apple', 'https://logos-world.net/wp-content/uploads/2020/04/Apple-Logo.png'),
('Samsung', 'https://logos-world.net/wp-content/uploads/2020/04/Samsung-Logo.png'),
('Nike', 'https://logos-world.net/wp-content/uploads/2020/04/Nike-Logo.png'),
('Puma', 'https://logos-world.net/wp-content/uploads/2020/04/Puma-Logo.png'),
('Adidas', 'https://logos-world.net/wp-content/uploads/2020/04/Adidas-Logo.png'),
('Sony', 'https://logos-world.net/wp-content/uploads/2020/04/Sony-Logo.png'),
('Logitech', 'https://1000logos.net/wp-content/uploads/2020/09/Logitech-Logo.png'),
('Marshall', 'https://logos-world.net/wp-content/uploads/2020/11/Marshall-Logo.png');
