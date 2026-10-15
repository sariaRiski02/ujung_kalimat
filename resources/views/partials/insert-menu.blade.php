{{-- Insert menu: "+" button beside an editor line (UI only) --}}
<div class="insert-menu absolute -left-14 top-0 z-10 hidden md:block">
    <button type="button"
            class="insert-trigger flex items-center justify-center w-8 h-8 rounded-full border border-gray-400 bg-white text-gray-500 transition duration-200 hover:border-gray-900 hover:text-gray-900"
            aria-label="Insert content" aria-expanded="false">
        <i class="ti ti-plus text-lg transition-transform duration-200" aria-hidden="true"></i>
    </button>
    
    <div class="insert-options absolute left-10 top-0 hidden items-center gap-2">
        <label title="Add an image" aria-label="Add an image"
               class="flex items-center justify-center w-8 h-8 rounded-full border border-green-600 bg-white text-green-600 transition hover:bg-green-50 cursor-pointer">
            <i class="ti ti-photo text-base" aria-hidden="true"></i>
            <input type="file" accept="image/*" class="hidden">
        </label>
    </div>
</div>
