-- Seasonal Wardrobe seed data
-- Run schema.sql first, then import this file into the seasonal_wardrobe database.

-- Clear existing seeded data in a safe order.
DELETE FROM product_store_availability;
DELETE FROM products;
DELETE FROM stores;
DELETE FROM categories;

ALTER TABLE categories AUTO_INCREMENT = 1;
ALTER TABLE stores AUTO_INCREMENT = 1;
ALTER TABLE products AUTO_INCREMENT = 1;

-- Insert seasonal categories.
INSERT INTO categories (id, name, slug, description) VALUES
    (1, 'Spring Collection', 'spring', 'Fresh and colorful styles for warm spring days.'),
    (2, 'Summer Collection', 'summer', 'Lightweight essentials designed for sunny weather.'),
    (3, 'Autumn Collection', 'autumn', 'Layered looks and earthy tones for the fall season.'),
    (4, 'Winter Collection', 'winter', 'Cozy outerwear and cold-weather fashion staples.');

-- Insert physical store locations.
INSERT INTO stores (id, name, address, city, map_url) VALUES
    (1, 'District 1 Store', '152 Nguyen Hue Street, Ben Nghe Ward', 'Ho Chi Minh City', 'https://www.google.com/maps/search/?api=1&query=152+Nguyen+Hue+Street+District+1+Ho+Chi+Minh+City'),
    (2, 'Thu Duc Store', '45 Vo Van Ngan Street, Linh Chieu Ward', 'Ho Chi Minh City', 'https://www.google.com/maps/search/?api=1&query=45+Vo+Van+Ngan+Street+Thu+Duc+Ho+Chi+Minh+City'),
    (3, 'Tan Binh Store', '208 Cong Hoa Street, Ward 12', 'Ho Chi Minh City', 'https://www.google.com/maps/search/?api=1&query=208+Cong+Hoa+Street+Tan+Binh+Ho+Chi+Minh+City');

