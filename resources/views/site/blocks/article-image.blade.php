@php
    $width = $block->imageAsArray('image')['width'];
    $height = $block->imageAsArray('image')['height'];
@endphp

<x-block-layout>
    <x-lazy-loading-wrapper class="mb-8 md:mb-16 3xl:mb-20 4xl:mb-32 {{ $block->input('frame') ? 'block-image-frame' : '' }}" :lqip="$block->lowQualityImagePlaceholder('image')">
        <img
            src="{{ $block->image('image') }}"
            alt="{{ $block->imageAltText('image') }}"
            loading="lazy"
            class="w-full h-full"
            style="aspect-ratio: {{ "$width/$height" }};"
        >
    </x-lazy-loading-wrapper>
</x-block-layout>

