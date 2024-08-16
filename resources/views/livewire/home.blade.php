<div x-data="{ isOpenFilter: false }" class="p-5">
    <div class="flex justify-end mb-3 space-x-2">
        <input wire:model.live="search" type="text" class="w-full md:hidden py-2 px-4 text-gray-700 bg-white border border-blue-500 rounded-lg dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 focus:ring-blue-300" placeholder="Search">
        <div class="block md:hidden">
            <!-- Filter Icon for Small Devices -->
            <button @click="isOpenFilter = true" class="text-blue-500 bg-white p-3 rounded-lg shadow border border-blue-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-5 5a1 1 0 00-.293.707v4.586a1 1 0 01-.293.707l-2 2A1 1 0 0110 21v-6.586a1 1 0 00-.293-.707l-5-5A1 1 0 014 6V4z" />
                </svg>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-12">
        <!-- Filter Menu -->
        <div x-cloak :class="{'translate-x-0': isOpenFilter, 'translate-x-full': !isOpenFilter}" class="col-span-3 md:col-span-3 fixed inset-y-0 right-0 bg-white z-50 px-5 transition-transform duration-300 transform md:translate-x-0 md:relative md:z-auto md:bg-transparent">
            <div :class="{'shadow-lg': !isOpenFilter}" class="p-5 bg-white rounded-lg">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-2xl font-semibold">Filters</p>
                    <button @click="isOpenFilter = false" :class="{'hidden': !isOpenFilter}" class="text-red-500">Close</button>
                </div>
                <div :class="{'hidden': isOpenFilter}" class="mb-3">
                    <input wire:model.live="search" type="text" class="w-full py-2 px-4 text-gray-700 bg-white border border-blue-500 rounded-lg dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 focus:ring-blue-300" placeholder="Search">
                </div>
                <div class="mb-3">
                    <div class="mb-2">
                        <label for="">Date From</label>
                        <input wire:model.live="date_from" type="date" class="w-full py-2 px-4 text-gray-700 bg-white border border-blue-500 rounded-lg dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 focus:ring-blue-300">
                    </div>
                    <div>
                        <label for="">Date To</label>
                        <input wire:model.live="date_to" type="date" class="w-full py-2 px-4 text-gray-700 bg-white border border-blue-500 rounded-lg dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 focus:ring-blue-300">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="">Discount ({{$disc}}%)</label>
                    <input wire:model.live="disc" type="range" class="w-full">
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-span-12 md:col-span-9">
            <div class="grid grid-cols-12 gap-4">
                @forelse($records as $record)
                <div class="bg-white col-span-12 md:col-span-4 rounded-xl shadow-lg">
                    <div class="relative">
                        <p class="text-xs bg-gray-700 text-white rounded-tl-lg absolute top-0 left-0 px-2 py-1.5" wire:ignore>{{$record->updated_at->diffForHumans();}}</p>
                        @if($record->saving_percent)
                        <p class="text-xs bg-red-700 text-white rounded-tr-lg absolute top-0 right-0 px-2 py-1.5">UPTO {{$record->saving_percent}} % OFF</p>
                        @endif
                        <img src="{{$record->primary_large_url}}" alt="" class="h-60 w-full rounded-lg object-contain">
                        <div class="h-24 w-full bg-gradient-to-t from-gray-800 bottom-0 rounded-t-lg">
                            <div class="absolute bottom-2 px-3">
                                <p class="text-sm mb-1 text-white">{{\Str::limit($record->product_title,70)}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="font-bold text-green-600 hidden">Up to 90% off</p>
                    </div>
                    <div class="grid grid-cols-2">
                        <a wire:navigate href="{{route('details',$record->id)}}" class="col-span-1 text-center py-3 bg-blue-600 rounded-bl-lg text-white font-semibold">Details</a>
                        <a href="{{$record->our_link}}" target="_blank" class="col-span-1 text-center py-3 bg-gray-50 rounded-br-lg font-semibold">Check Now</a>
                    </div>
                </div>
                @empty
                <p>No Posts Found</p>
                @endforelse
                @if($records->hasMorePages())
                <div wire:loading wire:target="loadMore" class="bg-white border p-1 col-span-12 md:col-span-4 rounded-xl animate-pulse">
                    <div class="relative">
                        <div class="bg-gray-300 h-4 w-20 absolute top-0 left-0 rounded-tl-lg"></div>
                        <div class="bg-gray-300 h-4 w-24 absolute top-0 right-0 rounded-tr-lg"></div>
                        <div class="bg-gray-300 h-60 w-full rounded-lg"></div>
                        <div class="bg-gray-400 h-24 w-full rounded-lg mt-2"></div>
                    </div>
                    <div class="mt-3 bg-gray-300 h-6 w-1/2 rounded-lg"></div>
                    <div class="flex my-2 space-x-2">
                        <div class="bg-gray-300 h-8 w-1/2 rounded-lg"></div>
                        <div class="bg-gray-300 h-8 w-1/2 rounded-lg"></div>
                    </div>
                </div>
                <div wire:loading wire:target="loadMore" class="bg-white border p-1 col-span-12 md:col-span-4 rounded-xl animate-pulse">
                    <div class="relative">
                        <div class="bg-gray-300 h-4 w-20 absolute top-0 left-0 rounded-tl-lg"></div>
                        <div class="bg-gray-300 h-4 w-24 absolute top-0 right-0 rounded-tr-lg"></div>
                        <div class="bg-gray-300 h-60 w-full rounded-lg"></div>
                        <div class="bg-gray-400 h-24 w-full rounded-lg mt-2"></div>
                    </div>
                    <div class="mt-3 bg-gray-300 h-6 w-1/2 rounded-lg"></div>
                    <div class="flex my-2 space-x-2">
                        <div class="bg-gray-300 h-8 w-1/2 rounded-lg"></div>
                        <div class="bg-gray-300 h-8 w-1/2 rounded-lg"></div>
                    </div>
                </div>
                <div wire:loading wire:target="loadMore" class="bg-white border p-1 col-span-12 md:col-span-4 rounded-xl animate-pulse">
                    <div class="relative">
                        <div class="bg-gray-300 h-4 w-20 absolute top-0 left-0 rounded-tl-lg"></div>
                        <div class="bg-gray-300 h-4 w-24 absolute top-0 right-0 rounded-tr-lg"></div>
                        <div class="bg-gray-300 h-60 w-full rounded-lg"></div>
                        <div class="bg-gray-400 h-24 w-full rounded-lg mt-2"></div>
                    </div>
                    <div class="mt-3 bg-gray-300 h-6 w-1/2 rounded-lg"></div>
                    <div class="flex my-2 space-x-2">
                        <div class="bg-gray-300 h-8 w-1/2 rounded-lg"></div>
                        <div class="bg-gray-300 h-8 w-1/2 rounded-lg"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('scroll', function() {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
                @this.call('loadMore');
            }
        });
    </script>
</div>