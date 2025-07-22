<div class="faq-item overflow-hidden">
    <button
        class="faq-question w-full py-3 px-6 text-left flex justify-between items-center bg-[#eff0ff] transition-colors duration-200 focus:outline-none border-t-2 border-b-2 border-custom"
        onclick="toggleFaqItem(this)">
        <span class="text-indigo-800 font-medium text-lg pr-4">{{ $item['question'] }}</span>
        <div
            class="faq-icon w-7 h-7 bg-indigo-800 rounded-full flex items-center justify-center text-white flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" class="icon-plus">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="M5 12h14m-7-7v14" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                class="icon-minus hidden">
                <path fill="none" stroke="currentColor" stroke-dasharray="16" stroke-dashoffset="16"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14">
                    <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.4s" values="16;0" />
                </path>
            </svg>
        </div>
    </button>

    <div class="faq-answer max-h-0 overflow-hidden transition-all duration-500 ease-in-out bg-white">
        <div class="p-4 text-indigo-800 leading-relaxed border-b-2 border-custom">
            {!! $item['answer'] !!}
        </div>
    </div>
</div>