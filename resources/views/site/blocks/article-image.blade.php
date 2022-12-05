<x-block-layout>
    <div class="mb-8 md:mb-16 3xl:mb-20 4xl:mb-32 {{ $block->input('frame') ? 'block-image-frame' : '' }}">
        <img class="lazyload w-full" style="height:100%" src="{{ $block->lowQualityImagePlaceholder('image') }}" data-src="{{ $block->image('image') }}" alt="{{ $block->imageAltText('image') }}">
    </div>
</x-block-layout>

