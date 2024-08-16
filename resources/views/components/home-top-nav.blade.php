<nav x-data="{ isOpen: false }" class="relative bg-white shadow dark:bg-gray-800">
    <div class="container px-0 lg:px-6 py-3 mx-auto md:flex">
        <div class="flex justify-between items-center px-5">
            <div>
                <a href="{{route('home')}}" class="text-xl font-bold text-gray-800 transition-colors duration-300 transform dark:text-white hover:text-gray-700 dark:hover:text-gray-300">
                    <div class="flex items-center">
                        <img src="{{asset('logo.png')}}" alt="" class="h-14 w-14 rounded-full">
                        <p class="ml-0 mr-3">DealsDay</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</nav>