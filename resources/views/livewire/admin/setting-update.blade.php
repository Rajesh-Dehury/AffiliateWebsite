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

            <div class="grid grid-cols-8 gap-2 w-full mb-3 mt-6">
                <div class="col-span-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Advanced Tools</h3>
                    <div class="p-4 border rounded-lg bg-gray-50 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Sitemap Management</p>
                            <p class="text-xs text-gray-400">Generate a fresh sitemap for SEO.</p>
                        </div>
                        <button
                            wire:click="generateSitemap"
                            wire:loading.attr="disabled"
                            type="button"
                            class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center">
                            <span wire:loading.remove wire:target="generateSitemap">Generate Sitemap</span>
                            <span wire:loading="generateSitemap" wire:target="generateSitemap">Generating...</span>
                        </button>
                    </div>
                </div>
            </div>

            @if (session()->has('message'))
            <div class="col-span-8 text-green-500 font-medium">
                {{ session('message') }}
            </div>
            @endif

            @if (session()->has('error'))
            <div class="col-span-8 text-red-500 font-medium">
                {{ session('error') }}
            </div>
            @endif
        </div>
    </div>
</div>