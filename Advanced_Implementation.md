# Advanced Implementation: DealsDay Enhancements Phase 2

This guide covers the next generation of features for DealsDay: Advanced SEO, Community Building, and Price Tracking.

---

## 🔍 Phase 1: Advanced SEO (Sitemap)

### 1. Install Sitemap Package
```bash
composer require spatie/laravel-sitemap
```

### 2. Generate Sitemap Command
Create a console command to generate the sitemap daily.
```bash
php artisan make:command GenerateSitemap
```

### 3. Configure Robots.txt
Add the sitemap URL to `public/robots.txt`.

---

## ✨ Phase 2: Price History Tracker

### 1. Create PriceHistory Migration & Model
Track price changes for every product over time.
```bash
php artisan make:model PriceHistory -m
```
Columns: `amazon_deal_id`, `price`, `timestamp`.

### 2. Update Scraper Logic
Every time `GetAmazonProductDetails` fetches a product, log the price if it has changed.

### 3. Display Chart
Add a simple Chart.js line graph to the `post-details.blade.php` page showing the price trend.

---

## 📣 Phase 3: Community & Visibility

### 1. Email Subscription
- Add a "Join our Newsletter" form to the footer.
- Use a background job to send a weekly summary of top deals.

### 2. Automated Social Posting (Twitter)
- Integrate a Twitter API client to auto-post successful "Saves" from the Admin panel.
