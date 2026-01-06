@props(['active' => false])

<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <button 
        @click="open = !open"
        type="button"
        {{ $attributes->merge(['class' => 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none ' . ($active ?? false ? 'border-terracotta text-charcoal' : 'border-transparent text-stone hover:text-charcoal hover:border-clay')]) }}
    >
        {{ $trigger }}
    </button>

    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-2 w-56 rounded-md shadow-lg ltr:left-0 rtl:right-0"
        style="display: none;"
    >
        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
            {{ $content }}
        </div>
    </div>
</div>
