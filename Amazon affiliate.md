# Building an Amazon Affiliate Deal Finder in Laravel

A step-by-step guide to building the Amazon India Deal Finder as a full Laravel web app — with AI-powered deal generation, affiliate link management, and one-click post copying for Telegram/WhatsApp.

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Tech Stack](#2-tech-stack)
3. [Setup & Installation](#3-setup--installation)
4. [Database Design](#4-database-design)
5. [Backend — Models & Migrations](#5-backend--models--migrations)
6. [AI Integration — Claude API Service](#6-ai-integration--claude-api-service)
7. [Controllers](#7-controllers)
8. [Routes](#8-routes)
9. [Frontend — Blade Views](#9-frontend--blade-views)
10. [Livewire Component (Optional Upgrade)](#10-livewire-component-optional-upgrade)
11. [Saving & Managing Deals](#11-saving--managing-deals)
12. [Deployment Tips](#12-deployment-tips)

---

## 1. Project Overview

This app lets you:

- Select filters (category, discount %, price range, audience)
- Call the Claude API to generate 10 realistic Amazon India deals
- Display each deal in your affiliate post format
- Copy individual posts or all 10 at once
- Save deals to a database for later reuse

**Post format output:**

```
[36% Off] HP Laptop 15s 11th Gen Intel Core i3 8GB RAM 512GB SSD

Mrp: ₹46,999 | DEAL: ₹29,990

LINK: https://www.amazon.in/dp/B09DMBXHL2?tag=codewithrd-21
```

---

## 2. Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 11 |
| Frontend | Blade + Alpine.js |
| Reactivity (optional) | Livewire 3 |
| AI | Anthropic Claude API (claude-sonnet) |
| Styling | Tailwind CSS |
| Database | MySQL |
| HTTP Client | Laravel's built-in `Http` facade |

---

## 3. Setup & Installation

### Create a new Laravel project

```bash
composer create-project laravel/laravel amazon-deals
cd amazon-deals
```

### Install dependencies

```bash
# Tailwind CSS
npm install -D tailwindcss @tailwindcss/vite
npx tailwindcss init

# Livewire (optional but recommended)
composer require livewire/livewire

# Alpine.js (via CDN or npm)
npm install alpinejs
```

### Environment variables

Add these to your `.env` file:

```env
ANTHROPIC_API_KEY=sk-ant-your-key-here
ANTHROPIC_MODEL=claude-sonnet-4-20250514
AFFILIATE_TAG=codewithrd-21
```

---

## 4. Database Design

You need two tables — one to save deal sessions and one for individual deals.

```
deal_sessions
─────────────────────────────────────────────
id              bigint PK
category        varchar
min_discount    int
price_range     varchar
audience        varchar
created_at      timestamp

deals
─────────────────────────────────────────────
id              bigint PK
session_id      bigint FK → deal_sessions.id
rank            int
name            varchar(500)
category        varchar
asin            varchar(20)
current_price   int
original_price  int
discount_pct    int
is_prime        boolean
rating          decimal(3,1)
review_count    varchar
emoji           varchar(10)
affiliate_link  varchar(500)
created_at      timestamp
```

---

## 5. Backend — Models & Migrations

### Migrations

```bash
php artisan make:migration create_deal_sessions_table
php artisan make:migration create_deals_table
```

**`create_deal_sessions_table`**

```php
public function up(): void
{
    Schema::create('deal_sessions', function (Blueprint $table) {
        $table->id();
        $table->string('category')->default('all');
        $table->integer('min_discount')->default(50);
        $table->string('price_range')->default('any');
        $table->string('audience')->default('general');
        $table->timestamps();
    });
}
```

**`create_deals_table`**

```php
public function up(): void
{
    Schema::create('deals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('session_id')->constrained('deal_sessions')->cascadeOnDelete();
        $table->integer('rank');
        $table->string('name', 500);
        $table->string('category');
        $table->string('asin', 20);
        $table->integer('current_price');
        $table->integer('original_price');
        $table->integer('discount_pct');
        $table->boolean('is_prime')->default(false);
        $table->decimal('rating', 3, 1)->default(4.0);
        $table->string('review_count')->default('0');
        $table->string('emoji', 10)->nullable();
        $table->string('affiliate_link', 500);
        $table->timestamps();
    });
}
```

```bash
php artisan migrate
```

### Models

```bash
php artisan make:model DealSession
php artisan make:model Deal
```

**`app/Models/DealSession.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DealSession extends Model
{
    protected $fillable = [
        'category', 'min_discount', 'price_range', 'audience'
    ];

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class, 'session_id');
    }
}
```

**`app/Models/Deal.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deal extends Model
{
    protected $fillable = [
        'session_id', 'rank', 'name', 'category', 'asin',
        'current_price', 'original_price', 'discount_pct',
        'is_prime', 'rating', 'review_count', 'emoji', 'affiliate_link'
    ];

    protected $casts = [
        'is_prime' => 'boolean',
        'rating'   => 'float',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(DealSession::class, 'session_id');
    }

    public function getPostTextAttribute(): string
    {
        $mrp  = '₹' . number_format($this->original_price, 0, '.', ',');
        $deal = '₹' . number_format($this->current_price,  0, '.', ',');

        return "[{$this->discount_pct}% Off] {$this->name}\n\nMrp: {$mrp} | DEAL: {$deal}\n\nLINK: {$this->affiliate_link}";
    }
}
```

---

## 6. AI Integration — Claude API Service

Create a dedicated service class to keep API logic out of your controllers.

```bash
php artisan make:class Services/ClaudeService
```

**`app/Services/ClaudeService.php`**

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaudeService
{
    private string $apiKey;
    private string $model;
    private string $affiliateTag;

    public function __construct()
    {
        $this->apiKey       = config('services.anthropic.key');
        $this->model        = config('services.anthropic.model');
        $this->affiliateTag = config('services.anthropic.affiliate_tag');
    }

    public function fetchDeals(array $filters): array
    {
        $prompt = $this->buildPrompt($filters);

        $response = Http::withHeaders([
            'x-api-key'         => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type'      => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model'      => $this->model,
            'max_tokens' => 2000,
            'messages'   => [
                ['role' => 'user', 'content' => $prompt]
            ],
        ]);

        if ($response->failed()) {
            Log::error('Claude API error', ['response' => $response->body()]);
            throw new \Exception('Failed to fetch deals from AI. Please try again.');
        }

        $text = collect($response->json('content'))
            ->where('type', 'text')
            ->pluck('text')
            ->implode('');

        // Strip markdown code fences if present
        $text = preg_replace('/^```json?\n?/m', '', $text);
        $text = preg_replace('/```$/m', '', $text);
        $text = trim($text);

        $deals = json_decode($text, true);

        if (!is_array($deals)) {
            throw new \Exception('Invalid response format from AI.');
        }

        // Append affiliate tag to each deal
        return array_map(function ($deal) {
            $deal['affiliate_link'] = "https://www.amazon.in/dp/{$deal['asin']}?tag={$this->affiliateTag}";
            return $deal;
        }, $deals);
    }

    private function buildPrompt(array $filters): string
    {
        $category    = $filters['category'] ?? 'all';
        $minDiscount = $filters['min_discount'] ?? 50;
        $priceRange  = $filters['price_range'] ?? 'any';
        $audience    = $filters['audience'] ?? 'general buyers in India';

        $categoryLabel = $category === 'all'
            ? 'best mix of Electronics, Smartphones, Laptops, Kitchen, Home, Fashion'
            : $category;

        return <<<PROMPT
You are an expert Amazon India affiliate marketer. Generate exactly 10 realistic, highly popular Amazon India product deals.

Requirements:
- Category: {$categoryLabel}
- Minimum discount: {$minDiscount}% off MRP
- Price range: ₹{$priceRange}
- Target audience: {$audience}
- Use REAL brand names and popular product lines sold in India (Samsung, boAt, HP, Mi, Philips, Prestige, etc.)
- ASIN must be 10 characters starting with B0 (realistic format)
- currentPrice must be mathematically correct: originalPrice minus the discount percentage
- Rating between 4.0 and 4.8, review counts in thousands

Return ONLY a raw JSON array with exactly 10 objects. No markdown, no explanation.

JSON structure per item:
{"rank":1,"name":"Full brand + model + key specs","category":"Laptops","asin":"B09DMBXHL2","currentPrice":29990,"originalPrice":46999,"discountPercent":36,"isPrime":true,"rating":4.3,"reviewCount":"12,450","emoji":"💻"}
PROMPT;
    }
}
```

### Register in config

Add to `config/services.php`:

```php
'anthropic' => [
    'key'           => env('ANTHROPIC_API_KEY'),
    'model'         => env('ANTHROPIC_MODEL', 'claude-sonnet-4-20250514'),
    'affiliate_tag' => env('AFFILIATE_TAG', 'codewithrd-21'),
],
```

---

## 7. Controllers

```bash
php artisan make:controller DealController
```

**`app/Http/Controllers/DealController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealSession;
use App\Services\ClaudeService;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function __construct(private ClaudeService $claude) {}

    // Show the main deal finder page
    public function index()
    {
        $recent = DealSession::with('deals')
            ->latest()
            ->take(5)
            ->get();

        return view('deals.index', compact('recent'));
    }

    // Fetch 10 deals from Claude and save to DB
    public function fetch(Request $request)
    {
        $validated = $request->validate([
            'category'     => 'required|string',
            'min_discount' => 'required|integer|min:10|max:90',
            'price_range'  => 'required|string',
            'audience'     => 'required|string',
        ]);

        try {
            $rawDeals = $this->claude->fetchDeals($validated);
        } catch (\Exception $e) {
            return back()->withErrors(['ai' => $e->getMessage()]);
        }

        // Save session
        $session = DealSession::create($validated);

        // Save each deal
        foreach ($rawDeals as $d) {
            Deal::create([
                'session_id'     => $session->id,
                'rank'           => $d['rank'],
                'name'           => $d['name'],
                'category'       => $d['category'],
                'asin'           => $d['asin'],
                'current_price'  => $d['currentPrice'],
                'original_price' => $d['originalPrice'],
                'discount_pct'   => $d['discountPercent'],
                'is_prime'       => $d['isPrime'] ?? false,
                'rating'         => $d['rating'],
                'review_count'   => $d['reviewCount'],
                'emoji'          => $d['emoji'] ?? '📦',
                'affiliate_link' => $d['affiliate_link'],
            ]);
        }

        return redirect()->route('deals.session', $session->id);
    }

    // Show a saved session's deals
    public function session(DealSession $session)
    {
        $deals = $session->deals()->orderBy('rank')->get();
        return view('deals.session', compact('session', 'deals'));
    }

    // Return post text for a single deal (AJAX)
    public function postText(Deal $deal)
    {
        return response()->json(['text' => $deal->post_text]);
    }

    // Return all post texts for a session (AJAX)
    public function allPostText(DealSession $session)
    {
        $separator = "\n\n──────────────────\n\n";
        $text = $session->deals()
            ->orderBy('rank')
            ->get()
            ->map(fn($d) => $d->post_text)
            ->implode($separator);

        return response()->json(['text' => $text]);
    }
}
```

---

## 8. Routes

**`routes/web.php`**

```php
<?php

use App\Http\Controllers\DealController;
use Illuminate\Support\Facades\Route;

Route::get('/',                              [DealController::class, 'index'])->name('deals.index');
Route::post('/deals/fetch',                  [DealController::class, 'fetch'])->name('deals.fetch');
Route::get('/deals/session/{session}',       [DealController::class, 'session'])->name('deals.session');
Route::get('/deals/{deal}/post-text',        [DealController::class, 'postText'])->name('deals.postText');
Route::get('/deals/session/{session}/all',   [DealController::class, 'allPostText'])->name('deals.allPostText');
```

---

## 9. Frontend — Blade Views

### Layout

**`resources/views/layouts/app.blade.php`**

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amazon Deal Finder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <header class="bg-[#232f3e] px-6 py-4 flex items-center justify-between">
        <div>
            <h1 class="text-[#FF9900] text-xl font-bold">🛒 Amazon Deal Finder</h1>
            <p class="text-gray-400 text-xs">Affiliate posts for Telegram · WhatsApp · Instagram</p>
        </div>
        <span class="bg-[#FF9900] text-[#232f3e] text-xs font-bold px-3 py-1 rounded-full">
            {{ config('services.anthropic.affiliate_tag') }}
        </span>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>
</html>
```

### Index Page (Filter Form)

**`resources/views/deals/index.blade.php`**

```html
@extends('layouts.app')

@section('content')

{{-- Filter Form --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <h2 class="text-base font-semibold mb-4">Find Today's Best Deals</h2>

    @if($errors->has('ai'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
            {{ $errors->first('ai') }}
        </div>
    @endif

    <form action="{{ route('deals.fetch') }}" method="POST">
        @csrf
        <div class="flex flex-wrap gap-4 items-end">

            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Category</label>
                <select name="category" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="all">All Categories</option>
                    <option value="electronics">Electronics</option>
                    <option value="smartphones">Smartphones</option>
                    <option value="laptops">Laptops</option>
                    <option value="kitchen">Kitchen Appliances</option>
                    <option value="fashion">Fashion</option>
                    <option value="home">Home & Furniture</option>
                    <option value="beauty">Beauty</option>
                    <option value="sports">Sports & Fitness</option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Min Discount</label>
                <select name="min_discount" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="30">30%+</option>
                    <option value="40">40%+</option>
                    <option value="50" selected>50%+</option>
                    <option value="60">60%+</option>
                    <option value="70">70%+</option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Price Range</label>
                <select name="price_range" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="any">Any Price</option>
                    <option value="under 1000">Under ₹1,000</option>
                    <option value="1000 to 5000">₹1,000 – ₹5,000</option>
                    <option value="5000 to 15000" selected>₹5,000 – ₹15,000</option>
                    <option value="15000 to 50000">₹15,000 – ₹50,000</option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Audience</label>
                <select name="audience" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="general buyers in India">General</option>
                    <option value="students">Students</option>
                    <option value="homemakers">Homemakers</option>
                    <option value="working professionals">Professionals</option>
                    <option value="gamers">Gamers</option>
                </select>
            </div>

            <button type="submit"
                class="bg-[#FF9900] text-[#232f3e] font-bold px-5 py-2 rounded-lg text-sm hover:bg-amber-500 transition">
                ⚡ Fetch 10 Deals
            </button>
        </div>
    </form>
</div>

{{-- Recent Sessions --}}
@if($recent->count())
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h2 class="text-base font-semibold mb-4">Recent Deal Sessions</h2>
    <div class="divide-y divide-gray-100">
        @foreach($recent as $session)
        <a href="{{ route('deals.session', $session) }}"
           class="flex items-center justify-between py-3 hover:bg-gray-50 px-2 rounded-lg transition">
            <div>
                <span class="font-medium text-sm">{{ ucfirst($session->category) }}</span>
                <span class="text-gray-400 text-xs ml-2">{{ $session->min_discount }}%+ off · {{ $session->price_range }}</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-400">{{ $session->deals->count() }} deals</span>
                <span class="text-xs text-gray-400">{{ $session->created_at->diffForHumans() }}</span>
                <span class="text-[#FF9900] text-sm">→</span>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

@endsection
```

### Session / Results Page

**`resources/views/deals/session.blade.php`**

```html
@extends('layouts.app')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-4 gap-3 mb-6">
    <div class="bg-white border border-gray-200 rounded-xl p-4">
        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Deals</p>
        <p class="text-2xl font-bold text-amber-600">{{ $deals->count() }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-4">
        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Avg Discount</p>
        <p class="text-2xl font-bold text-red-600">{{ round($deals->avg('discount_pct')) }}%</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-4">
        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Max Savings</p>
        <p class="text-2xl font-bold text-green-700">
            ₹{{ number_format($deals->max(fn($d) => $d->original_price - $d->current_price)) }}
        </p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-4">
        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Prime Deals</p>
        <p class="text-2xl font-bold text-blue-700">{{ $deals->where('is_prime', true)->count() }}</p>
    </div>
</div>

{{-- Copy All Button --}}
<div class="flex justify-between items-center mb-4">
    <a href="{{ route('deals.index') }}" class="text-sm text-gray-500 hover:text-gray-800">← New Search</a>
    <button onclick="copyAll()" class="bg-[#232f3e] text-[#FF9900] font-bold px-5 py-2 rounded-lg text-sm hover:bg-gray-900 transition">
        📋 Copy All 10 Posts
    </button>
</div>

{{-- Deal Cards --}}
<div class="flex flex-col gap-3">
@foreach($deals as $deal)
<div class="bg-white rounded-xl border {{ $deal->rank <= 3 ? 'border-l-4 border-l-[#FF9900] border-gray-200' : 'border-gray-200' }} p-5"
     x-data="{ copied: false }">
    <div class="flex gap-4">
        <div class="text-2xl font-black {{ $deal->rank <= 3 ? 'text-[#FF9900]' : 'text-gray-300' }} min-w-[36px]">
            #{{ $deal->rank }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs text-gray-400 mb-2">
                {{ $deal->emoji }} {{ $deal->category }}
                @if($deal->is_prime) &nbsp;·&nbsp; 🔵 Prime @endif
                &nbsp;·&nbsp; ⭐ {{ $deal->rating }} ({{ $deal->review_count }} reviews)
            </p>

            {{-- Post Preview --}}
            <pre class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-4 text-sm leading-relaxed whitespace-pre-wrap break-all font-mono text-gray-800" id="post-{{ $deal->id }}">{{ $deal->post_text }}</pre>

            {{-- Actions --}}
            <div class="flex gap-3 mt-3">
                <button onclick="copyPost({{ $deal->id }})"
                    class="border border-[#FF9900] text-amber-700 text-sm font-semibold px-4 py-2 rounded-lg hover:bg-amber-50 transition"
                    id="btn-{{ $deal->id }}">
                    📋 Copy Post
                </button>
                <a href="{{ $deal->affiliate_link }}" target="_blank"
                   class="bg-[#FF9900] text-[#232f3e] text-sm font-bold px-4 py-2 rounded-lg hover:bg-amber-500 transition">
                    View on Amazon ↗
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach
</div>

<script>
function copyPost(id) {
    const text = document.getElementById('post-' + id).innerText;
    const btn  = document.getElementById('btn-'  + id);
    navigator.clipboard.writeText(text).then(() => {
        btn.textContent = '✅ Copied!';
        btn.classList.add('border-green-500', 'text-green-700', 'bg-green-50');
        setTimeout(() => {
            btn.textContent = '📋 Copy Post';
            btn.classList.remove('border-green-500', 'text-green-700', 'bg-green-50');
        }, 2200);
    });
}

async function copyAll() {
    const res  = await fetch('{{ route("deals.allPostText", $session) }}');
    const data = await res.json();
    await navigator.clipboard.writeText(data.text);
    alert('✅ All 10 posts copied to clipboard!');
}
</script>

@endsection
```

---

## 10. Livewire Component (Optional Upgrade)

If you want the fetch to happen without a full page reload, replace the form with a Livewire component.

```bash
php artisan make:livewire DealFetcher
```

**`app/Livewire/DealFetcher.php`**

```php
<?php

namespace App\Livewire;

use App\Services\ClaudeService;
use Livewire\Component;

class DealFetcher extends Component
{
    public string $category    = 'all';
    public int    $minDiscount = 50;
    public string $priceRange  = '5000 to 15000';
    public string $audience    = 'general buyers in India';

    public array  $deals  = [];
    public bool   $loading = false;
    public string $error  = '';

    public function fetch(): void
    {
        $this->loading = true;
        $this->error   = '';
        $this->deals   = [];

        try {
            $service = app(ClaudeService::class);
            $this->deals = $service->fetchDeals([
                'category'     => $this->category,
                'min_discount' => $this->minDiscount,
                'price_range'  => $this->priceRange,
                'audience'     => $this->audience,
            ]);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.deal-fetcher');
    }
}
```

Use in Blade with:

```html
@livewire('deal-fetcher')
```

---

## 11. Saving & Managing Deals

Every time you fetch, a `DealSession` + 10 `Deal` records are saved to the database automatically. This means you can:

- Build a saved deals history page
- Re-copy old posts without re-fetching
- Track which sessions performed best
- Export to CSV for bulk scheduling

**Quick export to CSV example:**

```php
public function exportCsv(DealSession $session)
{
    $headers = [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => 'attachment; filename=deals.csv',
    ];

    $callback = function () use ($session) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Rank', 'Name', 'ASIN', 'MRP', 'Deal Price', 'Discount %', 'Link']);
        foreach ($session->deals()->orderBy('rank')->get() as $deal) {
            fputcsv($handle, [
                $deal->rank, $deal->name, $deal->asin,
                $deal->original_price, $deal->current_price,
                $deal->discount_pct, $deal->affiliate_link,
            ]);
        }
        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}
```

---

## 12. Deployment Tips

### Use queue for AI calls (recommended for production)

Long API calls can time out on shared hosting. Move the Claude fetch to a queued job:

```bash
php artisan make:job FetchDealsJob
```

```php
// Inside FetchDealsJob::handle()
$deals = app(ClaudeService::class)->fetchDeals($this->filters);
// save to DB, then notify user via session flash or broadcast
```

### Rate limiting

Add a rate limiter to avoid Claude API abuse:

```php
// In routes/web.php
Route::post('/deals/fetch', [DealController::class, 'fetch'])
    ->name('deals.fetch')
    ->middleware('throttle:10,1'); // 10 requests per minute
```

### Caching (save API costs)

Cache identical filter combinations for 30 minutes:

```php
// In ClaudeService::fetchDeals()
$cacheKey = 'deals_' . md5(json_encode($filters));

return cache()->remember($cacheKey, now()->addMinutes(30), function () use ($filters) {
    // ... Claude API call
});
```

### Production checklist

- Set `APP_ENV=production` and `APP_DEBUG=false`
- Store `ANTHROPIC_API_KEY` securely in your hosting environment variables
- Run `php artisan config:cache` and `php artisan route:cache`
- Use HTTPS (affiliate links require it on some platforms)

---

## Summary

| Step | What you build |
|---|---|
| ClaudeService | Calls Claude API, builds prompt, returns 10 deals |
| DealController | Handles form, saves to DB, returns views |
| DealSession + Deal | Database models to persist all fetched deals |
| Blade views | Filter form + deal cards with post preview |
| Copy buttons | JS clipboard API for single post or all 10 |
| Livewire (optional) | Real-time fetch without page reload |
| Queue + Cache | Production-grade performance and cost control |

With this setup you have a full affiliate content engine — fetch deals on demand, copy formatted posts in one click, and build a searchable history of every deal batch you've ever generated.