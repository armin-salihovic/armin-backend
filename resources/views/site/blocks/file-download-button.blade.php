<x-block-layout>
    <div class="flex pb-8 md:pb-16 grid grid-cols-6 text-base md:text-lg gap-4">
        <aside class="md:col-span-2 col-span-6"></aside>
        <div class="md:col-span-4 col-span-6 pr-0 xl:pr-32">
            <a href="{{ $block->file('single_file') }}" target="_blank" rel="noreferrer" class="py-2 px-10 mr-2 mb-2 text-base font-bold focus:outline-none bg-white border border-black hover:bg-black hover:text-white focus:z-10 focus:ring-4 focus:ring-gray-200 transition ease-in-out">{{ $block->input('title') }}</a>
        </div>
    </div>
</x-block-layout>
