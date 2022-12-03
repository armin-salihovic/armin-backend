<x-block-layout>
    <div class="flex pb-8 md:pb-16 grid grid-cols-6 text-base md:text-lg gap-4">
        <aside class="md:col-span-2 col-span-6"></aside>
        <div class="block-paragraph md:col-span-4 col-span-6 pr-0 xl:pr-32">
            <h3 class="text-xl md:text-3xl text-gray-500 pb-5">{!! $block->input('title') !!}</h3>
            {!! $block->input('paragraph') !!}
        </div>
    </div>
</x-block-layout>
