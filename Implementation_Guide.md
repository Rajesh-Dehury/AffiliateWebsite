# Implementation Guide: DealsDay Enhancements

This guide provides step-by-step instructions for implementing analytics tracking, SEO optimizations, and dashboard improvements for the DealsDay platform.

---

## Phase 1: Database Setup (Analytics & SEO)

### 1. Create Migration
Run the following command to create a new migration for tracking and SEO:
```bash
php artisan make:migration add_analytics_and_seo_to_amazon_deals_table
```

### 2. Update Migration File
Add `slug`, `views_count`, and `clicks_count` columns to the `amazon_deals` table.

```php
Schema::table('amazon_deals', function (Blueprint $table) {
    $table->string('slug')->unique()->nullable()->after('product_title');
    $table->unsignedBigInteger('views_count')->default(0)->after('our_post');
    $table->unsignedBigInteger('clicks_count')->default(0)->after('views_count');
});
```

### 3. Run Migration
```bash
php artisan migrate
```

---

## Phase 2: Implement Tracking Logic

### 1. Track Page Views
Update `app/Livewire/PostDetails.php` to increment `views_count` whenever a deal is viewed.
- Modify the `mount` method to find the deal by slug or ID.
- Call `$deal->increment('views_count')`.

### 2. Track Clicks (Outbound)
Update `app/Http/Controllers/RedirectController.php`.
- In the `redirectToAnotherUrl` method, find the deal by ASIN.
- Call `$deal->increment('clicks_count')` before redirecting the user to Amazon.

---

## Phase 3: SEO Optimization

### 1. Slug Generation
Update `app/Livewire/Admin/GetAmazonProductDetails.php` in the `savePost` method.
- Use `Illuminate\Support\Str::slug($this->product_title)` to generate a unique slug.
- Save this slug in the `amazon_deals` table.

### 2. Update Routes
Modify `routes/web.php` to prioritize slug-based URLs for better search engine indexing.
- Update `Route::get('details/{prod_id}', ...)` to `Route::get('deal/{slug}', ...)`.

### 3. Dynamic Meta Tags & Schema
Update `resources/views/livewire/post-details.blade.php`.
- Add `@section('title')` and `@section('meta_description')`.
- Include a `<script type="application/ld+json">` block for Product and Offer schema markup.

---

## Phase 4: Admin Dashboard Analytics

### 1. Update Dashboard Component
Modify `app/Livewire/Admin/Dashboard.php`.
- Calculate `totalViews` and `totalClicks` using `sum()`.
- Fetch the top 5 performing deals by `clicks_count`.

### 2. Enhance Dashboard View
Modify `resources/views/livewire/admin/dashboard.blade.php`.
- Add statistic cards for Total Views, Total Clicks, and Conversion Rate.
- Add a table displaying the "Top Performing Deals" with their view and click counts.
