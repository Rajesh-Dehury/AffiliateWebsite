<nav x-data="{ isOpen: false }" class="fixed top-0 left-0 right-0 z-[100] glass-card border-b border-gray-200/50">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <a href="{{route('home')}}" class="flex items-center group transition-transform duration-300 hover:scale-105">
                <div class="relative">
                    <img src="{{asset('logo.png')}}" alt="DealsDay" class="h-12 w-12 rounded-2xl shadow-lg group-hover:rotate-6 transition-all duration-500">
                    <div class="absolute -bottom-1 -right-1 bg-blue-600 h-4 w-4 rounded-full border-2 border-white"></div>
                </div>
                <div class="ml-3">
                    <span class="text-xl font-extrabold tracking-tight gradient-text">DealsDay</span>
                    <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Best Deals 24/7</span>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                <a href="{{route('home')}}" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 rounded-xl hover:bg-blue-50 transition-all duration-200 {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-600' : '' }}">Home</a>
                <a href="{{route('about')}}" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 rounded-xl hover:bg-blue-50 transition-all duration-200 {{ request()->routeIs('about') ? 'bg-blue-50 text-blue-600' : '' }}">About</a>
                <a href="{{route('contact')}}" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 rounded-xl hover:bg-blue-50 transition-all duration-200 {{ request()->routeIs('contact') ? 'bg-blue-50 text-blue-600' : '' }}">Contact</a>
                
                <div class="h-6 w-px bg-gray-200 mx-2"></div>
                
                <a href="https://t.me/DealsDay_24" target="_blank" class="ml-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-xl transition-all duration-300">
                    Join Telegram
                </a>
            </div>

            <!-- Mobile menu button -->
            <button @click="isOpen = !isOpen" type="button" class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors">
                <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-cloak 
             x-show="isOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="lg:hidden pb-6 pt-2">
            <div class="flex flex-col space-y-2">
                <a href="{{route('home')}}" class="px-4 py-3 text-base font-semibold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl">Home</a>
                <a href="{{route('about')}}" class="px-4 py-3 text-base font-semibold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl">About Us</a>
                <a href="{{route('contact')}}" class="px-4 py-3 text-base font-semibold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl">Contact</a>
                <a href="{{route('privacy')}}" class="px-4 py-3 text-base font-semibold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl">Privacy Policy</a>
                <a href="https://t.me/DealsDay_24" target="_blank" class="mt-4 w-full py-4 bg-blue-600 text-white text-center font-bold rounded-2xl shadow-lg">Join Telegram Community</a>
            </div>
        </div>
    </div>
</nav>
