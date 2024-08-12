<div class="p-5">
    <div class="grid grid-cols-12">
        <div class="max-w-3xl mx-auto col-span-12 md:col-span-8 rounded-lg">
            <div class="flex justify-between">
                <a wire:navigate href="{{ url()->previous() }}" class="mb-3 inline-block bg-blue-400 text-white px-3 py-1.5 rounded-lg shadow-lg hover:shadow-none">BACK</a>
                <a wire:navigate href="{{ route('home') }}" class="mb-3 inline-block bg-yellow-400 text-black px-3 py-1.5 rounded-lg shadow-lg hover:shadow-none">HOME</a>
            </div>
            <div class="relative bg-white rounded-lg">
                <p class="text-xs bg-gray-700 text-white rounded-tl-lg absolute top-0 left-0 px-2 py-1.5">{{$record->updated_at->format('F j, Y')}}</p>
                @if($record->saving_percent)
                <p class="text-xs bg-red-700 text-white rounded-tr-lg absolute top-0 right-0 px-2 py-1.5">UPTO {{$record->saving_percent}} % OFF</p>
                @endif
                <img src="{{$record->primary_large_url}}" alt="" class="h-80 w-full rounded-lg object-contain">
                <div class="h-40 w-full bg-gradient-to-t from-gray-800 bottom-0 rounded-lg">
                    <div class="absolute bottom-2 px-3">
                        <p class="mb-1 text-white text-sl md:text-2xl">{{$record->product_title}}</p>
                    </div>
                </div>
            </div>
            <div class="max-w-3xl mx-auto p-6 bg-gray-100 shadow-md rounded-lg mt-3">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Follow these simple steps to get the deal:</h1>
                <ol class="list-decimal list-inside space-y-4 text-gray-800">
                    <li class="flex items-center">
                        <p class="text-sm md:text-lg font-semibold mr-5">Visit the Offer Page:</p>
                        <a href="https://example.com" target="_blank" class="inline-block text-sm md:text-lg bg-blue-400 text-white px-3 py-1.5 rounded-lg shadow-lg hover:shadow-none">Click Here</a>
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
            </div>
            @if($record->features_editor)
            <div class="max-w-3xl mx-auto p-6 bg-gray-100 shadow-md rounded-lg mt-3">
                <p class="mb-3 text-xl font-bold">Details</p>
                <p class="text-sm">{!!$record->features_editor!!}</p>
            </div>
            @endif
            <div class="max-w-3xl mx-auto p-6 bg-gray-100 shadow-md rounded-lg mt-3 relative bg-yellow-200">
                <p class="mb-3 text-sm font-bold absolute top-0 left-0 px-3 py-1.5 bg-red-600 text-white rounded-tl-lg">NOTE</p>
                <p class="text-sm mt-3 font-mono text-yellow-950">Product prices and availability are accurate as of the date/time indicated and are subject to change. Any price and availability information displayed on Amazon India at the time of purchase will apply to the purchase of this product.</p>
            </div>
        </div>
        <div class="col-span-12 md:col-span-4">
            <div class="flex justify-between">
                <p class="text-2xl mb-4 mt-4 md:mt-0">Other offers</p>
            </div>
            @forelse($record_latests as $record)
            <div class="bg-white border p-1 col-span-12 md:col-span-4 rounded-xl mb-3">
                <div class="relative">
                    <p class="text-xs bg-gray-700 text-white rounded-tl-lg absolute top-0 left-0 px-2 py-1.5">{{$record->updated_at->format('F j, Y')}}</p>
                    @if($record->saving_percent)
                    <p class="text-xs bg-red-700 text-white rounded-tr-lg absolute top-0 right-0 px-2 py-1.5">UPTO {{$record->saving_percent}} % OFF</p>
                    @endif
                    <img src="{{$record->primary_large_url}}" alt="" class="h-60 w-full rounded-lg object-contain">
                    <div class="h-24 w-full bg-gradient-to-t from-gray-800 bottom-0 rounded-lg">
                        <div class="absolute bottom-2 px-3">
                            <p class="text-sm mb-1 text-white">{{\Str::limit($record->product_title,70)}}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-bold text-green-600 hidden">Up to 90% off</p>
                </div>
                <div class="flex my-2">
                    <a wire:navigate href="{{route('details',$record->id)}}" class="bg-green-700 hover:bg-green-600 text-white w-1/2 text-center rounded-lg py-1.5 mx-1 font-bold">Details</a>
                    <a href="{{$record->our_link}}" target="_blank" class="bg-blue-500 hover:bg-blue-400 text-white w-1/2 text-center rounded-lg py-1.5 mx-1 font-bold">Check Now</a>
                </div>
            </div>
            @empty
            <p>No Posts Found</p>
            @endforelse
        </div>
    </div>
</div>