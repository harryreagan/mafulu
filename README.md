# Mafullu - Premium Digital Marketplace

Mafullu is a streamlined, enterprise-grade digital marketplace designed for secure and efficient delivery of digital goods. Built with Laravel, it focuses on trust, simplicity, and a premium user experience, specifically tailored for high-value digital assets like templates, courses, and ebooks.

## 🚀 Key Features

- **Crypto-Integrated Checkout**: Native support for BTC and USDT payments with real-time rate fetching.
- **Secure File Delivery**: Single-use, private download links with 48-hour expiration logic.
- **Manual Verification Workflow**: Admin review process for payments before product delivery to ensure transaction integrity.
- **Smart Coupon System**: Flexible discount management for promotional campaigns.
- **Admin Dashboard**: Comprehensive management of products, orders, coupons, and sales analytics.
- **Trust-First UX**: Integrated testimonials and trust indicators to boost conversion rates.

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates, Tailwind CSS
- **Database**: MySQL/MariaDB
- **Payments**: BTC (Bitcoin), USDT (Tether)

## 📦 Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/harryreagan/mafulu.git
   cd mafulu
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**:
   Update your `.env` file with your database credentials and crypto wallet addresses:
   ```env
   DB_DATABASE=mafullu
   DB_USERNAME=root
   DB_PASSWORD=

   BTC_ADDRESS=your_btc_address
   USDT_ADDRESS=your_usdt_address
   ```

5. **Run Migrations & Seeders**:
   ```bash
   php artisan migrate
   ```

6. **Storage Link**:
   ```bash
   php artisan storage:link
   ```

## 🔐 Administration

The admin panel provides full control over the marketplace. Configure your admin credentials in the `.env` file:
```env
ADMIN_EMAIL=admin@mafullu.com
ADMIN_PASSWORD=your_secure_password
```

## 📄 License

The Mafullu platform is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
Developed by [Harry Reagan](https://github.com/harryreagan)
