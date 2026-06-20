<div align="center">
  <h1>🛒 Vital Mart</h1>
  <p><strong>Multi-category e-commerce platform — Grocery, Furniture, and Halal food delivery built on Laravel.</strong></p>

  <p>
    <a href="https://github.com/SohaibKhaliq/Vital-Mart/stargazers">
      <img src="https://img.shields.io/github/stars/SohaibKhaliq/Vital-Mart?style=flat-square&color=yellow" alt="Stars"/>
    </a>
    <img src="https://img.shields.io/badge/Laravel-10.x-red?style=flat-square&logo=laravel" alt="Laravel"/>
    <img src="https://img.shields.io/badge/PHP-8.x-purple?style=flat-square&logo=php" alt="PHP"/>
    <img src="https://img.shields.io/badge/MySQL-8.0-blue?style=flat-square&logo=mysql" alt="MySQL"/>
  </p>
</div>

## 📖 Overview

Vital Mart is a multi-category e-commerce platform built on Laravel supporting grocery, furniture, and halal food delivery with admin management, delivery personnel tracking, and payment gateway integration (Paytm, Iyzico, Flutterwave).

## Features
- 🛍️ **Multi-category** — Grocery, Furniture, Halal food
- 👨‍💼 **Admin Panel** — Full order and product management
- 🚚 **Delivery Management** — Delivery personnel tracking
- 💳 **Multiple Payments** — Paytm, Iyzico, Flutterwave
- 📱 **PWA Support** — Progressive Web App
- 📄 **PDF Invoices** — DomPDF invoice generation
- 🔒 **reCAPTCHA v3** — Bot protection

## 🚀 Quick Start

```bash
git clone https://github.com/SohaibKhaliq/Vital-Mart.git
cd Vital-Mart
composer install
cp .env.example .env
php artisan key:generate
# configure database in .env
php artisan migrate --seed
php artisan serve
```

## 📄 License

MIT