-- Insert products for all four seasonal collections.
INSERT INTO products (
    id,
    category_id,
    name,
    slug,
    product_type,
    description,
    price,
    image,
    rating,
    stock
) VALUES
    (1, 1, 'Floral Spring Dress', 'floral-spring-dress', 'Dress', 'A breezy floral dress with a soft silhouette for spring outings.', 49.90, 'floral-spring-dress.jpg', 4.7, 12),
    (2, 1, 'Light Denim Jacket', 'light-denim-jacket', 'Jacket', 'A light wash denim jacket that layers easily over casual outfits.', 58.50, 'light-denim-jacket.jpg', 4.6, 15),
    (3, 1, 'Pastel Cotton Shirt', 'pastel-cotton-shirt', 'Shirt', 'A soft cotton shirt in pastel tones for a clean spring look.', 32.00, 'pastel-cotton-shirt.jpg', 4.4, 11),
    (4, 1, 'Spring Casual Sneakers', 'spring-casual-sneakers', 'Shoes', 'Comfortable low-top sneakers that pair well with daily wear.', 55.00, 'spring-casual-sneakers.jpg', 4.5, 10),
    (5, 1, 'Soft Knit Cardigan', 'soft-knit-cardigan', 'Cardigan', 'A lightweight knit cardigan for cool mornings and evening walks.', 44.75, 'soft-knit-cardigan.jpg', 4.3, 8),
    (6, 1, 'Pleated Midi Skirt', 'pleated-midi-skirt', 'Skirt', 'A flowing midi skirt with soft pleats and an easy spring fit.', 39.90, 'pleated-midi-skirt.jpg', 4.4, 12),
    (7, 2, 'Summer Linen Shirt', 'summer-linen-shirt', 'Shirt', 'A breathable linen shirt built for warm afternoons and vacations.', 36.50, 'summer-linen-shirt.jpg', 4.6, 14),
    (8, 2, 'Cotton Basic T-shirt', 'cotton-basic-tshirt', 'T-shirt', 'A clean everyday T-shirt made from soft cotton jersey.', 22.00, 'cotton-basic-tshirt.jpg', 4.2, 15),
    (9, 2, 'Beach Sandals', 'beach-sandals', 'Shoes', 'Easy slip-on sandals with a flexible sole for summer comfort.', 24.90, 'beach-sandals.jpg', 4.1, 9),
    (10, 2, 'Lightweight Shorts', 'lightweight-shorts', 'Shorts', 'Relaxed fit shorts designed to stay cool on hot days.', 28.50, 'lightweight-shorts.jpg', 4.3, 13),
    (11, 2, 'Sleeveless Summer Dress', 'sleeveless-summer-dress', 'Dress', 'A sleeveless dress with a light drape for sunny weekend plans.', 47.00, 'sleeveless-summer-dress.jpg', 4.5, 11),
    (12, 2, 'Breathable Canvas Shoes', 'breathable-canvas-shoes', 'Shoes', 'Casual canvas shoes with a breathable upper and simple style.', 42.25, 'breathable-canvas-shoes.jpg', 4.4, 12),
    (13, 3, 'Brown Autumn Hoodie', 'brown-autumn-hoodie', 'Hoodie', 'A cozy hoodie in a warm brown tone for cool autumn evenings.', 46.90, 'brown-autumn-hoodie.jpg', 4.6, 10),
    (14, 3, 'Plaid Flannel Shirt', 'plaid-flannel-shirt', 'Shirt', 'A classic plaid flannel shirt that works well for layering.', 38.75, 'plaid-flannel-shirt.jpg', 4.5, 12),
    (15, 3, 'Chino Pants', 'chino-pants', 'Pants', 'Slim straight chino pants suited for both casual and smart looks.', 41.50, 'chino-pants.jpg', 4.4, 15),
    (16, 3, 'Suede Casual Shoes', 'suede-casual-shoes', 'Shoes', 'Soft suede shoes with a clean profile for daily autumn wear.', 63.00, 'suede-casual-shoes.jpg', 4.5, 8),
    (17, 3, 'Knit Sweater', 'knit-sweater', 'Sweater', 'A medium-weight knit sweater that adds warmth without bulk.', 43.90, 'knit-sweater.jpg', 4.3, 9),
    (18, 3, 'Lightweight Trench Coat', 'lightweight-trench-coat', 'Coat', 'A polished trench coat with a light structure for transitional weather.', 79.00, 'lightweight-trench-coat.jpg', 4.7, 6),
    (19, 4, 'Winter Wool Coat', 'winter-wool-coat', 'Coat', 'A tailored wool coat that brings warmth and a refined winter style.', 98.00, 'winter-wool-coat.jpg', 4.8, 7),
    (20, 4, 'Thermal Hoodie', 'thermal-hoodie', 'Hoodie', 'A fleece-lined hoodie made for comfortable layering in cold weather.', 52.50, 'thermal-hoodie.jpg', 4.6, 12),
    (21, 4, 'Puffer Jacket', 'puffer-jacket', 'Jacket', 'A padded jacket with a lightweight feel and reliable warmth.', 88.75, 'puffer-jacket.jpg', 4.7, 10),
    (22, 4, 'Wool Scarf', 'wool-scarf', 'Accessories', 'A soft wool scarf that adds warmth and texture to winter outfits.', 26.00, 'wool-scarf.jpg', 4.4, 15),
    (23, 4, 'Winter Boots', 'winter-boots', 'Shoes', 'Durable ankle boots with a sturdy sole for colder days.', 91.20, 'winter-boots.jpg', 4.6, 8),
    (24, 4, 'Thick Knit Sweater', 'thick-knit-sweater', 'Sweater', 'A thick knit sweater with a relaxed fit for peak winter comfort.', 54.30, 'thick-knit-sweater.jpg', 4.5, 11);

-- Insert store availability for products.
INSERT INTO product_store_availability (product_id, store_id, quantity) VALUES
    (1, 1, 5),
    (1, 2, 7),
    (2, 1, 8),
    (2, 3, 7),
    (3, 2, 6),
    (3, 3, 5),
    (4, 1, 4),
    (4, 2, 6),
    (5, 1, 3),
    (5, 3, 5),
    (6, 2, 5),
    (6, 3, 7),
    (7, 1, 6),
    (7, 2, 8),
    (8, 1, 7),
    (8, 3, 8),
    (9, 2, 4),
    (9, 3, 5),
    (10, 1, 5),
    (10, 2, 8),
    (11, 1, 3),
    (11, 3, 8),
    (12, 2, 5),
    (12, 3, 7),
    (13, 1, 4),
    (13, 2, 6),
    (14, 1, 5),
    (14, 3, 7),
    (15, 2, 7),
    (15, 3, 8),
    (16, 1, 3),
    (16, 2, 5),
    (17, 1, 4),
    (17, 3, 5),
    (18, 2, 6),
    (19, 1, 7),
    (20, 1, 5),
    (20, 3, 7),
    (21, 2, 4),
    (21, 3, 6),
    (22, 1, 6),
    (22, 2, 4),
    (22, 3, 5),
    (23, 1, 3),
    (23, 3, 5),
    (24, 2, 5),
    (24, 3, 6);
