@php $imageUrl = $url; @endphp
<figure class="image-block group relative my-4">
    <input type="hidden" name="content[{{ $index }}][type]" value="image" class="block-type">
    <input type="hidden" name="content[{{ $index }}][url]" value="{{ $imageUrl }}" class="image-url">
    <img src="{{ $imageUrl }}" alt="" class="mx-auto max-h-[32rem] w-auto max-w-full rounded-lg">
    <button type="button" aria-label="Remove image"
            class="image-remove absolute top-2 right-2 flex items-center justify-center w-8 h-8 rounded-full bg-white/90 text-gray-700 shadow opacity-0 group-hover:opacity-100 focus:opacity-100 transition-opacity hover:text-red-600">
        <i class="ti ti-x" aria-hidden="true"></i>
    </button>
</figure>
