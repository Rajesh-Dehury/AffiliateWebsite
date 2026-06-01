<footer class="bg-white border-t border-slate-100">
    <div class="container px-6 py-12 mx-auto">
        <!-- Deal Finder Section -->
        <div class="mb-12">
            <livewire:deal-finder-public />
        </div>

        <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
            <!-- Brand & Tagline -->
            <div class="space-y-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img class="w-12 h-12 rounded-xl shadow-md" src="{{ asset('logo.png') }}" alt="DealsDay">
                    <span class="text-2xl font-black gradient-text">DealsDay24</span>
                </a>
                <p class="text-sm text-slate-500 font-medium leading-relaxed max-w-xs">
                    Handpicking the best Amazon deals and exclusive discounts across India. Save more every single day.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] mb-6">Explore</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a wire:navigate href="{{ route('home') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">Home</a>
                    <a wire:navigate href="{{ route('about') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">About Us</a>
                    <a wire:navigate href="{{ route('contact') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">Contact</a>
                    <a wire:navigate href="{{ route('privacy') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">Privacy</a>
                    <a wire:navigate href="{{ route('disclaimer') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">Disclaimer</a>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] mb-2">Newsletter</h3>
                    <p class="text-sm text-slate-500 font-medium">Join 10,000+ shoppers and get top deals in your inbox.</p>
                </div>
                <livewire:email-subscription />
            </div>
        </div>

        <hr class="my-10 border-slate-100" />

        <div class="flex flex-col items-center justify-between gap-6 sm:flex-row">
            <p class="text-xs font-bold text-slate-400">© {{ date('Y') }} DealsDay24. Built for smart shoppers.</p>

            <div class="flex items-center gap-6">
                <a href="https://t.me/DealsDay_24" target="_blank" class="text-slate-400 hover:text-[#0088cc] transition-colors">
                    <img src="{{ asset('telegram-svgrepo-com.svg') }}" class="h-5 w-5 opacity-50 hover:opacity-100">
                </a>
                <a href="https://wa.me/+916371391755" target="_blank" class="text-slate-400 hover:text-[#25D366] transition-colors">
                    <img src="{{ asset('whatsapp-svgrepo-com.svg') }}" class="h-5 w-5 opacity-50 hover:opacity-100">
                </a>
                <a href="https://www.facebook.com/profile.php?id=61564583578956" target="_blank" class="text-slate-400 hover:text-[#1877F2] transition-colors">
                    <img src="{{ asset('facebook-svgrepo-com.svg') }}" class="h-5 w-5 opacity-50 hover:opacity-100">
                </a>
            </div>
        </div>
    </div>
</footer>
