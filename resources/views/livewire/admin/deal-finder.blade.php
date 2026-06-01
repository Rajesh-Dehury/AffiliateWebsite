<div>
    @if (session()->has('error'))
        <div class="m-3 p-3 bg-red-500 font-medium rounded-lg text-white">
            {{ session('error') }}
        </div>
    @endif
    @if (session()->has('success'))
        <div class="m-3 p-3 bg-green-500 font-medium rounded-lg text-white">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white m-3 p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4 text-gray-800">🛒 Amazon Deal Finder</h2>
        <p class="text-sm text-gray-500 mb-6">Generate 10 AI-powered deals for Telegram, WhatsApp & Instagram</p>

        @if($error)
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                {{ $error }}
            </div>
        @endif

        <!-- Filter Form -->
        <form wire:submit.prevent="fetchDeals" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <!-- Row 1 -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Category</label>
                    <select wire:model="category" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All Categories</option>
                        <option value="electronics">Electronics</option>
                        <option value="smartphones">Smartphones</option>
                        <option value="laptops">Laptops</option>
                        <option value="kitchen">Kitchen Appliances</option>
                        <option value="fashion">Fashion</option>
                        <option value="home">Home & Furniture</option>
                        <option value="beauty">Beauty</option>
                        <option value="sports">Sports & Fitness</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Brands (Optional)</label>
                    <input type="text" wire:model="brands" placeholder="e.g. boAt, Samsung" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Price Range</label>
                    <select wire:model="priceRange" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="any">Any Price</option>
                        <option value="under 1000">Under ₹1,000</option>
                        <option value="1000 to 5000">₹1,000 – ₹5,000</option>
                        <option value="5000 to 15000" selected>₹5,000 – ₹15,000</option>
                        <option value="15000 to 50000">₹15,000 – ₹50,000</option>
                        <option value="50000+">₹50,000+</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Min Rating</label>
                    <select wire:model="minRating" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="3.5">3.5+ Stars</option>
                        <option value="4.0">4.0+ Stars</option>
                        <option value="4.5">4.5+ Stars</option>
                    </select>
                </div>

                <!-- Row 2 -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Min Discount</label>
                    <select wire:model="minDiscount" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="30">30%+</option>
                        <option value="40">40%+</option>
                        <option value="50" selected>50%+</option>
                        <option value="60">60%+</option>
                        <option value="70">70%+</option>
                        <option value="80">80%+</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Target Audience</label>
                    <select wire:model="audience" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="general buyers in India">General</option>
                        <option value="students">Students</option>
                        <option value="homemakers">Homemakers</option>
                        <option value="working professionals">Professionals</option>
                        <option value="gamers">Gamers</option>
                        <option value="fitness enthusiasts">Fitness Freaks</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Sort By</label>
                    <select wire:model="sortBy" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="discount">Highest Discount</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="rating">Top Rated</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <label class="flex items-center cursor-pointer mb-2">
                        <div class="relative">
                            <input type="checkbox" wire:model="primeOnly" class="sr-only">
                            <div class="block {{ $primeOnly ? 'bg-blue-600' : 'bg-gray-200' }} w-10 h-6 rounded-full transition-colors duration-200"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 {{ $primeOnly ? 'translate-x-4' : '' }}"></div>
                        </div>
                        <div class="ml-3 text-gray-700 text-sm font-medium">Prime Only</div>
                    </label>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-6 mt-6">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="bg-[#FF9900] hover:bg-amber-500 text-[#232f3e] font-bold px-8 py-3 rounded-lg text-sm transition flex items-center gap-2 disabled:opacity-50">
                    <span wire:loading.remove wire:target="fetchDeals">⚡ Find Targeted Deals</span>
                    <span wire:loading wire:target="fetchDeals">⏳ Searching...</span>
                </button>
            </div>
        </form>

        <!-- Loading State -->
        <div wire:loading wire:target="fetchDeals" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#FF9900]"></div>
            <p class="mt-2 text-sm text-gray-500">Calling Gemini AI to generate deals...</p>
        </div>

        <!-- Deals Results -->
        @if(!empty($deals) && !$loading)
            <div class="border-t pt-6">
                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Deals</p>
                        <p class="text-2xl font-bold text-amber-600">{{ count($deals) }}</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Avg Discount</p>
                        <p class="text-2xl font-bold text-red-600">
                            {{ round(collect($deals)->avg('discountPercent')) }}%
                        </p>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Max Savings</p>
                        <p class="text-2xl font-bold text-green-700">
                            ₹{{ number_format(collect($deals)->max(fn($d) => ($d['originalPrice'] ?? 0) - ($d['currentPrice'] ?? 0))) }}
                        </p>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Prime Deals</p>
                        <p class="text-2xl font-bold text-blue-700">
                            {{ collect($deals)->where('isPrime', true)->count() }}
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 mb-6">
                    <button
                        wire:click="verifyAvailability"
                        wire:loading.attr="disabled"
                        class="bg-amber-100 hover:bg-amber-200 text-amber-800 border border-amber-300 font-bold px-5 py-2 rounded-lg text-sm transition flex items-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove wire:target="verifyAvailability">🔍 Verify Availability (API)</span>
                        <span wire:loading wire:target="verifyAvailability">Verifying...</span>
                    </button>

                    @if(collect($deals)->where('isAvailable', false)->count() > 0)
                        <button
                            wire:click="removeUnavailable"
                            class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold px-5 py-2 rounded-lg text-sm transition">
                            🗑️ Remove {{ collect($deals)->where('isAvailable', false)->count() }} Unavailable
                        </button>
                    @endif

                    <button
                        wire:click="saveDeals"
                        wire:loading.attr="disabled"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold px-5 py-2 rounded-lg text-sm transition flex items-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove wire:target="saveDeals">💾 Save All to Website</span>
                        <span wire:loading wire:target="saveDeals">Saving...</span>
                    </button>

                    <button
                        wire:click="postAllToTelegram"
                        wire:loading.attr="disabled"
                        class="bg-sky-600 hover:bg-sky-700 text-white font-bold px-5 py-2 rounded-lg text-sm transition flex items-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove wire:target="postAllToTelegram">✈️ Post All to Telegram</span>
                        <span wire:loading wire:target="postAllToTelegram">Posting...</span>
                    </button>

                    <button
                        onclick="copyAll()"
                        class="bg-[#232f3e] hover:bg-gray-900 text-[#FF9900] font-bold px-5 py-2 rounded-lg text-sm transition">
                        📋 Copy All 10 Posts
                    </button>
                </div>

                <!-- Deal Cards -->
                <div class="flex flex-col gap-3">
                    @foreach($deals as $index => $deal)
                        <div class="bg-white rounded-xl border {{ $index < 3 ? 'border-l-4 border-l-[#FF9900]' : '' }} {{ isset($deal['isAvailable']) && !$deal['isAvailable'] ? 'opacity-60 grayscale' : '' }} border-gray-200 p-5 hover:shadow-md transition">
                            <div class="flex gap-4">
                                <div class="text-2xl font-black {{ $index < 3 ? 'text-[#FF9900]' : 'text-gray-300' }} min-w-[36px]">
                                    #{{ $deal['rank'] ?? ($index + 1) }}
                                </div>
                                
                                <!-- Product Image -->
                                <div class="w-24 h-24 flex-shrink-0 bg-gray-50 rounded-lg overflow-hidden border border-gray-100 flex items-center justify-center relative">
                                    @if(isset($deal['imageUrl']) && $deal['imageUrl'])
                                        <img src="{{ $deal['imageUrl'] }}" alt="Product" class="object-contain w-full h-full">
                                    @else
                                        <span class="text-4xl">{{ $deal['emoji'] ?? '📦' }}</span>
                                    @endif

                                    @if(isset($deal['isAvailable']))
                                        <div class="absolute bottom-0 inset-x-0 text-[10px] font-bold text-center py-0.5 {{ $deal['isAvailable'] ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                            {{ $deal['isAvailable'] ? 'IN STOCK' : 'OUT OF STOCK' }}
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-400 mb-2">
                                        {{ $deal['emoji'] ?? '📦' }} {{ $deal['category'] ?? 'General' }}
                                        @if($deal['isPrime'] ?? false)
                                            &nbsp;·&nbsp; 🔵 Prime
                                        @endif
                                        &nbsp;·&nbsp; ⭐ {{ $deal['rating'] ?? '4.0' }} ({{ $deal['reviewCount'] ?? '0' }} reviews)
                                        
                                        @if(isset($deal['isAvailable']) && !$deal['isAvailable'])
                                            <span class="ml-2 text-red-600 font-bold">⚠️ UNAVAILABLE</span>
                                        @endif
                                    </p>

                                    <!-- Post Preview -->
                                    <pre class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-4 text-sm leading-relaxed whitespace-pre-wrap break-all font-mono text-gray-800" id="post-{{ $index }}">{{ $this->getPostText($deal) }}</pre>

                                    <!-- Actions -->
                                    <div class="flex flex-wrap gap-3 mt-3">
                                        <button
                                            onclick="copyPost({{ $index }})"
                                            class="border border-[#FF9900] text-amber-700 text-sm font-semibold px-4 py-2 rounded-lg hover:bg-amber-50 transition"
                                            id="btn-{{ $index }}">
                                            📋 Copy
                                        </button>
                                        <button
                                            wire:click="postToWebsite({{ $index }})"
                                            wire:loading.attr="disabled"
                                            class="bg-green-600 text-white text-sm font-bold px-4 py-2 rounded-lg hover:bg-green-700 transition disabled:opacity-50">
                                            💾 Post to Web
                                        </button>
                                        <button
                                            wire:click="postToTelegram({{ $index }})"
                                            wire:loading.attr="disabled"
                                            class="bg-sky-600 text-white text-sm font-bold px-4 py-2 rounded-lg hover:bg-sky-700 transition disabled:opacity-50">
                                            ✈️ Telegram
                                        </button>
                                        <a href="{{ $deal['affiliate_link'] ?? '#' }}" target="_blank"
                                           class="bg-[#FF9900] text-[#232f3e] text-sm font-bold px-4 py-2 rounded-lg hover:bg-amber-500 transition ml-auto">
                                            View ↗
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function copyPost(index) {
            const text = document.getElementById('post-' + index).innerText;
            const btn  = document.getElementById('btn-' + index);
            navigator.clipboard.writeText(text).then(() => {
                btn.textContent = '✅ Copied!';
                btn.classList.add('border-green-500', 'text-green-700', 'bg-green-50');
                setTimeout(() => {
                    btn.textContent = '📋 Copy Post';
                    btn.classList.remove('border-green-500', 'text-green-700', 'bg-green-50');
                }, 2200);
            });
        }

        function copyAll() {
            const text = @json($this->getAllPostText());
            navigator.clipboard.writeText(text).then(() => {
                alert('✅ All 10 posts copied to clipboard!');
            });
        }
    </script>
</div>
