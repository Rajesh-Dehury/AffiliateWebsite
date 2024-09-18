<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DealsDay24</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('site.webmanifest')}}">
    @vite('resources/css/app.css')

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

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
    <div class="fixed bottom-4 right-4 flex flex-col space-y-3">
        <!-- WhatsApp Icon -->
        <a href="https://wa.me/+916371391755" target="_blank" class="bg-green-500 p-2.5 rounded-full shadow-lg hover:bg-green-600 hover:drop-shadow-lg hover:shadow-green-300 transition duration-300">
            <img src="{{asset('whatsapp-svgrepo-com.svg')}}" alt="" class="h-10 w-10 transition duration-300">
        </a>

        <!-- Telegram Icon -->
        <a href="https://t.me/DealsDay_24" target="_blank" class="bg-blue-500 p-2.5 rounded-full shadow-lg hover:bg-blue-600 hover:drop-shadow-lg hover:shadow-blue-300 transition duration-300">
            <img src="{{asset('telegram-svgrepo-com.svg')}}" alt="" class="h-10 w-10">
        </a>
    </div>

    <x-home-footer />
</body>

</html>