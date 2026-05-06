<div x-data="{ isOpenFilter: false }" class="space-y-6">
    <!-- Compact Search and Filter Bar -->
    <div class="sticky top-20 z-40 glass-card p-2 rounded-xl shadow-sm border border-slate-200/60 mb-6">
        <div class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
            <!-- Search -->
            <div class="relative flex-grow min-w-0">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" 
                       class="block w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200" 
                       placeholder="Search products...">
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 flex-shrink-0">
                <button @click="isOpenFilter = !isOpenFilter" 
                        class="flex items-center gap-1 px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-700 hover:bg-slate-50 transition-all duration-200 whitespace-nowrap h-full">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-5 5a1 1 0 00-.293.707v4.586a1 1 0 01-.293.707l-2 2A1 1 0 0110 21v-6.586a1 1 0 00-.293-.707l-5-5A1 1 0 014 6V4z" />
                    </svg>
                    <span>Filters</span>
                    @if($disc > 0 || $date_from || $date_to)
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600 flex-shrink-0"></span>
                    @endif
                </button>
                <button wire:click="clearFilters" 
                        class="p-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200 flex-shrink-0 h-full"
                        title="Clear Filters">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Expanded Filters -->
        <div x-cloak x-show="isOpenFilter" x-collapse>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-3 pb-1 border-t border-slate-100 mt-3">
                <!-- Date Filter -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Date</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input wire:model.live="date_from" type="date" class="block w-full px-2 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <input wire:model.live="date_to" type="date" class="block w-full px-2 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <!-- Discount Filter -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Min Discount</label>
                        <span class="text-xs font-bold text-blue-600">{{ $disc }}%</span>
                    </div>
                    <input wire:model.live="disc" type="range" min="0" max="90" step="5" 
                           class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                </div>
                
                <!-- Quick Stats -->
                <div class="flex items-center justify-center md:justify-end">
                    <p class="text-[10px] text-slate-400">{{ $records->count() }} results</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Deals Grid - More columns, smaller gap -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-3 sm:gap-4">
        @forelse($records as $record)
            <article class="group bg-white rounded-2xl overflow-hidden deal-shadow border border-slate-100 flex flex-col h-full">
                <!-- Image Container - Compact -->
                <div class="relative pt-[65%] bg-slate-50 overflow-hidden">
                    <img src="{{$record->primary_large_url}}" 
                         alt="{{ $record->product_title }}" 
                         class="absolute inset-0 w-full h-full object-contain p-3 transform group-hover:scale-105 transition-transform duration-300"
                         loading="lazy">
                    
                    <!-- Labels - Smaller -->
                    <div class="absolute top-2 left-2 flex flex-col gap-1">
                        <span class="px-2 py-0.5 bg-slate-900/80 backdrop-blur-md text-white text-[9px] font-bold rounded-md">
                            {{$record->updated_at->diffForHumans(null, true)}} ago
                        </span>
                        @if($record->saving_percent)
                            <span class="px-2 py-0.5 bg-red-600 text-white text-[9px] font-extrabold rounded-md shadow shadow-red-200">
                                {{ $record->saving_percent }}% OFF
                            </span>
                        @endif
                    </div>
                    
                    <!-- Quick Preview Overlay -->
                    <div class="absolute inset-0 bg-slate-900/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>

                <!-- Content - Compact -->
                <div class="p-3 flex flex-col flex-grow space-y-2">
                    <div class="flex-grow">
                        <h3 class="text-xs font-bold text-slate-800 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                            {{ $record->product_title }}
                        </h3>
                    </div>

                    <!-- Price Info -->
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-base font-black text-slate-900">{{ $record->offer_price }}</span>
                        @if($record->mrp)
                            <span class="text-xs text-slate-400 line-through">{{ $record->mrp }}</span>
                        @endif
                    </div>

                    <!-- Actions - Compact -->
                    <div class="grid grid-cols-2 gap-1.5 pt-1">
                        <a wire:navigate href="{{ route('details', $record->slug ?? $record->id) }}" 
                           class="flex items-center justify-center py-2 text-[10px] font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all duration-200">
                            Details
                        </a>
                        <a href="{{ $record->our_link }}" target="_blank" rel="nofollow"
                           class="flex items-center justify-center py-2 text-[10px] font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition-all duration-200">
                            Grab
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full py-16 text-center space-y-3">
                <div class="inline-flex items-center justify-center h-16 w-16 bg-slate-100 rounded-full text-slate-300 mb-3">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">No deals found</h3>
                <p class="text-slate-500 text-sm">Try adjusting your filters or search.</p>
                <button wire:click="clearFilters" class="text-blue-600 font-bold hover:underline text-sm">Clear all filters</button>
            </div>
        @endforelse
    </div>

    <!-- Loading Skeleton -->
    <div wire:loading wire:target="loadMore" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-3 sm:gap-4">
        @foreach(range(1, 6) as $i)
            <div class="bg-white rounded-2xl p-1 animate-pulse border border-slate-100">
                <div class="bg-slate-100 h-32 rounded-xl mb-2"></div>
                <div class="px-3 pb-3 space-y-2">
                    <div class="h-3 bg-slate-100 rounded w-3/4"></div>
                    <div class="h-3 bg-slate-100 rounded w-1/2"></div>
                    <div class="flex gap-1.5 pt-1">
                        <div class="h-7 bg-slate-100 rounded-lg w-1/2"></div>
                        <div class="h-7 bg-slate-100 rounded-lg w-1/2"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Load More - Compact -->
    @if($records->hasMorePages())
        <div class="flex justify-center pt-8">
            <button wire:click="loadMore" 
                    wire:loading.attr="disabled"
                    class="group inline-flex items-center gap-2 px-8 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all duration-300 shadow-lg shadow-slate-200">
                <span wire:loading.remove wire:target="loadMore">More Deals</span>
                <span wire:loading wire:target="loadMore">Loading...</span>
                <svg wire:loading.remove wire:target="loadMore" 
                     class="h-4 w-4 transform group-hover:translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </button>
        </div>
    @endif
</div>
