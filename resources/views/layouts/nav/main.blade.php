<div class="flex justify-between items-center px-8 py-5">
    <a href="{{ $backUrl ?? '#' }}" class="text-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
    </a>
    <div class="flex space-x-2">
        <a href="https://www.facebook.com/profile.php?id=61577920375564" target="_blank" class="block">
          <img src="{{ asset('images/facebook.png') }}" alt="Facebook" class="w-8 h-8 object-contain">
        </a>
        <a href="https://lin.ee/ZLnJCpu" target="_blank" class="block">
          <img src="{{ asset('images/line.png') }}" alt="Line" class="w-8 h-8 object-contain">
        </a>
    </div>
</div>