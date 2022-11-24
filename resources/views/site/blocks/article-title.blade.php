<x-block-layout>
    <div class="title flex grid grid-cols-6">
        <aside class="md:col-span-2 col-span-6">
            <h3 class="text-lg md:text-5xl">{!! $block->input('year') !!}</h3>
        </aside>
        <div class="md:col-span-4 col-span-6 pr-0 xl:pr-32">
            <h1 class="text-3xl md:text-5xl">{{ $block->input('title') }}</h1>
            <h2 class="text-2xl md:text-4xl text-gray-500">{!! $block->input('subtitle') !!}</h2>
        </div>
    </div>
</x-block-layout>

