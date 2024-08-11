<div class="bg-white m-3 p-3 rounded-lg">
    <div class="overflow-auto">
        <div class="flex flex-col">
            <div class="p-2" x-data="{
    copiedUrl: false,
    copiedAsin: false,
    copyToClipboard(id, field) { 
        const text = document.getElementById(id).value;
        navigator.clipboard.writeText(text).then(() => { 
            if (field === 'url') {
                this.copiedUrl = true; 
                setTimeout(() => { this.copiedUrl = false; }, 2000); 
            } else if (field === 'asin') {
                this.copiedAsin = true; 
                setTimeout(() => { this.copiedAsin = false; }, 2000); 
            }
        }); 
    }
}">
                <form class="grid grid-cols-8 gap-2 w-full mb-3" wire:submit.prevent="scrape">
                    <label for="url" class="col-span-8 text-gray-700 font-medium mb-1">Enter product URL</label>
                    <input type="text" wire:model="url" wire:loading.attr="disabled" placeholder="Enter product URL" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <button wire:loading.attr="disabled" type="submit" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span wire:loading.remove>Generate</span>
                        <span wire:loading>Loading...</span>
                    </button>
                </form>
                @error('url')
                <p class="text-red-500">{{$message}}</p>
                @enderror
                @if($new_url)
                <label for="new_url" class="text-gray-700 font-medium pb-2 hidden">My URL</label>
                <div class="grid grid-cols-8 gap-2 w-full mb-3 hidden">
                    <input id="new_url" x-ref="new_url" type="text" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" wire:model="new_url" disabled readonly>
                    <button @click="copyToClipboard('new_url', 'url')" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span x-show="!copiedUrl">Copy</span>
                        <span x-show="copiedUrl" class="inline-flex items-center">
                            <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            Copied!
                        </span>
                    </button>
                </div>
                @endif
                @if($asin)
                <label for="asin" class="text-gray-700 font-medium pb-2 block">Product ID</label>
                <div class="grid grid-cols-8 gap-2 w-full mb-3">
                    <input id="asin" x-ref="asin" type="text" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" wire:model="asin" disabled readonly>
                    <button @click="copyToClipboard('asin', 'asin')" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span x-show="!copiedAsin">Copy</span>
                        <span x-show="copiedAsin" class="inline-flex items-center">
                            <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            Copied!
                        </span>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>