<div class="bg-white m-3 p-6 rounded-xl shadow-sm border border-gray-100"
     x-data="{
        copiedField: null,
        copyToClipboard(id) { 
            const text = document.getElementById(id).innerText;
            navigator.clipboard.writeText(text).then(() => { 
                this.copiedField = id; 
                setTimeout(() => { this.copiedField = null; }, 2000); 
            }); 
        }
    }"
    x-on:open-link.window="window.open($event.detail.url, '_blank')"
>
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                🇮🇳 IndiaFreeStuff Scraper
            </h2>
            <div class="flex flex-wrap gap-3">
                <button wire:click="scrape" wire:loading.attr="disabled"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-lg text-sm transition flex items-center gap-2 disabled:opacity-50">
                    <span wire:loading.remove wire:target="scrape">🔄 Refresh Latest 20</span>
                    <span wire:loading wire:target="scrape">⏳ Scraping...</span>
                </button>

                @if(count($scrapedDeals) > 0)
                    <button wire:click="postAllToTelegram" wire:loading.attr="disabled"
                            class="bg-sky-600 hover:bg-sky-700 text-white font-bold px-6 py-2.5 rounded-lg text-sm transition flex items-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove wire:target="postAllToTelegram">✈️ Post Top 10</span>
                        <span wire:loading wire:target="postAllToTelegram">Posting...</span>
                    </button>
                    <button onclick="copyAllDeals()"
                            class="bg-[#232f3e] hover:bg-gray-900 text-[#FF9900] font-bold px-6 py-2.5 rounded-lg text-sm transition shadow-sm">
                        📋 Copy Top 10
                    </button>
                @endif
            </div>
        </div>

        <script>
            function copyAllDeals() {
                const text = @json($this->getAllPostText());
                navigator.clipboard.writeText(text).then(() => {
                    alert('✅ Top 10 deals copied to clipboard!');
                });
            }
        </script>

        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- Deals Grid -->
        <div class="grid grid-cols-1 gap-6">
            @foreach($scrapedDeals as $index => $deal)
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Product Info -->
                        <div class="w-full md:w-1/4">
                            <div class="aspect-square bg-white rounded-lg overflow-hidden border border-gray-100 flex items-center justify-center p-2 mb-3">
                                @if($deal['image_url'])
                                    <img src="{{ $deal['image_url'] }}" alt="Product" class="object-contain w-full h-full">
                                @else
                                    <span class="text-4xl">📦</span>
                                @endif
                            </div>
                            <div class="text-center">
                                <span class="bg-red-100 text-red-600 text-xs font-bold px-2.5 py-1 rounded-full">{{ $deal['discount'] }}% OFF</span>
                                <p class="text-lg font-black text-gray-800 mt-1">₹{{ number_format($deal['offer_price']) }}</p>
                                <p class="text-xs text-gray-400 line-through">₹{{ number_format($deal['mrp']) }}</p>
                            </div>
                        </div>

                        <!-- Post Preview & Actions -->
                        <div class="flex-1 min-w-0 flex flex-col gap-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-bold text-gray-700 text-sm truncate">{{ $deal['title'] }}</h3>
                                <button @click="copyToClipboard('post-{{ $index }}')" 
                                        class="text-xs font-bold px-3 py-1.5 rounded-md transition border"
                                        :class="copiedField === 'post-{{ $index }}' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-white text-blue-600 border-blue-200 hover:bg-blue-50'">
                                    <span x-show="copiedField !== 'post-{{ $index }}'">📋 Copy</span>
                                    <span x-show="copiedField === 'post-{{ $index }}'">✅ Copied!</span>
                                </button>
                            </div>

                            <div id="post-{{ $index }}" class="bg-white border border-dashed border-gray-300 rounded-lg p-4 text-xs font-mono whitespace-pre-wrap leading-relaxed shadow-sm text-gray-800">{{ $this->getPostText($index) }}</div>

                            <div class="flex flex-wrap gap-2 mt-auto">
                                <button wire:click="postToTelegram({{ $index }})" 
                                        class="bg-[#0088cc] hover:bg-[#0077b5] text-white font-bold px-4 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                                    ✈️ Telegram
                                </button>
                                <button wire:click="postToWhatsapp({{ $index }})" 
                                        class="bg-[#25D366] hover:bg-[#128C7E] text-white font-bold px-4 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                                    💬 WhatsApp
                                </button>
                                <button wire:click="postToWebsite({{ $index }})" 
                                        class="bg-gray-800 hover:bg-black text-white font-bold px-4 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                                    💾 Post to Web
                                </button>
                                 <a href="{{ $deal['our_link'] }}" target="_blank" 
                                   class="ml-auto text-blue-600 hover:text-blue-800 text-xs font-bold underline">
                                    Source ↗
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if(empty($scrapedDeals) && !$isScraping)
            <div class="text-center py-20 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                <p class="text-gray-400">No deals found. Click refresh to try again.</p>
            </div>
        @endif
    </div>
</div>
