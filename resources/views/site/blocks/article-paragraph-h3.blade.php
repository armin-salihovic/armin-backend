<x-block-layout>
    <div class="flex mb-8 md:mb-16 3xl:mb-20 grid grid-cols-6 text-base md:text-lg 3xl:text-2xl gap-4 3xl:gap-5">
        <aside class="md:col-span-2 col-span-6"></aside>
        <div class="block-paragraph md:col-span-4 col-span-6 pr-0 xl:pr-32 3xl:pr-40">
            <h3 class="text-xl md:text-3xl text-gray-500 pb-5">{!! $block->input('title') !!}</h3>
            {!! $block->input('paragraph') !!}
        </div>
    </div>
</x-block-layout>
