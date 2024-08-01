<section class="bg-white dark:bg-gray-900">
    <div class="container flex items-center justify-center min-h-screen px-6 mx-auto">
        <form wire:submit="login" class="w-full max-w-md">
            <img class="w-auto h-20 rounded-full bg-white" src="{{asset('logo.png')}}" alt="">

            <h1 class="mt-3 text-2xl font-semibold text-gray-800 capitalize sm:text-3xl dark:text-white">Admin sign
                In
            </h1>

            <div>
                <div class="relative flex items-center mt-8">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>

                    <input wire:model="email" type="email" class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" placeholder="Email address">
                </div>
                @error('email')
                <p class="text-red-500 mt-1 text-xs">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <div class="relative flex items-center mt-4">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>

                    <input wire:model="password" type="password" class="block w-full px-10 py-3 text-gray-700 bg-white border rounded-lg dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" placeholder="Password">
                </div>
                @error('password')
                <p class="text-red-500 mt-1 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full mt-4">
                <input wire:model="remember" id="remember" class="w-3 h-3 text-blue-600 bg-gray-100 rounded-lg border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" type="checkbox"> <label class="text-sm text-gray-700 dark:text-gray-200 hover:text-gray-500" for="remember">Remember Login</label>
            </div>


            <div class="mt-6">
                <button class="w-full px-6 py-3 text-sm font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-500 rounded-lg hover:bg-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                    Sign in
                </button>
            </div>
            <div class="mt-6">
                @if(session()->has('error_message'))
                <p class="w-full px-6 py-3 text-sm font-medium tracking-wide text-white bg-red-500 rounded-lg">
                    {{ session('error_message') }}
                </p>
                @endif
            </div>
        </form>
    </div>
</section>