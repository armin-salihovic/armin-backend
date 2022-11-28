<x-block-layout>
    <div class="flex pb-16 grid grid-cols-6 text-base md:text-lg gap-4">
        <aside class="md:col-span-2 col-span-6">
            <h4 class="font-bold">{!! $block->input('title') !!}</h4>
        </aside>
        <div class="block-paragraph md:col-span-4 col-span-6 flex flex-col lg:flex-row gap-10">
            <div class="w-full lg:w-1/2">
                <div class="w-full">{!! $block->input('paragraph1') !!}</div>
            </div>
            <div class="w-full lg:w-1/2">
                <div class="w-full">{!! $block->input('paragraph2') !!}</div>
            </div>
        </div>
    </div>
</x-block-layout>

