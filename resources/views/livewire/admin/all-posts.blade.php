<div class="p-5">
    <div class="bg-white rounded-lg border-gray-200 border p-3 relative">
        <!-- Overlay -->
        <div wire:loading.class="absolute" wire:loading.class.remove="hidden" class="hidden inset-0 z-10 bg-white bg-opacity-75 flex justify-center items-center">
            <div class="animate-spin rounded-full h-6 w-6 border-x-2 border-blue-400"></div>
        </div>
        <div class="flex items-center py-3 flex-wrap">
            <div class="mr-3 mt-4 md:mt-0">
                <input class="border border-gray-200 px-3 py-1.5 rounded-lg" wire:model.live="search" type="search" placeholder="Search..">
            </div>
            <div class="mr-3 mt-4 md:mt-0">
                <select wire:model.live="perPage" class="border border-gray-200 px-3 py-1.5 rounded-lg" name="" id="">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="All">All</option>
                </select>
            </div>
            <div class="mr:ml-auto mr-auto mt-4 md:mt-0">
                <a wire:navigate href="{{route('admin.get-prod.az')}}" class="bg-gray-800 px-6 py-2 text-gray-50 rounded-lg shadow-md">Create</a>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col" class="py-2 px-3 text-start whitespace-nowrap text-xs font-medium text-gray-500 uppercase cursor-pointer" wire:click="sortBy('id')">
                                        ID
                                        @if ($sortField == 'id')
                                        <span>{{ $sortDirection == 'asc' ? ' ▲' : ' ▼' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="py-2 px-3 text-start whitespace-nowrap text-xs font-medium text-gray-500 uppercase">Image</th>
                                    <th scope="col" class="py-2 px-3 text-start whitespace-nowrap text-xs font-medium text-gray-500 uppercase">AZ Post</th>
                                    <th scope="col" class="py-2 px-3 text-start whitespace-nowrap text-xs font-medium text-gray-500 uppercase">AZ Link</th>
                                    <th scope="col" class="py-2 px-3 text-start whitespace-nowrap text-xs font-medium text-gray-500 uppercase">Our Post</th>
                                    <th scope="col" class="py-2 px-3 text-start whitespace-nowrap text-xs font-medium text-gray-500 uppercase">Our Link</th>
                                    <th scope="col" class="py-2 px-3 text-start whitespace-nowrap text-xs font-medium text-gray-500 uppercase">Action</th>

                                    <th scope="col" class="py-2 px-3 text-start whitespace-nowrap text-xs font-medium text-gray-500 uppercase cursor-pointer" wire:click="sortBy('product_asin')">
                                        ASIN
                                        @if ($sortField == 'product_asin')
                                        <span>{{ $sortDirection == 'asc' ? ' ▲' : ' ▼' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="py-2 px-3 text-start whitespace-nowrap text-xs font-medium text-gray-500 uppercase cursor-pointer" wire:click="sortBy('updated_at')">
                                        Date
                                        @if ($sortField == 'updated_at')
                                        <span>{{ $sortDirection == 'asc' ? ' ▲' : ' ▼' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="py-2 px-3 text-start whitespace-nowrap text-xs font-medium text-gray-500 uppercase cursor-pointer" wire:click="sortBy('product_title')">
                                        Title
                                        @if ($sortField == 'product_title')
                                        <span>{{ $sortDirection == 'asc' ? ' ▲' : ' ▼' }}</span>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($records as $record)
                                <tr x-data="{
            copiedField: null,
            copyToClipboard(id) { 
                const text = document.getElementById(id).value;
                navigator.clipboard.writeText(text).then(() => { 
                    this.copiedField = id; 
                    setTimeout(() => { this.copiedField = null; }, 2000); 
                }); 
            }
        }">


                                    <td class="py-2 px-3 whitespace-nowrap text-sm font-medium text-gray-800">{{$record->id}}</td>

                                    <td class="py-2 px-3 whitespace-nowrap text-sm font-medium text-gray-800">
                                        <img src="{{$record->primary_large_url}}" alt="" class="">
                                    </td>
                                    <!-- AZ Post -->
                                    <td class="py-2 px-3 whitespace-nowrap text-sm font-medium">
                                        <input type="hidden" id="az-post-{{$record->id}}" value="{{$record->wp_post}}">
                                        <p @click="copyToClipboard('az-post-{{$record->id}}')" x-show="copiedField !== 'az-post-{{$record->id}}'" class="inline-flex cursor-pointer items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">
                                            Copy
                                        </p>
                                        <span x-show="copiedField === 'az-post-{{$record->id}}'" class="text-green-500">Copied!</span>
                                    </td>

                                    <!-- AZ Link -->
                                    <td class="py-2 px-3 whitespace-nowrap text-sm font-medium">
                                        <input type="hidden" id="az-link-{{$record->id}}" value="{{$record->detail_page_url}}">
                                        <p @click="copyToClipboard('az-link-{{$record->id}}')" x-show="copiedField !== 'az-link-{{$record->id}}'" class="inline-flex cursor-pointer items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">
                                            Copy
                                        </p>
                                        <span x-show="copiedField === 'az-link-{{$record->id}}'" class="text-green-500">Copied!</span>
                                    </td>

                                    <!-- Our Post -->
                                    <td class="py-2 px-3 whitespace-nowrap text-sm font-medium">
                                        <input type="hidden" id="our-post-{{$record->id}}" value="{{$record->our_post}}">
                                        <p @click="copyToClipboard('our-post-{{$record->id}}')" x-show="copiedField !== 'our-post-{{$record->id}}'" class="inline-flex cursor-pointer items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">
                                            Copy
                                        </p>
                                        <span x-show="copiedField === 'our-post-{{$record->id}}'" class="text-green-500">Copied!</span>
                                    </td>

                                    <!-- Our Link -->
                                    <td class="py-2 px-3 whitespace-nowrap text-sm font-medium">
                                        <input type="hidden" id="our-link-{{$record->id}}" value="{{$record->our_link}}">
                                        <p @click="copyToClipboard('our-link-{{$record->id}}')" x-show="copiedField !== 'our-link-{{$record->id}}'" class="inline-flex cursor-pointer items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">
                                            Copy
                                        </p>
                                        <span x-show="copiedField === 'our-link-{{$record->id}}'" class="text-green-500">Copied!</span>
                                    </td>

                                    <!-- Action -->
                                    <td class="py-2 text-start whitespace-nowrap text-sm font-medium">
                                        <a wire:navigate href="{{route('admin.post',$record->id)}}" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">Details</a>
                                    </td>

                                    <td class="py-2 px-3 whitespace-nowrap text-sm font-medium text-gray-800">{{$record->product_asin}}</td>
                                    <td class="py-2 px-3 whitespace-nowrap text-sm font-medium text-gray-800">{{$record->updated_at}}</td>
                                    <td class="py-2 px-3 whitespace-nowrap text-sm font-medium text-gray-800">{{\Str::limit($record->product_title,70)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mb-3">
                            @if($perPage != 'All')
                            {{ $records->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>