@push('seo')
    <title>{{ $record->product_title }} - DealsDay24</title>
    <meta name="description" content="Get the best deal on {{ $record->product_title }}. Now only {{ $record->offer_price }}. Original price: {{ $record->mrp }}.">
    <meta property="og:title" content="{{ $record->product_title }} - DealsDay24">
    <meta property="og:description" content="Get the best deal on {{ $record->product_title }}. Now only {{ $record->offer_price }}.">
    <meta property="og:image" content="{{ $record->primary_large_url }}">
    
    <!-- JSON-LD for Rich Snippets -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "{{ $record->product_title }}",
      "image": "{{ $record->primary_large_url }}",
      "description": "Get the best deal on {{ $record->product_title }}",
      "brand": {
        "@type": "Brand",
        "name": "Amazon"
      },
      "offers": {
        "@type": "Offer",
        "url": "{{ route('open.az.prod', $record->product_asin) }}",
        "priceCurrency": "INR",
        "price": "{{ preg_replace('/[^0-9.]/', '', $record->offer_price) }}",
        "availability": "https://schema.org/InStock"
      }
    }
    </script>
@endpush

<div class="max-w-7xl mx-auto space-y-8">
    <!-- Breadcrumb -->
    <nav class="flex text-sm font-medium text-slate-400 mb-6">
        <a wire:navigate href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Home</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600 truncate max-w-[200px] md:max-w-md">{{ $record->product_title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Image Section -->
        <div class="lg:col-span-5 space-y-4">
            <div class="glass-card rounded-[2.5rem] p-8 border border-slate-100 relative overflow-hidden group">
                <!-- Badge -->
                @if($record->saving_percent)
                    <div class="absolute top-6 right-6 z-10">
                        <span class="bg-red-600 text-white text-xs font-black px-4 py-2 rounded-xl shadow-xl shadow-red-200 uppercase tracking-widest">
                            Save {{ $record->saving_percent }}%
                        </span>
                    </div>
                @endif
                
                <img src="{{$record->primary_large_url}}" 
                     alt="{{ $record->product_title }}" 
                     class="w-full h-auto max-h-[500px] object-contain transform group-hover:scale-105 transition-transform duration-700">
                
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/5 to-transparent pointer-events-none"></div>
            </div>
            
            <!-- Quick Info -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-3xl border border-slate-100 text-center">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Last Updated</span>
                    <span class="text-sm font-bold text-slate-700">{{ $record->updated_at->diffForHumans() }}</span>
                </div>
                <div class="bg-white p-4 rounded-3xl border border-slate-100 text-center">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</span>
                    <span class="text-sm font-bold text-green-600 flex items-center justify-center gap-1">
                        <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                        Live Deal
                    </span>
                </div>
            </div>
        </div>

        <!-- Product Info Section -->
        <div class="lg:col-span-7 space-y-6">
            <div class="space-y-4">
                <h1 class="text-2xl md:text-4xl font-extrabold text-slate-900 leading-tight">
                    {{ $record->product_title }}
                </h1>
                
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-3xl md:text-5xl font-black text-slate-900">{{ $record->offer_price }}</span>
                        @if($record->mrp)
                            <span class="text-lg text-slate-400 line-through decoration-red-400/40 font-bold">{{ $record->mrp }}</span>
                        @endif
                    </div>
                    @if($record->saving_amount)
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-lg border border-green-200">
                            Save {{ $record->saving_amount }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- CTA Section -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 md:p-10 shadow-2xl shadow-slate-200 space-y-6">
                <div class="space-y-2">
                    <h3 class="text-white text-xl font-bold">Limited Time Offer!</h3>
                    <p class="text-slate-400 text-sm">Grab this deal before the price increases or stock runs out.</p>
                </div>
                
                <a href="{{ $record->our_link }}" target="_blank" rel="nofollow" 
                   class="flex items-center justify-center w-full py-5 bg-blue-600 hover:bg-blue-500 text-white text-lg font-black rounded-2xl transition-all duration-300 transform hover:scale-[1.02] shadow-xl shadow-blue-900/20 active:scale-95">
                    Claim Deal on Amazon
                    <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
                
                <div class="flex items-center justify-center gap-6 text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em]">
                    <span class="flex items-center gap-1"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.9L10 1.55l7.834 3.35a1 1 0 01.666.92v6.57a8 8 0 01-4.067 6.953l-4.14 2.4a.5.5 0 01-.527 0l-4.14-2.4a8 8 0 01-4.067-6.953V5.82a1 1 0 01.666-.92zM10 3.103L3.5 5.889v5.441a6.5 6.5 0 003.305 5.65L10 18.914l3.195-1.934A6.5 6.5 0 0016.5 11.33V5.89L10 3.103z" clip-rule="evenodd" /></svg> Verified Seller</span>
                    <span class="flex items-center gap-1"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" /><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" /></svg> Secure Redirect</span>
                </div>
            </div>

            <!-- Product Features -->
            @if($record->features_editor)
                <div class="space-y-4">
                    <h3 class="text-xl font-black text-slate-900 flex items-center gap-2">
                        <span class="h-8 w-1.5 bg-blue-600 rounded-full"></span>
                        Key Highlights
                    </h3>
                    <div class="prose prose-slate prose-sm max-w-none prose-ul:list-none prose-ul:p-0 prose-li:bg-white prose-li:p-4 prose-li:rounded-2xl prose-li:border prose-li:border-slate-100 prose-li:shadow-sm prose-li:mb-2 prose-li:flex prose-li:items-start prose-li:gap-3">
                        {!! $record->features_editor !!}
                    </div>
                </div>
            @endif

            <!-- Price History Chart -->
            @if(count($priceHistoryData) > 1)
                <div class="space-y-4">
                    <h3 class="text-xl font-black text-slate-900 flex items-center gap-2">
                        <span class="h-8 w-1.5 bg-purple-600 rounded-full"></span>
                        Price History
                    </h3>
                    <div class="glass-card p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                        <canvas id="priceChart" class="w-full h-64"></canvas>
                    </div>
                </div>

                @push('js')
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const ctx = document.getElementById('priceChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: @json($priceHistoryLabels),
                            datasets: [{
                                label: 'Price (INR)',
                                data: @json($priceHistoryData),
                                borderColor: '#7c3aed',
                                backgroundColor: 'rgba(124, 58, 237, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#7c3aed',
                                pointBorderColor: '#fff',
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: { 
                                    beginAtZero: false,
                                    grid: { color: 'rgba(0,0,0,0.05)' }
                                },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                </script>
                @endpush
            @endif

            <!-- Steps Box -->
            <div class="bg-blue-50 border border-blue-100 rounded-[2rem] p-8 space-y-4">
                <h3 class="text-blue-900 font-extrabold flex items-center gap-2 uppercase tracking-wider text-xs">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    How to Claim this deal
                </h3>
                <ol class="space-y-3">
                    <li class="flex items-center gap-3 text-sm font-semibold text-blue-800">
                        <span class="flex items-center justify-center h-6 w-6 rounded-full bg-blue-600 text-white text-[10px]">1</span>
                        Click the "Claim Deal" button above to go to Amazon.
                    </li>
                    <li class="flex items-center gap-3 text-sm font-semibold text-blue-800">
                        <span class="flex items-center justify-center h-6 w-6 rounded-full bg-blue-600 text-white text-[10px]">2</span>
                        Add the product to your cart and login.
                    </li>
                    <li class="flex items-center gap-3 text-sm font-semibold text-blue-800">
                        <span class="flex items-center justify-center h-6 w-6 rounded-full bg-blue-600 text-white text-[10px]">3</span>
                        Complete the checkout with your shipping details.
                    </li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Related Offers -->
    <section class="pt-12 space-y-8">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl md:text-3xl font-black text-slate-900">Recommended <span class="gradient-text">For You</span></h2>
            <a wire:navigate href="{{ route('home') }}" class="text-sm font-bold text-blue-600 hover:underline">View All Deals</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($record_latests as $latest)
                <article class="bg-white rounded-3xl p-4 border border-slate-100 deal-shadow group">
                    <div class="relative pt-[70%] bg-slate-50 rounded-2xl overflow-hidden mb-4">
                        <img src="{{$latest->primary_large_url}}" alt="{{ $latest->product_title }}" class="absolute inset-0 w-full h-full object-contain p-4 group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="space-y-3">
                        <h3 class="text-sm font-bold text-slate-800 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $latest->product_title }}</h3>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-black text-slate-900">{{ $latest->offer_price }}</span>
                            <a wire:navigate href="{{ route('details', $latest->slug ?? $latest->id) }}" class="text-xs font-bold text-blue-600 px-3 py-1.5 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">Details</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
