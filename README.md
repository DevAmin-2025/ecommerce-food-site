# eCommerce Food Website – Laravel 12
This repository contains the customer-facing website for an eCommerce food ordering platform built with Laravel 12. It provides all the functionality for customers to browse products, manage their cart, place orders, and interact with support and account features. It works alongside a separate admin panel that handles backend management.

**Note:** The frontend assets (HTML, CSS, JavaScript, Bootstrap, Alpine.js, etc.) were pre-built and **not developed by me.** This project focuses exclusively on Laravel backend development and integration with the frontend.

## Features
- Customer registration, login, and password reset.
- Browse food items and categories.
- Add to cart and checkout flow.
- Order tracking and history.
- Contact and support form.
- Responsive frontend integrated with backend APIs.
- Jalali calendar support for Persian locale.

## Installation
1. Clone the Repository:
```bash
git clone https://your-repo-url.git
cd ecommerce-food-site-main
```
2. Install Dependencies:
```bash
composer install
```
3. Environment Setup:
```bash
cp .env.example .env
php artisan key:generate
```
4. Database Setup:
Configure your .env with your database credentials, then run:
```bash
php artisan migrate
```
5. Run the Development Server:
```bash
php artisan serve
```

## Licence
This project is licensed under the MIT License.
