<div x-data="{ isOpenFilter: false }" class="space-y-8">
    <!-- Hero / Header Section -->
    <div class="text-center space-y-4 mb-10">
        <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-slate-900">
            Handpicked <span class="gradient-text">Premium Deals</span>
        </h1>
        <p class="text-slate-500 max-w-2xl mx-auto text-sm md:text-base font-medium">
            Discover massive discounts on top-rated Amazon products. Updated every hour just for you.
        </p>
    </div>

    <!-- Search and Filter Bar -->
    <div class="sticky top-20 z-40 glass-card p-3 rounded-2xl shadow-sm border border-slate-200/60 mb-8">
        <div class="flex flex-col md:flex-row gap-3">
            <!-- Search -->
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" 
                       class="block w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200" 
                       placeholder="Search for products, brands or categories...">
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button @click="isOpenFilter = !isOpenFilter" 
                        class="flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all duration-200 shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-5 5a1 1 0 00-.293.707v4.586a1 1 0 01-.293.707l-2 2A1 1 0 0110 21v-6.586a1 1 0 00-.293-.707l-5-5A1 1 0 014 6V4z" />
                    </svg>
                    <span>Filters</span>
                    @if($disc > 0 || $date_from || $date_to)
                        <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                    @endif
                </button>
                <button wire:click="clearFilters" 
                        class="p-3 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200 shadow-sm"
                        title="Clear Filters">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Expanded Filters -->
        <div x-cloak x-show="isOpenFilter" x-collapse>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 pb-2 border-t border-slate-100 mt-4">
                <!-- Date Filter -->
                <div class="space-y-3">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Date Range</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input wire:model.live="date_from" type="date" class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <input wire:model.live="date_to" type="date" class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Discount Filter -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Minimum Discount</label>
                        <span class="text-sm font-bold text-blue-600">{{ $disc }}% OFF</span>
                    </div>
                    <input wire:model.live="disc" type="range" min="0" max="90" step="5" 
                           class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                </div>

                <!-- Quick Stats / Info -->
                <div class="flex items-center justify-center md:justify-end">
                    <p class="text-xs text-slate-400 italic">Showing {{ $records->count() }} of total results</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Deals Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($records as $record)
            <article class="group bg-white rounded-3xl overflow-hidden deal-shadow border border-slate-100 flex flex-col h-full">
                <!-- Image Container -->
                <div class="relative pt-[80%] bg-slate-50 overflow-hidden">
                    <img src="{{$record->primary_large_url}}" 
                         alt="{{ $record->product_title }}" 
                         class="absolute inset-0 w-full h-full object-contain p-6 transform group-hover:scale-110 transition-transform duration-500"
                         loading="lazy">
                    
                    <!-- Labels -->
                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                        <span class="px-3 py-1 bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold rounded-lg tracking-wider uppercase">
                            {{$record->updated_at->diffForHumans(null, true)}} ago
                        </span>
                        @if($record->saving_percent)
                            <span class="px-3 py-1 bg-red-600 text-white text-[10px] font-extrabold rounded-lg tracking-wider uppercase shadow-lg shadow-red-200">
                                {{ $record->saving_percent }}% OFF
                            </span>
                        @endif
                    </div>
                    
                    <!-- Quick Preview Overlay -->
                    <div class="absolute inset-0 bg-slate-900/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>

                <!-- Content -->
                <div class="p-5 flex flex-col flex-grow space-y-4">
                    <div class="flex-grow">
                        <h3 class="text-sm font-bold text-slate-800 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                            {{ $record->product_title }}
                        </h3>
                    </div>

                    <!-- Price Info -->
                    <div class="flex items-baseline gap-2">
                        <span class="text-xl font-black text-slate-900">{{ $record->offer_price }}</span>
                        @if($record->mrp)
                            <span class="text-sm text-slate-400 line-through decoration-red-400/50">{{ $record->mrp }}</span>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <a wire:navigate href="{{ route('details', $record->slug ?? $record->id) }}" 
                           class="flex items-center justify-center py-3 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all duration-200">
                            View Details
                        </a>
                        <a href="{{ $record->our_link }}" target="_blank" rel="nofollow"
                           class="flex items-center justify-center py-3 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-100 transition-all duration-200">
                            Grab Deal
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full py-20 text-center space-y-4">
                <div class="inline-flex items-center justify-center h-20 w-20 bg-slate-100 rounded-full text-slate-300 mb-4">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800">No deals found matching your search</h3>
                <p class="text-slate-500">Try adjusting your filters or search keywords.</p>
                <button wire:click="clearFilters" class="text-blue-600 font-bold hover:underline">Clear all filters</button>
            </div>
        @endforelse
    </div>

    <!-- Loading Skeleton (visible during loadMore) -->
    <div wire:loading wire:target="loadMore" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach(range(1, 4) as $i)
            <div class="bg-white rounded-3xl p-1 animate-pulse border border-slate-100">
                <div class="bg-slate-100 h-60 rounded-2xl mb-4"></div>
                <div class="px-4 pb-4 space-y-3">
                    <div class="h-4 bg-slate-100 rounded w-3/4"></div>
                    <div class="h-4 bg-slate-100 rounded w-1/2"></div>
                    <div class="flex gap-2 pt-2">
                        <div class="h-10 bg-slate-100 rounded-xl w-1/2"></div>
                        <div class="h-10 bg-slate-100 rounded-xl w-1/2"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Load More -->
    @if($records->hasMorePages())
        <div class="flex justify-center pt-12">
            <button wire:click="loadMore" 
                    wire:loading.attr="disabled"
                    class="group relative inline-flex items-center gap-3 px-12 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all duration-300 shadow-xl shadow-slate-200">
                <span wire:loading.remove wire:target="loadMore">Browse More Deals</span>
                <span wire:loading wire:target="loadMore">Hunting for more...</span>
                <svg wire:loading.remove wire:target="loadMore" 
                     class="h-5 w-5 transform group-hover:translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </button>
        </div>
    @endif
</div>
