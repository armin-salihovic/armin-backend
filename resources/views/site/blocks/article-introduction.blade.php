<x-block-layout>
    <div class="flex mb-8 md:mb-16 3xl:mb-20 grid grid-cols-6 text-base md:text-lg 3xl:text-2xl gap-4 3xl:gap-5">
        <aside class="md:col-span-2 col-span-6">
            <h4 class="font-bold">{{ $block->input('title') }}</h4>
            @foreach($block->blockable->technologies as $technology)
                <h4 class="text-gray-500">
                    {{ $technology->title }}
                </h4>
            @endforeach
        </aside>
        <div class="block-paragraph md:col-span-4 col-span-6 pr-0 xl:pr-32 3xl:pr-40">
            {!! $block->input('paragraph') !!}
        </div>
    </div>
</x-block-layout>

