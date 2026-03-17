<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4530205480687340"
     crossorigin="anonymous"></script>
    
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

    <!-- CookieConsent CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />

    <!-- jQuery and Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 h-screen font-sans">
    <!-- Navigation -->
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

    <!-- jQuery and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- CookieConsent JS -->
    <script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js"></script>

    <!-- Initialize CookieConsent -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}

        window.addEventListener("load", function(){
            window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "#000"
                    },
                    "button": {
                        "background": "#f1d600"
                    }
                },
                "theme": "classic",
                "position": "bottom",
                "type": "opt-in",
                "content": {
                    "message": "We use cookies to ensure you get the best experience on our website.",
                    "allow": "Accept",
                    "deny": "Decline",
                    "link": "Learn more",
                    "href": "/privacy"  // Update with the path to your privacy policy
                },
                onInitialise: function (status) {
                    var didConsent = this.hasConsented();
                    if (didConsent) {
                        // Enable Google Analytics
                        gtag('js', new Date());
                        gtag('config', 'G-GBE4QW6W7F');
                    }
                },
                onStatusChange: function(status, chosenBefore) {
                    var didConsent = this.hasConsented();
                    if (didConsent) {
                        // Enable Google Analytics
                        gtag('js', new Date());
                        gtag('config', 'G-GBE4QW6W7F');
                    } else {
                        // Disable Google Analytics
                        // Optional: Remove existing GA cookies
                    }
                },
                onRevokeChoice: function() {
                    // Disable Google Analytics
                    // Optional: Remove existing GA cookies
                }
            })
        });
    </script>
</body>

</html>