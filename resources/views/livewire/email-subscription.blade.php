<div>
    <form wire:submit.prevent="subscribe" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-grow">
            <input type="email" wire:model="email" 
                   class="w-full px-5 py-3 bg-slate-100 border border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-medium placeholder:text-slate-400 text-sm" 
                   placeholder="Enter your email address">
            @error('email') <span class="absolute -bottom-5 left-2 text-[10px] font-bold text-red-500 uppercase">{{ $message }}</span> @enderror
        </div>
        <button type="submit" 
                class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all duration-300 shadow-lg shadow-blue-200 shrink-0">
            Subscribe
        </button>
    </form>

    @if (session()->has('subscription_success'))
        <p class="mt-4 text-sm font-bold text-green-600 animate-pulse">{{ session('subscription_success') }}</p>
    @endif
    @if (session()->has('subscription_error'))
        <p class="mt-4 text-sm font-bold text-orange-600">{{ session('subscription_error') }}</p>
    @endif
</div>
