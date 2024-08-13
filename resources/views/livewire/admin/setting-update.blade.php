<div class="bg-white m-3 p-3 rounded-lg">
    <div class="overflow-auto">
        <div class="flex flex-col">
            <div class="grid grid-cols-8 gap-2 w-full mb-3">
                <label for="wp_link" class="col-span-8 text-gray-700 font-medium mb-1">WhatsApp Link</label>
                <input
                    type="text"
                    id="wp_link"
                    wire:model="wp_link"
                    wire:loading.attr="disabled"
                    placeholder="Enter WhatsApp link"
                    class="col-span-4 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <button
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50"
                    wire:click="updateWpLink"
                    type="button"
                    class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                    <span wire:loading.remove wire:target="updateWpLink">Update</span>
                    <span wire:loading="updateWpLink" wire:target="updateWpLink">Loading...</span>
                </button>
            </div>

            <div class="grid grid-cols-8 gap-2 w-full mb-3">
                <label for="tele_link" class="col-span-8 text-gray-700 font-medium mb-1">Telegram Link</label>
                <input
                    type="text"
                    id="tele_link"
                    wire:model="tele_link"
                    wire:loading.attr="disabled"
                    placeholder="Enter Telegram link"
                    class="col-span-4 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <button
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50"
                    wire:click="updateTeleLink"
                    type="button"
                    class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                    <span wire:loading.remove wire:target="updateTeleLink">Update</span>
                    <span wire:loading="updateTeleLink" wire:target="updateTeleLink">Loading...</span>
                </button>
            </div>

            @if (session()->has('message'))
            <div class="col-span-8 text-green-500 font-medium">
                {{ session('message') }}
            </div>
            @endif
        </div>
    </div>
</div>