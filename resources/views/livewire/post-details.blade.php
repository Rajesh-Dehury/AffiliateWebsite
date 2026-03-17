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

<div class="p-5">
    <div class="grid grid-cols-12">
        <!-- Main Content -->
        <div class="max-w-3xl mx-auto col-span-12 md:col-span-8 rounded-lg">
            <!-- Navigation Buttons -->
            <div class="flex justify-between mb-3">
                <a wire:navigate href="{{ url()->previous() }}" class="inline-block bg-blue-400 text-white px-3 py-1.5 rounded-lg shadow-lg hover:shadow-none" aria-label="Go back to the previous page">BACK</a>
                <a wire:navigate href="{{ route('home') }}" class="inline-block bg-yellow-400 text-black px-3 py-1.5 rounded-lg shadow-lg hover:shadow-none" aria-label="Go to the home page">HOME</a>
            </div>

            <!-- Product Image and Labels -->
            <article class="relative bg-white rounded-lg shadow-lg">
                <p class="text-xs bg-gray-700 text-white rounded-tl-lg absolute top-0 left-0 px-2 py-1.5" aria-label="Last updated on {{$record->updated_at->format('F j, Y')}}">
                    {{$record->updated_at->format('F j, Y')}}
                </p>
                @if($record->saving_percent)
                <p class="text-xs bg-red-700 text-white rounded-tr-lg absolute top-0 right-0 px-2 py-1.5" aria-label="Up to {{$record->saving_percent}} percent off">UP TO {{$record->saving_percent}}% OFF</p>
                @endif
                <img src="{{$record->primary_large_url}}" alt="{{ $record->product_title }}" class="h-80 w-full rounded-lg object-contain" loading="lazy">
                <div class="h-40 w-full bg-gradient-to-t from-gray-800 bottom-0 rounded-lg">
                    <div class="absolute bottom-2 px-3">
                        <h1 class="text-lg md:text-2xl font-bold text-white">{{ $record->product_title }}</h1>
                    </div>
                </div>
            </article>

            <!-- Steps to Get the Deal -->
            <section class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg mt-3">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Follow these simple steps to get the deal:</h2>
                <ol class="list-decimal list-inside space-y-4 text-gray-800">
                    <li class="flex items-center">
                        <p class="text-sm md:text-lg font-semibold mr-5">Visit the Offer Page:</p>
                        <a href="https://example.com" target="_blank" class="inline-block text-sm md:text-lg bg-blue-400 text-white px-3 py-1.5 rounded-lg shadow-lg hover:shadow-none" rel="nofollow noopener noreferrer">Click Here</a>
                    </li>
                    <li class="flex items-center">
                        <p class="text-sm md:text-lg font-semibold">Select the Product you want to buy.</p>
                    </li>
                    <li class="flex items-center">
                        <p class="text-sm md:text-lg font-semibold">Add the product to your cart.</p>
                    </li>
                    <li class="flex items-center">
                        <p class="text-sm md:text-lg font-semibold">Click on the "Proceed to Checkout" option.</p>
                    </li>
                    <li class="flex items-center">
                        <p class="text-sm md:text-lg font-semibold">Log in to your Amazon account or create a new one.</p>
                    </li>
                    <li class="flex items-center">
                        <p class="text-sm md:text-lg font-semibold">Enter your shipping address and other details.</p>
                    </li>
                    <li class="flex items-center">
                        <p class="text-sm md:text-lg font-semibold">Continue to the payment page and complete the transaction.</p>
                    </li>
                </ol>
            </section>

            <!-- Additional Details -->
            @if($record->features_editor)
            <section class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg mt-3">
                <h2 class="text-xl font-bold">Product Details</h2>
                <p class="text-sm">{!! $record->features_editor !!}</p>
            </section>
            @endif

            <!-- Disclaimer -->
            <section class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg mt-3 relative bg-yellow-200">
                <p class="mb-3 text-sm font-bold absolute top-0 left-0 px-3 py-1.5 bg-red-600 text-white rounded-tl-lg">NOTE</p>
                <p class="text-sm mt-3 font-mono text-yellow-950">Product prices and availability are accurate as of the date/time indicated and are subject to change. Any price and availability information displayed on Amazon India at the time of purchase will apply to the purchase of this product.</p>
            </section>
        </div>

        <!-- Other Offers Section -->
        <aside class="col-span-12 md:col-span-4">
            <div class="flex justify-between">
                <h2 class="text-2xl mb-4 mt-4 md:mt-0">Other Offers</h2>
            </div>

            @forelse($record_latests as $record)
            <article class="bg-white col-span-12 md:col-span-4 rounded-xl shadow-lg mb-3">
                <div class="relative">
                    <p class="text-xs bg-gray-700 text-white rounded-tl-lg absolute top-0 left-0 px-2 py-1.5" aria-label="Last updated {{$record->updated_at->diffForHumans()}}">
                        {{$record->updated_at->diffForHumans()}}
                    </p>
                    @if($record->saving_percent)
                    <p class="text-xs bg-red-700 text-white rounded-tr-lg absolute top-0 right-0 px-2 py-1.5" aria-label="Up to {{$record->saving_percent}} percent off">UP TO {{$record->saving_percent}}% OFF</p>
                    @endif
                    <img src="{{$record->primary_large_url}}" alt="{{ \Str::limit($record->product_title, 70) }}" class="h-60 w-full rounded-lg object-contain" loading="lazy">
                    <div class="h-24 w-full bg-gradient-to-t from-gray-800 bottom-0 rounded-t-lg">
                        <div class="absolute bottom-2 px-3">
                            <h3 class="text-sm text-white">{{ \Str::limit($record->product_title, 70) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2">
                    <a wire:navigate href="{{route('details',$record->id)}}" class="col-span-1 text-center py-3 bg-blue-600 rounded-bl-lg text-white font-semibold">Details</a>
                    <a href="{{$record->our_link}}" target="_blank" class="col-span-1 text-center py-3 bg-gray-50 rounded-br-lg font-semibold" rel="nofollow noopener noreferrer">Check Now</a>
                </div>
            </article>
            @empty
            <p>No Posts Found</p>
            @endforelse
        </aside>
    </div>
</div>