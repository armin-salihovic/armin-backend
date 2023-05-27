<x-block-layout>
    <div class="flex mb-8 md:mb-16 3xl:mb-20 4xl:mb-32 grid grid-cols-6 text-base md:text-lg 3xl:text-2xl 4xl:text-3xl gap-4 3xl:gap-5 4xl:gap-8">
        <aside class="md:col-span-2 col-span-6"></aside>
        <div class="md:col-span-4 col-span-6 pr-0 xl:pr-32 3xl:pr-40 4xl:pr-64">
            <a href="/cv" target="_blank" rel="noreferrer" class="py-2 3xl:py-2.5 4xl:py-4 px-10 3xl:px-12 4xl:px-20 mr-2 3xl:mr-2.5 4xl:mr-4 mb-2 3xl:mb-2.5 4xl:mb-4 text-base font-bold focus:outline-none bg-white border border-black hover:bg-black hover:text-white focus:z-10 focus:ring-4 focus:ring-gray-200 transition ease-in-out">{{ $block->input('title') }}</a>
        </div>
    </div>
</x-block-layout>
