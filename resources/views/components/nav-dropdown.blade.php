@props([
'title'=>'Pages',
'active'=>false,
'url'=>null,
])
<div x-data="{dropdown:false}" x-on:click="dropdown=!dropdown">
    <a class="flex justify-between items-center px-4 py-2 {{$active?'bg-gray-700 text-white':'text-gray-700 dark:text-white'}} rounded-md mb-2 hover:bg-gray-700 hover:text-white" href="#">
        <div class="flex items-center">
            {{$icon}}

            <span class="mx-4 font-medium">{{$title}}</span>
        </div>

        <svg xmlns="http://www.w3.org/2000/svg" x-show="!dropdown" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" x-cloak x-show="dropdown" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
        </svg>
    </a>
    <div class="bg-gray-100 dark:bg-gray-800 shadow-inner dark:shadow-gray-700 block rounded-md" x-cloak x-show="dropdown" x-transition:enter="transition ease duration-500" x-transition:enter-start="scale-0" x-transition:enter-end="scale-100" x-transition:leave="transition ease duration-300" x-transition:leave-start="scale-100" x-transition:leave-end="scale-0">
        {{$slot}}
    </div>
</div>