<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 dark:bg-gray-600 h-screen font-sans overflow-hidden" x-cloak x-data="{menu:false,darkMode: $persist(false)}" :class="{'dark': darkMode === true }">
    {{$slot}}
</body>

</html>`