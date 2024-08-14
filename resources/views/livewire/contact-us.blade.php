<div class="bg-gray-200 text-gray-800 font-sans leading-relaxed">
    <section class="max-w-4xl mx-auto p-8">
        <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-center text-blue-600">Contact Us</h2>
        <p class="mb-8 lg:mb-16 font-light text-center text-gray-500 sm:text-xl">
            Got a technical issue? Want to send feedback about a beta feature? Need details about our Business plan? Let us know.
        </p>

        @if (session()->has('success'))
        <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-8">
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Your email</label>
                <input type="email" id="email" wire:model="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('email') border-red-500 @enderror" placeholder="name@flowbite.com">
                @error('email')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="subject" class="block mb-2 text-sm font-medium text-gray-900">Subject</label>
                <input type="text" id="subject" wire:model="subject" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('subject') border-red-500 @enderror" placeholder="Let us know how we can help you">
                @error('subject')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="sm:col-span-2">
                <label for="message" class="block mb-2 text-sm font-medium text-gray-900">Your message</label>
                <textarea id="message" wire:model="message" rows="6" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg shadow-sm border border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('message') border-red-500 @enderror" placeholder="Leave a comment..."></textarea>
                @error('message')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="py-3 px-5 text-sm font-medium text-center text-white rounded-lg bg-blue-700 sm:w-fit hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">Send message</button>
        </form>
    </section>
</div>