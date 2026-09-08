# Vanilla PHP E-Commerce

## Overview

This project is an e-commerce web application built entirely with **vanilla PHP**, without frameworks or Composer packages.

It was developed as a hands-on project to build a complete e-commerce application from scratch and gain a deeper understanding of how PHP applications are structured without relying on a framework.

## What This Project Demonstrates

- Object-oriented PHP development
- MVC-like application architecture
- Separation of business logic and data access
- Manual dependency injection
- MySQL database interaction using PDO
- User authentication and session management
- E-commerce workflows such as cart, checkout and order management
- Form validation and error handling
- Administrative functionality
- Reusable templates and components

## Features

### Customer

- Product listing and product details
- Shopping cart management
- Checkout
- User account
- Order history

### Administration

- Product management
- User management
- Order management


## Architecture

The application follows a **service-oriented, MVC-like architecture** designed to separate presentation, application logic, and data access.

### Application Flow

```text
Public Page
    ↓
Controller
    ↓
Service
    ↓
Model
    ↓
Database
```

### Components

**Public Pages**

Serve as entry points for application requests, for example:

* `products.php`
* `cart.php`
* `checkout.php`

**Controllers**

Handle incoming requests and coordinate the application flow for a specific feature.

**Services**

Contain the business logic and coordinate operations between controllers and models.

**Models**

Handle database queries and data access.

**Templates**

Contain reusable presentation components such as headers, footers, and pagination.

The application uses **manual dependency injection** without relying on a framework or dependency injection container.

## Technologies

* PHP 8.2+
* MySQL / MariaDB
* PDO
* HTML5
* CSS3
* Bootstrap

The project intentionally uses **no PHP framework and no Composer packages**.

## Setup

### Requirements

* PHP 8.2+
* MySQL / MariaDB
* Web browser

### 1. Clone the Repository

```bash
git clone https://github.com/h-s-ner/vanilla-php-ecommerce.git
cd vanilla-php-ecommerce
```

### 2. Configure the Database

Copy the example database configuration:

```bash
cp config/db_config.example.php config/db_config.php
```

Then update the database credentials in:

```text
config/db_config.php
```

The `db_config.php` file is excluded from version control.

### 3. Create and Initialize the Database

Create the database specified in `db_config.php` and import the database schema:

```bash
mysql -u root -p shop < database/schema.sql
```

Then import the demo data:

```bash
mysql -u root -p shop < database/seed.sql
```

Replace `shop` with your database name if necessary.

### 4. Start the Application

Start PHP's built-in development server:

```bash
php -S localhost:8080 -t public
```

The application will then be available at:

```text
http://localhost:8080
```

The `-t public` option sets the `public` directory as the document root.

The application requires PHP's PDO MySQL extension to connect to the MySQL/MariaDB database.

Make sure that both PDO and the PDO MySQL driver are installed and enabled.

Note: The PHP development server itself can start without pdo_mysql. However, the application will fail when it attempts to establish a MySQL/MariaDB connection if the PDO MySQL driver is missing or disabled.

## Future Improvements

Possible future improvements include:

* Email notifications after order confirmation
* PDF invoice generation
* Discount and voucher codes
* Additional security hardening
* Automated tests
* Improved frontend design

## License

See the [LICENSE](LICENSE) file for license terms.
