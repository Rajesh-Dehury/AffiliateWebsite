<div class="bg-white m-3 p-3 rounded-lg">
    <div class="overflow-auto">
        <div class="flex flex-col">
            <div x-data="{
                copiedField: null,
                copyToClipboard(id) { 
                    const text = document.getElementById(id).value;
                    navigator.clipboard.writeText(text).then(() => { 
                        this.copiedField = id; 
                        setTimeout(() => { this.copiedField = null; }, 2000); 
                    }); 
                }
            }">
                <div class="flex justify-between">
                    <a href="{{route('admin.all.posts')}}" class="mb-3 inline-block bg-blue-400 text-white px-3 py-1.5 rounded-lg shadow-lg hover:shadow-none">Back</a>
                </div>
                <div>
                    <img src="{{$primary_large_url}}" alt="" class="h-32">
                </div>
                <div class="grid grid-cols-8 gap-2 w-full mb-3">
                    <label for="wp_post" class="col-span-8 text-gray-700 font-medium pb-2">WP Post</label>
                    <textarea wire:model="wp_post" id="wp_post" type="text" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly></textarea>
                    <button @click="copyToClipboard('wp_post')" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span x-show="copiedField !== 'wp_post'">Copy</span>
                        <span x-show="copiedField === 'wp_post'" class="inline-flex items-center">
                            <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            Copied!
                        </span>
                    </button>

                    <label for="our_post" class="col-span-8 text-gray-700 font-medium pb-2">Our Post</label>
                    <textarea wire:model="our_post" id="our_post" type="text" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly></textarea>
                    <button @click="copyToClipboard('our_post')" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span x-show="copiedField !== 'our_post'">Copy</span>
                        <span x-show="copiedField === 'our_post'" class="inline-flex items-center">
                            <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            Copied!
                        </span>
                    </button>

                    <label for="our_link" class="col-span-8 text-gray-700 font-medium pb-2">Our Link</label>
                    <input wire:model="our_link" id="our_link" type="text" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    <button @click="copyToClipboard('our_link')" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span x-show="copiedField !== 'our_link'">Copy</span>
                        <span x-show="copiedField === 'our_link'" class="inline-flex items-center">
                            <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            Copied!
                        </span>
                    </button>

                    <label for="product_asin" class="col-span-8 text-gray-700 font-medium pb-2">ASIN</label>
                    <input wire:model="product_asin" id="product_asin" type="text" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    <button @click="copyToClipboard('product_asin')" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span x-show="copiedField !== 'product_asin'">Copy</span>
                        <span x-show="copiedField === 'product_asin'" class="inline-flex items-center">
                            <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            Copied!
                        </span>
                    </button>

                    <label for="detail_page_url" class="col-span-8 text-gray-700 font-medium pb-2">Detail Page URL</label>
                    <input wire:model="detail_page_url" id="detail_page_url" type="text" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    <button @click="copyToClipboard('detail_page_url')" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span x-show="copiedField !== 'detail_page_url'">Copy</span>
                        <span x-show="copiedField === 'detail_page_url'" class="inline-flex items-center">
                            <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            Copied!
                        </span>
                    </button>

                    <label for="primary_large_url" class="col-span-8 text-gray-700 font-medium pb-2">Primary -> Large</label>
                    <input wire:model="primary_large_url" id="primary_large_url" type="text" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    <button @click="copyToClipboard('primary_large_url')" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span x-show="copiedField !== 'primary_large_url'">Copy</span>
                        <span x-show="copiedField === 'primary_large_url'" class="inline-flex items-center">
                            <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            Copied!
                        </span>
                    </button>

                    <label for="product_title" class="col-span-8 text-gray-700 font-medium pb-2">Title</label>
                    <input wire:model="product_title" id="product_title" type="text" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    <button @click="copyToClipboard('product_title')" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span x-show="copiedField !== 'product_title'">Copy</span>
                        <span x-show="copiedField === 'product_title'" class="inline-flex items-center">
                            <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            Copied!
                        </span>
                    </button>

                    <label for="mrp" class="col-span-8 text-gray-700 font-medium pb-2">MRP</label>
                    <input wire:model="mrp" id="mrp" type="text" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    <button @click="copyToClipboard('mrp')" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span x-show="copiedField !== 'mrp'">Copy</span>
                        <span x-show="copiedField === 'mrp'" class="inline-flex items-center">
                            <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            Copied!
                        </span>
                    </button>

                    <label for="offer_price" class="col-span-8 text-gray-700 font-medium pb-2">Offer Price</label>
                    <input wire:model="offer_price" id="offer_price" type="text" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    <button @click="copyToClipboard('offer_price')" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span x-show="copiedField !== 'offer_price'">Copy</span>
                        <span x-show="copiedField === 'offer_price'" class="inline-flex items-center">
                            <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            Copied!
                        </span>
                    </button>

                    <label for="saving_percent" class="col-span-8 text-gray-700 font-medium pb-2">Saving %</label>
                    <input wire:model="saving_percent" id="saving_percent" type="text" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    <button @click="copyToClipboard('saving_percent')" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span x-show="copiedField !== 'saving_percent'">Copy</span>
                        <span x-show="copiedField === 'saving_percent'" class="inline-flex items-center">
                            <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            Copied!
                        </span>
                    </button>

                    <label for="saving_amount" class="col-span-8 text-gray-700 font-medium pb-2">Saving Amount</label>
                    <input wire:model="saving_amount" id="saving_amount" type="text" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    <button @click="copyToClipboard('saving_amount')" class="col-span-2 md:col-span-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto py-2.5 text-center items-center inline-flex justify-center">
                        <span x-show="copiedField !== 'saving_amount'">Copy</span>
                        <span x-show="copiedField === 'saving_amount'" class="inline-flex items-center">
                            <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            Copied!
                        </span>
                    </button>

                    <div class="col-span-8" wire:ignore>
                        <label for="features_editor" class="col-span-8 text-gray-700 font-medium pb-2">Features</label>
                        <textarea wire:model="features_editor" id="features_editor" class="col-span-6 md:col-span-7 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>