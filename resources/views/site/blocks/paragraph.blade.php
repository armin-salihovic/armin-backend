<x-block-layout>
    <div class="flex mb-8 md:mb-16 3xl:mb-20 4xl:mb-32 grid grid-cols-6 text-base md:text-lg 3xl:text-2xl 4xl:text-3xl gap-4 3xl:gap-5 4xl:gap-8">
        <aside class="md:col-span-2 col-span-6">
            <h4 class="font-bold">{!! $block->input('title') !!}</h4>
        </aside>
        <div class="block-paragraph md:col-span-4 col-span-6 pr-0 xl:pr-32 3xl:pr-40 4xl:pr-64">
            {!! $block->input('paragraph') !!}
        </div>
    </div>
</x-block-layout>

