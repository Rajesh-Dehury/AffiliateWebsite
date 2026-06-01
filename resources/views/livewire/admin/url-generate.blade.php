<div class="bg-white m-3 p-6 rounded-xl shadow-sm border border-gray-100" 
     x-data="{
        copiedField: null,
        copyToClipboard(id) { 
            const text = document.getElementById(id).value || document.getElementById(id).innerText;
            navigator.clipboard.writeText(text).then(() => { 
                this.copiedField = id; 
                setTimeout(() => { this.copiedField = null; }, 2000); 
            }); 
        }
    }"
    x-on:open-link.window="window.open($event.detail.url, '_blank')"
>
    <div class="flex flex-col gap-6">
        <!-- Input Section -->
        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                🔗 URL Converter & Quick Post
            </h2>
            <form wire:submit.prevent="scrape" class="flex gap-2">
                <input type="text" wire:model="url" placeholder="Paste Amazon Product URL here..." 
                       class="flex-1 bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-3">
                <button type="submit" wire:loading.attr="disabled" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-lg text-sm transition flex items-center gap-2 disabled:opacity-50">
                    <span wire:loading.remove wire:target="scrape">🚀 Convert</span>
                    <span wire:loading wire:target="scrape">Scraping...</span>
                </button>
                <button type="button" wire:click="resetT" class="p-3 text-gray-400 hover:text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </button>
            </form>
            @error('url') <p class="mt-2 text-xs text-red-500 font-medium">{{$message}}</p> @enderror
        </div>

        @if($wp_post)
        <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
            <!-- Preview & Copy Area -->
            <div class="space-y-4 max-w-2xl mx-auto">
                <div class="flex justify-between items-center">
                    <label class="text-sm font-bold text-gray-500 uppercase tracking-wider">Generated Deal Post</label>
                    <button @click="copyToClipboard('wp_post')" 
                            class="text-xs font-bold px-3 py-1.5 rounded-md transition border"
                            :class="copiedField === 'wp_post' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-white text-blue-600 border-blue-200 hover:bg-blue-50'">
                        <span x-show="copiedField !== 'wp_post'">📋 Copy Content</span>
                        <span x-show="copiedField === 'wp_post'">✅ Copied!</span>
                    </button>
                </div>
                
                <div id="wp_post" class="w-full bg-gray-50 border border-dashed border-gray-300 text-gray-800 text-sm rounded-xl p-6 font-mono whitespace-pre-wrap leading-relaxed shadow-inner">{{ $wp_post }}</div>
                
                <div class="flex flex-wrap gap-3">
                    <button wire:click="sendTelegram" 
                            class="flex-1 bg-[#0088cc] hover:bg-[#0077b5] text-white font-bold py-4 rounded-xl text-sm transition flex items-center justify-center gap-2 shadow-sm">
                        ✈️ Post to Telegram
                    </button>
                    <button wire:click="sendWhatsapp" 
                            class="flex-1 bg-[#25D366] hover:bg-[#128C7E] text-white font-bold py-4 rounded-xl text-sm transition flex items-center justify-center gap-2 shadow-sm">
                        💬 Post to WhatsApp
                    </button>
                </div>

                <div class="pt-4 flex gap-3">
                    <button wire:click="savePost" class="flex-1 bg-gray-800 hover:bg-black text-white font-bold py-3 rounded-lg text-xs transition uppercase tracking-wider">
                        💾 Save to Website
                    </button>
                    <button wire:click="postToFacebookPage" class="flex-1 bg-[#1877F2] hover:bg-[#166fe5] text-white font-bold py-3 rounded-lg text-xs transition uppercase tracking-wider">
                        📘 Facebook
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>