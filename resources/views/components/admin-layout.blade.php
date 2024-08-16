<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DealsDay24 Admin</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('site.webmanifest')}}">
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="//cdn.ckeditor.com/4.22.0/standard/ckeditor.js"></script>
</head>

<body class="bg-gray-300 h-screen font-sans" x-cloak x-data="{menu:false,darkMode: $persist(true)}" :class="{'dark': darkMode === true }">
    <div class="relative md:grid md:grid-cols-11">
        <x-admin-side-nav />
        <div class="md:col-span-9 w-full z-0 min-h-screen">
            <x-admin-top-nav />
            {{$slot}}
        </div>
    </div>

    @stack('js')
</body>

</html>