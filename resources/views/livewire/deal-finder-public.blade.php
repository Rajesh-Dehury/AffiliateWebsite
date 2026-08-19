<div id="deal-finder" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Header -->
    <div class="relative bg-gradient-to-br from-[#232f3e] via-[#2c3e50] to-[#37475a] px-6 py-5 overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                <circle cx="10" cy="10" r="2" fill="white" />
                <circle cx="90" cy="20" r="1.5" fill="white" />
                <circle cx="50" cy="5" r="1" fill="white" />
                <circle cx="20" cy="40" r="1.5" fill="white" />
                <circle cx="80" cy="70" r="2" fill="white" />
                <circle cx="40" cy="85" r="1" fill="white" />
                <circle cx="70" cy="45" r="1.5" fill="white" />
            </svg>
        </div>
        <div class="relative flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 bg-[#FF9900]/15 rounded-xl flex items-center justify-center">
                <svg class="h-6 w-6 text-[#FF9900]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white tracking-tight">Deal Finder</h2>
                <p class="text-slate-400 text-xs mt-0.5">Find your own deals — search millions of Amazon products</p>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="p-6 space-y-5">
        <form wire:submit.prevent="redirectToAmazon" class="space-y-5">
            <!-- Row 1: Keyword + Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="group">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Search Keyword</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400 group-focus-within:text-[#FF9900] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input wire:model="keyword" type="text" placeholder="e.g. wireless earbuds, kitchen appliances..."
                               class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-[#FF9900]/20 focus:border-[#FF9900] focus:outline-none transition-all duration-200">
                    </div>
                </div>

                <div class="group">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Select Category</label>
                    <select wire:model="category"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-700 focus:bg-white focus:ring-2 focus:ring-[#FF9900]/20 focus:border-[#FF9900] focus:outline-none transition-all duration-200 appearance-none cursor-pointer"
                            style="background-image: url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2212%22%20height%3D%2212%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%239ca3af%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; padding-right: 2.5rem;">
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Discount Range</label>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-[#FF9900] bg-[#FF9900]/10 px-2.5 py-1 rounded-full">
                            <span>{{ $minDiscount }}</span>
                            <span class="text-slate-400">—</span>
                            <span>{{ $maxDiscount }}</span>%
                        </span>
                    </div>
                    <div class="space-y-1.5">
                        <div class="relative pt-1">
                            <input wire:model.live="minDiscount" type="range" min="0" max="100" step="5"
                                   class="w-full h-1.5 bg-slate-200 rounded-full appearance-none cursor-pointer accent-[#FF9900] range-thumb">
                        </div>
                        <div class="relative">
                            <input wire:model.live="maxDiscount" type="range" min="0" max="100" step="5"
                                   class="w-full h-1.5 bg-slate-200 rounded-full appearance-none cursor-pointer accent-[#FF9900] range-thumb">
                        </div>
                    </div>
                    <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                        <span>0%</span>
                        <span>50%</span>
                        <span>100%</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Price Range</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm font-medium">$</span>
                            </div>
                            <input wire:model="minPrice" type="number" placeholder="Min"
                                   class="block w-full pl-7 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-[#FF9900]/20 focus:border-[#FF9900] focus:outline-none transition-all duration-200">
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm font-medium">$</span>
                            </div>
                            <input wire:model="maxPrice" type="number" placeholder="Max"
                                   class="block w-full pl-7 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-[#FF9900]/20 focus:border-[#FF9900] focus:outline-none transition-all duration-200">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 3: Sort By + Prime + Button -->
            <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 pt-1">
                <div class="flex-1 w-full sm:w-auto group">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Sort By</label>
                    <select wire:model="sortBy"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-700 focus:bg-white focus:ring-2 focus:ring-[#FF9900]/20 focus:border-[#FF9900] focus:outline-none transition-all duration-200 appearance-none cursor-pointer"
                            style="background-image: url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2212%22%20height%3D%2212%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%239ca3af%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; padding-right: 2.5rem;">
                        <option value="relevance">Relevance</option>
                        <option value="rating">Avg. Customer Review</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="newest">Newest Arrivals</option>
                    </select>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 border border-blue-100 rounded-lg">
                        <svg class="h-5 w-5 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <span class="text-xs font-semibold text-blue-700">Prime Eligible</span>
                    </div>

                    <button type="submit"
                            class="group relative bg-gradient-to-r from-[#FF9900] to-[#ffb84d] hover:from-[#e88f00] hover:to-[#ffa726] text-[#232f3e] font-bold px-7 py-2.5 rounded-xl text-sm transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2 whitespace-nowrap">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Find Deals on Amazon
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>