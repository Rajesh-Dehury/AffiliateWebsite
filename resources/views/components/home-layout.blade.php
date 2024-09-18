<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title Tag for SEO -->
    <title>DealsDay24 - Best Deals and Offers on E-Commerce Sites in India</title>

    <!-- Meta Description for SEO -->
    <meta name="description" content="Find the latest and best deals, offers, and discounts on leading e-commerce sites in India like Amazon, Flipkart, and more. Save big on your favorite products.">

    <!-- Meta Keywords for SEO -->
    <meta name="keywords" content="Deals, Offers, Discounts, Amazon, Flipkart, E-commerce, Best Deals India, Online Shopping, DealsDay24">

    <!-- Canonical URL to avoid duplicate content issues -->
    <link rel="canonical" href="https://www.dealsday24.com/">

    <!-- Open Graph (OG) Tags for Social Media Sharing -->
    <meta property="og:title" content="DealsDay24 - Best Deals and Offers on E-Commerce Sites in India">
    <meta property="og:description" content="Discover the best deals and offers from top Indian e-commerce sites. Save money on your online shopping with DealsDay24.">
    <meta property="og:url" content="https://www.dealsday24.com/">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{asset('logo.png')}}">

    <!-- Twitter Card Tags for Twitter Sharing -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="DealsDay24 - Best Deals and Offers on E-Commerce Sites in India">
    <meta name="twitter:description" content="Find the latest and best deals from India's top online stores. Save big on electronics, fashion, and more.">
    <meta name="twitter:image" content="https://www.dealsday24.com/assets/twitter-image.jpg">

    <!-- Apple Touch Icon and Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('site.webmanifest')}}">

    <!-- Vite CSS -->
    @vite('resources/css/app.css')

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- jQuery and Select2 JS/CSS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="bg-gray-100 h-screen font-sans">
    <x-home-top-nav />
    <div class="min-h-screen">
        <div class="pt-20 md:pt-0">
            {{$slot}}
        </div>
    </div>

    <!-- WhatsApp and Telegram Floating Icons -->
    <div class="fixed bottom-4 right-4 flex flex-col space-y-3">
        <!-- WhatsApp Icon -->
        <a href="https://wa.me/+916371391755" target="_blank" class="bg-green-500 p-2.5 rounded-full shadow-lg hover:bg-green-600 hover:drop-shadow-lg hover:shadow-green-300 transition duration-300">
            <img src="{{asset('whatsapp-svgrepo-com.svg')}}" alt="WhatsApp" class="h-10 w-10 transition duration-300">
        </a>

        <!-- Telegram Icon -->
        <a href="https://t.me/DealsDay_24" target="_blank" class="bg-blue-500 p-2.5 rounded-full shadow-lg hover:bg-blue-600 hover:drop-shadow-lg hover:shadow-blue-300 transition duration-300">
            <img src="{{asset('telegram-svgrepo-com.svg')}}" alt="Telegram" class="h-10 w-10">
        </a>
    </div>

    <!-- Footer Component -->
    <x-home-footer />
</body>

</html>