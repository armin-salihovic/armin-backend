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
@endphp

<div class="pb-10">
    <figure class="hidden xs:block img-container mb-3 {{ $backgroundPosition }}" style="background-image:url('{{ $block->lowQualityImagePlaceholder('image') }}');">
        <img class="lazyload blur-up w-full w-full bg-center absolute {{ $objectPosition }}" src="{{ $block->image('image') }}" data-src="{{ $block->image('image') }}" alt="{{ $block->imageAltText('image') }}">
    </figure>
    <figure class="block xs:hidden img-container image-square mb-3" style="background-image:url('{{ $block->lowQualityImagePlaceholder('image', 'mobile') }}');">
        <img class="lazyload blur-up w-full w-full bg-center absolute bg-position-top" src="{{ $block->image('image', 'mobile') }}" data-src="{{ $block->image('image', 'mobile') }}" alt="{{ $block->imageAltText('image') }}">
    </figure>
</div>
