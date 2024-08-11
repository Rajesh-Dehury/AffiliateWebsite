<div class="p-5">
    <div class="grid grid-cols-12">
        <div class="col-span-0"></div>
        <div class="col-span-12">
            <div class="grid grid-cols-12 gap-2">
                @forelse($records as $record)
                <div class="bg-white border p-1 col-span-4 rounded-xl">
                    <div class="relative">
                        <p class="text-xs bg-gray-700 text-white rounded-tl-lg absolute top-0 left-0 px-2 py-1.5">{{$record->updated_at->format('F j, Y')}}</p>
                        @if($record->saving_percent)
                        <p class="text-xs bg-red-700 text-white rounded-tr-lg absolute top-0 right-0 px-2 py-1.5">UPTO {{$record->saving_percent}} % OFF</p>
                        @endif
                        <img src="{{$record->primary_large_url}}" alt="" class="h-60 w-full rounded-lg object-contain">
                        <div class="h-24 w-full absolute bg-gradient-to-t from-gray-800 bottom-0 rounded-lg">
                            <div class="absolute bottom-2 px-3">
                                <p class="text-sm mb-1 text-white">{{\Str::limit($record->product_title,70)}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="font-bold text-green-600 hidden">Up to 90% off</p>
                    </div>
                    <div class="flex my-3">
                        <a href="{{$record->our_link}}" class="bg-green-700 hover:bg-green-600 text-white w-1/2 text-center rounded-lg py-1.5 mx-1 font-bold">Details</a>
                        <a href="{{$record->detail_page_url}}" target="_blank" class="bg-blue-500 hover:bg-blue-400 text-white w-1/2 text-center rounded-lg py-1.5 mx-1 font-bold">Check Now</a>
                    </div>
                </div>
                @empty
                <p>No Posts Found</p>
                @endforelse
            </div>
        </div>
    </div>
</div>