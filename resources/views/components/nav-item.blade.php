@props([
'title'=>'Pages',
'url'=>'#',
'active'=>false,
])
<div>
    <a wire:navigate href="{{$url}}" class="flex items-center px-4 py-2 {{$active?'bg-gray-700 text-white':'text-gray-700 dark:text-white'}} rounded-md mb-2 hover:bg-gray-700 hover:text-white">
        {{ $icon }}

        <span class="mx-4 font-medium">{{$title}}</span>
    </a>
</div>