<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4530205480687340"
     crossorigin="anonymous"></script>
    
    <!-- Title Tag for SEO -->
    @stack('seo')
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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite CSS -->
    @vite('resources/css/app.css')

    <style>
        [x-cloak] { display: none !important; }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3External%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            background-blend-mode: overlay;
            background-attachment: fixed;
            opacity: 0.98;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .deal-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .deal-shadow:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: translateY(-4px);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-glow:hover {
            box-shadow: 0 0 15px rgba(37, 99, 235, 0.4);
        }
    </style>

    <!-- CookieConsent CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />

    <!-- jQuery and Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="antialiased text-gray-900">
    <!-- Navigation -->
    <x-home-top-nav />

    <main class="min-h-screen pt-24 pb-12">
        <div class="container mx-auto px-4 lg:px-8">
            {{$slot}}
        </div>
    </main>

    <!-- Floating Actions -->
    <div class="fixed bottom-6 right-6 flex flex-col space-y-4 z-50">
        <!-- Telegram -->
        <a href="https://t.me/DealsDay_24" target="_blank" 
           class="bg-blue-500 p-3 rounded-full shadow-lg hover:bg-blue-600 transition-all duration-300 transform hover:scale-110 btn-glow">
            <img src="{{asset('telegram-svgrepo-com.svg')}}" alt="Telegram" class="h-8 w-8">
        </a>
        <!-- WhatsApp -->
        <a href="https://wa.me/+916371391755" target="_blank" 
           class="bg-green-500 p-3 rounded-full shadow-lg hover:bg-green-600 transition-all duration-300 transform hover:scale-110 btn-glow">
            <img src="{{asset('whatsapp-svgrepo-com.svg')}}" alt="WhatsApp" class="h-8 w-8">
        </a>
    </div>

    <!-- Footer Component -->
    <x-home-footer />

    <!-- jQuery and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    @stack('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- CookieConsent JS -->
    <script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js"></script>

    <script>
        window.addEventListener("load", function(){
            window.cookieconsent.initialise({
                "palette": {
                    "popup": { "background": "#0f172a", "text": "#f8fafc" },
                    "button": { "background": "#2563eb", "text": "#ffffff" }
                },
                "theme": "classic",
                "position": "bottom-right",
                "content": {
                    "message": "We use cookies to enhance your deal-hunting experience.",
                    "dismiss": "Got it!",
                    "link": "Privacy Policy",
                    "href": "/privacy"
                }
            })
        });
    </script>
</body>

</html>
