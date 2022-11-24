<x-block-layout>
    <div class="flex py-16 grid grid-cols-6 text-base md:text-lg gap-4">
        <aside class="md:col-span-2 col-span-6">
            <h4 class="font-bold">Introduction</h4>
            @foreach($block->blockable->technologies as $technology)
                <h4 class="text-gray-500">
                    {{ $technology->title }}
                </h4>
            @endforeach
        </aside>
        <div class="block-paragraph md:col-span-4 col-span-6 pr-0 xl:pr-32">
            {!! $block->input('paragraph') !!}
        </div>
    </div>
</x-block-layout>

