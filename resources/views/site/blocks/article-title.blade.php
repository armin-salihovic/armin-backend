<x-block-layout>
    <div class="title mb-8 md:mb-16 3xl:mb-20 flex grid grid-cols-6">
        <aside class="md:col-span-2 col-span-6">
            <h3 class="text-lg md:text-5xl 3xl:text-6xl">{!! $block->input('year') !!}</h3>
        </aside>
        <div class="md:col-span-4 col-span-6 pr-0 xl:pr-32 3xl:pr-40">
            <h1 class="text-3xl md:text-5xl 3xl:text-6xl">{{ $block->input('title') }}</h1>
            <h2 class="text-2xl md:text-4xl 3xl:text-5xl text-gray-500">{!! $block->input('subtitle') !!}</h2>
        </div>
    </div>
</x-block-layout>

