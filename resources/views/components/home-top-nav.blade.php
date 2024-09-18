<nav x-data="{ isOpen: false, searchBox: false }" class="md:relative fixed bg-white shadow dark:bg-gray-800 w-full z-10">
    <div class="container px-0 lg:px-6 py-3 mx-auto md:flex">
        <div class="flex justify-between items-center md:px-5 pr-5">
            <div class="group">
                <a href="{{route('home')}}" class="text-xl font-bold text-gray-800 transition-colors duration-300 transform dark:text-white hover:text-gray-700 dark:hover:text-gray-300">
                    <div class="flex items-center">
                        <img src="{{asset('logo.png')}}" alt="" class="h-14 w-14 rounded-full group-hover:scale-110 transition">
                        <p class="ml-0 mr-3">DealsDay</p>
                    </div>
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="flex lg:hidden">
                <button x-cloak @click="isOpen = !isOpen" type="button" class="text-gray-500 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-400 focus:outline-none focus:text-gray-600 dark:focus:text-gray-400" aria-label="toggle menu">
                    <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
                    </svg>

                    <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
        <div x-cloak :class="[isOpen ? 'translate-x-0 opacity-100 ' : 'opacity-0 -translate-x-full']" class="inset-x-0 z-20 w-full px-6 py-4 transition-all duration-300 ease-in-out bg-white h-screen md:h-auto fixed dark:bg-gray-800 md:mt-0 md:p-0 md:top-0 md:relative md:opacity-100 md:translate-x-0 md:flex md:items-center md:justify-end">
            <div class="flex flex-col px-0 -mx-4 md:flex-row md:mx-0 md:py-0">
                <a href="{{route('home')}}" class="px-2.5 py-2 text-gray-700 transition-colors duration-300 transform rounded-lg dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 md:mx-2">Home</a>
            </div>
            <div class="flex flex-col px-0 -mx-4 md:flex-row md:mx-0 md:py-0">
                <a href="{{route('about')}}" class="px-2.5 py-2 text-gray-700 transition-colors duration-300 transform rounded-lg dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 md:mx-2">About</a>
            </div>
            <div class="flex flex-col px-0 -mx-4 md:flex-row md:mx-0 md:py-0">
                <a href="{{route('contact')}}" class="px-2.5 py-2 text-gray-700 transition-colors duration-300 transform rounded-lg dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 md:mx-2">Contact</a>
            </div>
            <div class="flex flex-col px-0 -mx-4 md:flex-row md:mx-0 md:py-0">
                <a href="{{route('disclaimer')}}" class="px-2.5 py-2 text-gray-700 transition-colors duration-300 transform rounded-lg dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 md:mx-2">Disclaimer</a>
            </div>
            <div class="flex flex-col px-0 -mx-4 md:flex-row md:mx-0 md:py-0">
                <a href="{{route('privacy')}}" class="px-2.5 py-2 text-gray-700 transition-colors duration-300 transform rounded-lg dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 md:mx-2">Privacy</a>
            </div>
        </div>
    </div>
</nav>