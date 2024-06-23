@php
    $objectPosition = '';
    $backgroundPosition = '';
    switch ($block->input('position')) {
        case 1:
            $objectPosition = 'object-position-top';
            $backgroundPosition = 'bg-position-top';
            break;
        case 2:
            $objectPosition = 'object-position-center';
            $backgroundPosition = 'bg-position-center';
            break;
        case 3:
            $objectPosition = 'object-position-bottom';
            $backgroundPosition = 'bg-position-bottom';
            break;
    }

    $width = $block->imageAsArray('image')['width'];
    $height = $block->imageAsArray('image')['height'];
@endphp

<div class="mb-10 3xl:mb-12 4xl:mb-20">
    <div class="hidden xs:block mb-3 3xl:mb-4 4xl:mb-6">
        <x-lazy-loading-wrapper class="{{ $backgroundPosition }}" :lqip="$block->lowQualityImagePlaceholder('image')">
            <img
                src="{{ $block->image('image') }}"
                alt="{{ $block->imageAltText('image') }}"
                loading="lazy"
                class="hero-img bg-center {{ $objectPosition }}"
                style="aspect-ratio: {{ "$width/$height" }};"
            >
        </x-lazy-loading-wrapper>
    </div>
    <div class="block xs:hidden image-square mb-3">
        <x-lazy-loading-wrapper :lqip="$block->lowQualityImagePlaceholder('image', 'mobile')">
            <img
                src="{{ $block->image('image', 'mobile') }}"
                alt="{{ $block->image('image', 'mobile') }}"
                style="aspect-ratio: 1/1;"
                loading="lazy"
                class="hero-img bg-center bg-position-top"
            >
        </x-lazy-loading-wrapper>
    </div>
</div>
