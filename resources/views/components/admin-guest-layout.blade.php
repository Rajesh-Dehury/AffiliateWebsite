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
</head>

<body class="bg-gray-100 dark:bg-gray-600 h-screen font-sans overflow-hidden" x-cloak x-data="{menu:false,darkMode: $persist(false)}" :class="{'dark': darkMode === true }">
    {{$slot}}
</body>

</html>`