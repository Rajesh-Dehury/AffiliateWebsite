<div id="deal-finder" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#232f3e] to-[#37475a] px-6 py-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <svg class="h-6 w-6 text-[#FF9900]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Deal Finder | Find your own deals
        </h2>
        <p class="text-slate-300 text-sm mt-1">Search millions of Amazon products with filters</p>
    </div>

    <!-- Filter Form -->
    <div class="p-6 space-y-6">
        <form wire:submit.prevent="redirectToAmazon">
            <!-- Row 1: Keyword + Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Search Keyword</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input wire:model="keyword" type="text" placeholder="e.g. wireless earbuds, kitchen appliances..."
                               class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:ring-2 focus:ring-[#FF9900] focus:border-[#FF9900] transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Select Category</label>
                    <select wire:model="category" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-[#FF9900] focus:border-[#FF9900] bg-white">
                        <option value="all">All Categories</option>
                        <option value="Baby Products">Baby Products</option>
                        <option value="Beauty & Skin Care Products">Beauty & Skin Care</option>
                        <option value="Books">Books</option>
                        <option value="Business, Industrial & Scientific Products">Business & Industrial</option>
                        <option value="Collectibles & Fine Art">Collectibles & Fine Art</option>
                        <option value="Computers & Accessories">Computers & Accessories</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Fashion">Fashion</option>
                        <option value="Garden & Outdoor Living">Garden & Outdoor</option>
                        <option value="Gift Cards">Gift Cards</option>
                        <option value="Grocery & Gourmet Foods">Grocery & Gourmet</option>
                        <option value="Health Household & Personal Care">Health & Personal Care</option>
                        <option value="Home & Kitchen">Home & Kitchen</option>
                        <option value="Kindle eBooks">Kindle eBooks</option>
                        <option value="Luxury Beauty">Luxury Beauty</option>
                        <option value="Men">Men</option>
                        <option value="Movies & TV Shows">Movies & TV Shows</option>
                        <option value="Music">Music</option>
                        <option value="Musical Instruments">Musical Instruments</option>
                        <option value="Pet Supplies">Pet Supplies</option>
                        <option value="Software">Software</option>
                        <option value="Sports, Fitness & Outdoors">Sports & Fitness</option>
                        <option value="Stationery & Office Products">Stationery & Office</option>
                        <option value="Toys & Games">Toys & Games</option>
                        <option value="Vehicle Parts & Accessories">Vehicle Parts</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: Discount + Price Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Discount Percentage</label>
                        <span class="text-sm font-bold text-[#FF9900]">between <span>{{ $minDiscount }}</span>% and <span>{{ $maxDiscount }}</span>%</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <input wire:model.live="minDiscount" type="range" min="0" max="100" step="5"
                               class="flex-1 h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#FF9900]">
                        <input wire:model.live="maxDiscount" type="range" min="0" max="100" step="5"
                               class="flex-1 h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#FF9900]">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Select range between 0 and 100 discount</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Price Range</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <input wire:model="minPrice" type="number" placeholder="Min. Price"
                                   class="block w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:ring-2 focus:ring-[#FF9900] focus:border-[#FF9900]">
                        </div>
                        <div>
                            <input wire:model="maxPrice" type="number" placeholder="Max. Price"
                                   class="block w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:ring-2 focus:ring-[#FF9900] focus:border-[#FF9900]">
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Enter maximum and minimum price</p>
                </div>
            </div>

            <!-- Row 3: Sort By + Prime Logo + Button -->
            <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Sort By</label>
                    <select wire:model="sortBy" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-[#FF9900] focus:border-[#FF9900] bg-white">
                        <option value="relevance">Relevance</option>
                        <option value="rating">Avg. Customer Review</option>
                        <option value="price_low">Price Low to High</option>
                        <option value="price_high">Price High to Low</option>
                        <option value="newest">Newest Arrival</option>
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <img src="https://www.dealsmagnet.com/Assets/img/AmazonDealFinder/amazon_prime_logo.png" alt="Amazon Prime" class="h-8">
                    <span class="text-[#FF9900] font-bold text-lg">+</span>
                </div>

                <button
                    type="submit"
                    class="bg-[#FF9900] hover:bg-amber-500 text-[#232f3e] font-bold px-8 py-3 rounded-lg text-sm transition flex items-center gap-2 whitespace-nowrap">
                    🔍 Find Deals on Amazon
                </button>
            </div>
        </form>
    </div>
</div>
