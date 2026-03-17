<div class="max-w-6xl mx-auto space-y-12">
    <!-- Header Section -->
    <header class="text-center space-y-4 py-10">
        <h1 class="text-4xl md:text-6xl font-black tracking-tight text-slate-900">
            Get In <span class="gradient-text">Touch</span>
        </h1>
        <p class="text-slate-500 max-w-2xl mx-auto text-lg font-medium">
            Join our community or reach out to our team for support and feedback.
        </p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        <!-- Community Section -->
        <div class="lg:col-span-5 space-y-8">
            <div class="space-y-6">
                <h2 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                    <span class="h-8 w-1.5 bg-blue-600 rounded-full"></span>
                    Join our Communities
                </h2>
                
                <!-- WhatsApp Groups -->
                <div class="glass-card p-6 rounded-[2rem] border border-slate-100 shadow-sm space-y-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-10 w-10 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                            <img src="{{asset('whatsapp-svgrepo-com.svg')}}" class="h-6 w-6">
                        </div>
                        <h3 class="font-bold text-slate-800">WhatsApp Groups</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @php
                            $groups = [
                                'DealsDay' => 'https://chat.whatsapp.com/K5lVjY7pPH35B8U0GdIv2K',
                                'DealsDay 2' => 'https://chat.whatsapp.com/JIporKDkkEQFsCYTMp1H7D',
                                'DealsDay 3' => 'https://chat.whatsapp.com/IhleNxgHdhQEmKbm8rIos3',
                                'DealsDay 4' => 'https://chat.whatsapp.com/DQAE7QgWFUQ9okzFjsHWso',
                            ];
                        @endphp
                        @foreach($groups as $name => $url)
                            <a href="{{ $url }}" target="_blank" class="flex items-center justify-between p-3 bg-slate-50 hover:bg-green-50 rounded-xl border border-slate-100 transition-colors group">
                                <span class="text-sm font-bold text-slate-700 group-hover:text-green-700">{{ $name }}</span>
                                <svg class="h-4 w-4 text-slate-400 group-hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Direct Social Links -->
                <div class="grid grid-cols-2 gap-4">
                    <a href="https://t.me/DealsDay_24" target="_blank" class="glass-card p-5 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center text-center space-y-2 hover:shadow-lg transition-all hover:-translate-y-1">
                        <div class="h-12 w-12 bg-blue-100 rounded-2xl flex items-center justify-center mb-1">
                            <img src="{{asset('telegram-svgrepo-com.svg')}}" class="h-7 w-7">
                        </div>
                        <span class="text-xs font-black text-slate-900 uppercase tracking-widest">Telegram</span>
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61564583578956" target="_blank" class="glass-card p-5 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center text-center space-y-2 hover:shadow-lg transition-all hover:-translate-y-1">
                        <div class="h-12 w-12 bg-indigo-100 rounded-2xl flex items-center justify-center mb-1">
                            <img src="{{asset('facebook-svgrepo-com.svg')}}" class="h-7 w-7">
                        </div>
                        <span class="text-xs font-black text-slate-900 uppercase tracking-widest">Facebook</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Form Section -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-[3rem] p-8 md:p-12 shadow-2xl shadow-slate-200 border border-slate-100 space-y-8">
                <div class="space-y-2">
                    <h2 class="text-3xl font-black text-slate-900">Send a Message</h2>
                    <p class="text-slate-500 font-medium">Have a specific question or partnership inquiry? Drop us a line.</p>
                </div>

                @if (session()->has('success'))
                    <div class="bg-green-50 border border-green-100 p-4 rounded-2xl flex items-center gap-3 text-green-700 animate-bounce">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Email Address</label>
                            <input type="email" wire:model="email" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-medium placeholder:text-slate-300" placeholder="your@email.com">
                            @error('email') <span class="text-red-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Subject</label>
                            <input type="text" wire:model="subject" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-medium placeholder:text-slate-300" placeholder="How can we help?">
                            @error('subject') <span class="text-red-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Message Detail</label>
                        <textarea wire:model="message" rows="5" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-medium placeholder:text-slate-300" placeholder="Tell us more about your inquiry..."></textarea>
                        @error('message') <span class="text-red-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" 
                            class="w-full py-5 bg-slate-900 hover:bg-slate-800 text-white font-black rounded-2xl transition-all duration-300 shadow-xl shadow-slate-200 flex items-center justify-center gap-2 group">
                        Send Message
                        <svg class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
