<div>
    <!-- Analytics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
        <!-- Total Views Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Total Views</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalViews) }}</p>
        </div>

        <!-- Total Clicks Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Total Clicks (Outbound)</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalClicks) }}</p>
        </div>
        
        <!-- Conversion Rate Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Click-Through Rate (CTR)</h3>
            <p class="text-3xl font-bold text-gray-800">
                {{ $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 2) : 0 }}%
            </p>
        </div>
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
