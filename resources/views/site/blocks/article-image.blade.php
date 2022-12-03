<x-block-layout>
    <div class="pb-8 md:pb-16 {{ $block->input('frame') ? 'block-image-frame' : '' }}">
        <img class="lazyload w-full" src="{{ $block->lowQualityImagePlaceholder('image') }}" data-src="{{ $block->image('image') }}" alt="{{ $block->imageAltText('image') }}">
    </div>
</x-block-layout>

