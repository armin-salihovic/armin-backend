<div class="flex py-16 grid grid-cols-6 text-base md:text-lg gap-4">
    <aside class="md:col-span-2 col-span-6">
        <h4 class="font-bold">{!! $block->input('title') !!}</h4>
    </aside>
    <div class="block-paragraph md:col-span-4 col-span-6 pr-0 xl:pr-32">
        {!! $block->input('paragraph') !!}
    </div>
</div>
