<div class="category-section mb-2">
    <div class="text-center mb-4 mt-4 relative">
        <div class="flex items-center justify-center">
            <div class="flex-grow h-px bg-indigo-800 mr-4"></div>
            <div class="relative">
                <h1 class="text-xl font-bold text-indigo-800 inline-block">{{ $category['title'] }}</h1>
            </div>
            <div class="flex-grow h-px bg-indigo-800 ml-4"></div>
        </div>
    </div>

    <div class="space-y-4">
        @foreach ($category['items'] as $item)
            @include('layouts.faq.accordion-item', ['item' => $item])
        @endforeach
    </div>
</div>
