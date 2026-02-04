-- Add status column to sales_cart_items
ALTER TABLE sales_cart_items 
ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'active';

-- Add check constraint to ensure only 'active' or 'inactive' values
ALTER TABLE sales_cart_items 
ADD CONSTRAINT check_cart_item_status 
CHECK (status IN ('active', 'inactive'));
