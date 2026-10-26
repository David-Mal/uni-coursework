# Order Application

Simple PHP web application for building orders with dynamic discounts.

## Setup

1. Import the database schema:

```bash
mysql -u root -p < schema.sql
```

2. Update database credentials in `config.php` if needed.

3. Start the PHP built-in server:

```bash
php -S localhost:8000
```

4. Open http://localhost:8000 in your browser.

## Test Users

- alice
- bob
- charlie

## Discount Rules

- **10%** off when the order contains 3 or more products
- **Additional 5%** off when 2 or more products share the same category (prefix before `-` in product name)
- Maximum 15% discount (both rules applied once)

## Diversity Warning

When adding a product, if its category appears in all of the user's last 3 confirmed orders, a warning is shown.
