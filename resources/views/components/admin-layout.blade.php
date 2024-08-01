<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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

<body class="bg-gray-300 h-screen font-sans" x-cloak x-data="{menu:false,darkMode: $persist(true)}" :class="{'dark': darkMode === true }">
    <div class="relative md:grid md:grid-cols-11">
        <x-admin-side-nav />
        <div class="md:col-span-9 w-full z-0 min-h-screen">
            <x-admin-top-nav />
            <div class="bg-white m-3 p-3 rounded-lg">
                <div class="overflow-auto">
                    <div class="flex flex-col">
                        {{$slot}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>