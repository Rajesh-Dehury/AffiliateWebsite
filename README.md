# DealsDay - Amazon Affiliate Platform

DealsDay is a robust Amazon Affiliate deal-sharing platform built with Laravel and Livewire. It automates the process of fetching product details from Amazon, generating affiliate links, and sharing deals across Telegram and Facebook.

## 🚀 Features

- **Automated Deal Fetching:** Integrates with Amazon Product Advertising API (PA-API) to fetch product titles, prices, images, and features automatically using ASIN.
- **ASIN Scraper:** Easily extract ASINs directly from Amazon product URLs.
- **Admin Dashboard:** A comprehensive panel for managing deals, generating affiliate URLs, and updating site settings.
- **Multi-Platform Sharing:**
  - **Telegram Integration:** Post deals directly to Telegram channels/groups.
  - **Facebook Integration:** Share deals to Facebook Pages via the Graph API.
- **Dynamic Frontend:** Fast, interactive user experience powered by Laravel Livewire 3.
- **Affiliate Link Management:** Automatic generation of tracking-enabled links with seamless redirection.
- **Responsive Design:** Clean and modern UI built with Tailwind CSS, optimized for all devices.

## 🛠 Tech Stack

- **Framework:** [Laravel 10](https://laravel.com/)
- **Frontend:** [Livewire 3](https://livewire.laravel.com/) & [Tailwind CSS](https://tailwindcss.com/)
- **Database:** MySQL
- **Integrations:**
  - Amazon PA-API (Product Advertising API)
  - Telegram Bot SDK
  - Facebook Graph API

## 📦 Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd AffiliateWebsite
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Frontend dependencies:**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure Database:**
   Update your `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run Migrations:**
   ```bash
   php artisan migrate --seed
   ```

7. **Configure Services:**
   Add your API credentials to the `.env` file:
   ```env
   # Telegram
   TELEGRAM_BOT_TOKEN=your_bot_token
   
   # Facebook
   FACEBOOK_PAGE_ACCESS_TOKEN=your_page_access_token
   
   # Amazon (Note: Ensure these are also updated in the GetAmazonProductDetails component if not yet moved to .env)
   AMAZON_ACCESS_KEY=your_access_key
   AMAZON_SECRET_KEY=your_secret_key
   ```

8. **Start the server:**
   ```bash
   php artisan serve
   ```

## 🤖 Telegram Bot
The platform includes a Telegram bot integration to automate deal posting.
- Set your webhook: `https://yourdomain.com/set-webhook`
- Webhook handling is managed via `TelegramBotController`.

## 🛡 Security
If you discover any security-related issues, please contact the maintainer directly instead of using the issue tracker.

## 📄 License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
