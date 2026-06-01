<div>
    <!-- Analytics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4">
        <!-- Total Views Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Total Views</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalViews) }}</p>
        </div>

        <!-- Total Clicks Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Total Clicks</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalClicks) }}</p>
        </div>
        
        <!-- Total Subscribers Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Subscribers</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalSubscribers) }}</p>
        </div>

        <!-- Conversion Rate Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Click-Through Rate (CTR)</h3>
            <p class="text-3xl font-bold text-gray-800">
                {{ $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 2) : 0 }}%
            </p>
        </div>
    </div>

    <!-- Telegram Quick Post -->
    <div class="bg-white p-6 mx-4 mt-4 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4 text-gray-800 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161c-.18.717-.962 4.084-1.362 5.441-.168.57-.493.763-.693.781-.444.039-.781-.295-1.211-.577-.673-.441-1.053-.716-1.706-1.146-.755-.498-.266-.771.165-1.218.113-.117 2.071-1.899 2.109-2.06.005-.021.009-.098-.037-.139s-.114-.027-.163-.015c-.07.016-1.186.753-3.345 2.211-.316.216-.602.322-.857.317-.282-.006-.823-.153-1.226-.284-.494-.161-.887-.246-.853-.52.017-.143.214-.29.59-.441 2.305-1.003 3.842-1.664 4.61-1.983 2.193-.907 2.648-1.065 2.946-1.07.065-.001.212.016.307.093.08.066.102.155.107.224.007.106.003.22-.002.329z"/>
            </svg>
            Telegram Quick Post
        </h2>
        
        @if (session()->has('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="postToTelegram">
            <div class="mb-4">
                <textarea 
                    wire:model="telegramMessage" 
                    rows="4" 
                    class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500 @error('telegramMessage') border-red-500 @enderror" 
                    placeholder="Type your message here (HTML supported)..."></textarea>
                @error('telegramMessage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end">
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none transition-colors flex items-center"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Post to Telegram</span>
                    <span wire:loading>
                        <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Posting...
                    </span>
                </button>
            </div>
        </form>
    </div>

    <!-- Top Performing Deals Table -->
    <div class="bg-white p-6 mx-4 mt-4 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Top Performing Deals</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Clicks</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">CTR</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topDeals as $deal)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <img src="{{ $deal->primary_large_url }}" alt="" class="h-8 w-8 object-contain mr-3">
                                <span title="{{ $deal->product_title }}">{{ \Str::limit($deal->product_title, 40) }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center text-gray-900">{{ $deal->offer_price }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center text-gray-600">{{ $deal->views_count }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center text-green-600 font-bold">{{ $deal->clicks_count }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center font-medium">
                            {{ $deal->views_count > 0 ? round(($deal->clicks_count / $deal->views_count) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No data available yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Existing Weekly Post Chart -->
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12">
            <livewire:admin.weekly-post-chart />
        </div>
    </div>
</div>
