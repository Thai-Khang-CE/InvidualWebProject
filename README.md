# Seasonal Wardrobe

Seasonal Wardrobe is an individual Web Programming semester project developed for the D2 Fashion / Shoe Store domain. The website presents a seasonal fashion catalog organized into four collections: Spring, Summer, Autumn, and Winter. It allows users to browse products, search dynamically, view item details, check store availability, and use basic account features in a responsive PHP and MySQL web application.

## Domain Assignment

- Student ID modulo 5 result: 1
- Assigned domain: D2 - Fashion / Shoe Store

## Tech Stack

- HTML
- CSS
- JavaScript
- PHP
- MySQL
- Fetch API
- XAMPP
- phpMyAdmin

## Main Features

- Responsive layout for desktop, tablet, and mobile
- Header, navigation bar, main content area, and footer
- Product catalog loaded from MySQL
- Category filtering for Spring, Summer, Autumn, and Winter
- Sorting by name, price, and rating
- Pagination for product listings
- Breadcrumb navigation
- Product detail page
- Store availability by product
- Google Maps links for store locations
- AJAX search using Fetch API
- Search result to product detail navigation flow
- Register, Login, and Logout
- Forgot Password
- Session-based navbar state
- Contact page
- SEO page titles and meta descriptions
- `sitemap.xml` and `robots.txt`
- Product image fallback placeholder for missing product images

## Folder Structure

```text
seasonal-wardrobe/
├── public/
│   ├── index.php
│   ├── css/
│   ├── js/
│   ├── images/
│   ├── sitemap.xml
│   └── robots.txt
├── app/
│   ├── models/
│   ├── views/
│   ├── views/layouts/
│   └── helpers/
├── config/
├── database/
│   ├── schema.sql
│   └── seed.sql
├── storage/
├── report/
└── README.md
```

## Database Structure

The project uses the following main tables:

- `users`: stores registered user account information for authentication
- `categories`: stores the four seasonal collections
- `products`: stores fashion products linked to categories
- `stores`: stores physical store information and Google Maps links
- `product_store_availability`: stores product quantity available at each store

Relationships:

- One category has many products
- Products and stores have a many-to-many relationship through `product_store_availability`
- The `users` table is used for authentication features

## Local Setup Instructions

1. Install and open XAMPP.
2. Start Apache and MySQL.
3. Place the project folder at:
   `D:\xampp\htdocs\seasonal-wardrobe\`
4. Open phpMyAdmin:
   `http://localhost/phpmyadmin`
5. Create a database named:
   `seasonal_wardrobe`
6. Import:
   `database/schema.sql`
7. Import:
   `database/seed.sql`
8. Open the website:
   `http://localhost/seasonal-wardrobe/public/`

## Demo Account

No default account is required. Please register a new user through the Register page.

## Recommended Demo Flow

1. Open the Home page
2. Browse the Products page
3. Filter products by seasonal category
4. Sort products
5. Use pagination
6. Open a Product Detail page
7. Check store availability and Google Maps links
8. Use AJAX Search
9. Register a new account
10. Login
11. Logout
12. Test Forgot Password
13. Open the Contact page
14. Check `sitemap.xml` and `robots.txt`

## SEO Implementation

The project includes basic SEO support through:

- Dynamic page titles
- Meta descriptions
- Semantic HTML structure
- `sitemap.xml`
- `robots.txt`
- Product and category slugs in URLs

## Security Notes

The project includes basic academic-level security practices:

- PDO prepared statements for database queries
- `password_hash()` for storing passwords
- `password_verify()` for login authentication
- `htmlspecialchars()` for HTML output escaping
- Session-based authentication
- Safe JSON response handling for the AJAX search endpoint

## Academic Note

The Forgot Password feature is implemented as a simplified academic reset flow without real email delivery or token-based verification.

## Current Status

The project is ready for final testing and report preparation.
