@extends('layouts.workspace')

@section('content')

    <form action="{{route('workspace.write')}}" method="POST" class=" min-h-200 transition-all duration-300 p-8 items-center"
        :class="open ? 'ml-56' : 'ml-14'" enctype="multipart/form-data">
        @csrf
        {{--  header --}}
        <div class="mb-8 flex items-center justify-between ">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-serif">New Article</h1>
            </div>

            <div class="flex items-center gap-3" 
                x-data="{status: 'draft'}">

                {{-- Toggle Premium --}}
                <button
                    type="button"
                    x-data="{ premium: false }"
                    @click="premium = !premium"
                    class="flex items-center gap-2 bg-gray-100 border border-gray-200 rounded-full px-4 py-1.5 text-sm font-medium cursor-pointer"
                    :class="premium ? 'text-amber-600' : 'text-gray-500'"
                >
                    <i class="ti" :class="premium ? 'ti-lock' : 'ti-lock-open'" aria-hidden="true"></i>
                    <span x-text="premium ? 'Premium' : 'Free'"></span>

                    <input type="hidden" name="is_premium" :value="premium ? 1 : 0">
                </button>

                {{-- Save as Draft --}}
                <button
                    type="submit"
                    @click="status = 'draft'"
                    class="flex items-center gap-2 border border-gray-300 rounded-full px-4 py-1.5 text-sm font-medium text-gray-500"
                >
                    <i class="ti ti-device-floppy" aria-hidden="true"></i>
                    Save as draft
                </button>

                {{-- Publish --}}
                <button
                    type="submit"
                    class="bg-green-600 text-white text-sm font-medium px-5 py-1.5 rounded-full"
                    @click="status = 'published'"
                >
                    Publish
                </button>

                <input type="hidden" name="status" :value="status">
            </div>

        </div>

        <div class="max-w-3xl px-6 mx-auto">

            <div class="mb-8">
                <label
                    class="flex items-center justify-center w-full h-80 border-2 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 overflow-hidden">

                    <input
                        id="coverImage"
                        type="file"
                        name="image"
                        accept="image/*"
                        class="hidden">

                    <img
                        id="previewImage"
                        class="hidden w-full h-full object-cover"
                        alt="Preview">

                    <span
                        id="placeholderText"
                        class="text-gray-500">
                        Click to Upload Cover Image
                    </span>

                </label>

            </div>
            
            <!-- Title -->
            <textarea
                rows="1"
                placeholder="Title"
                name="title"
                class="
                    title-block
                    w-full
                    resize-none
                    overflow-hidden
                    border-none
                    outline-none
                    text-4xl
                    font-serif
                    font-bold
                    placeholder-gray-400
                    leading-tight
                    mb-8
                "
            >{{old('title')}}</textarea>

            <!-- Content -->
            @php
                $oldContent = old('content', []);
            @endphp

            <!-- Content -->
            <div id="editor" class="flex flex-col">

                @if(count($oldContent) > 0)

                    @foreach($oldContent as $index => $block)
                        <div class="story-wrapper group relative flex items-start gap-2 {{ ($block['type'] ?? 'paragraph') === 'quote' ? 'border-l-4 border-gray-300 pl-4' : '' }}" data-index="{{ $index }}">

                            <input type="hidden" name="content[{{ $index }}][type]" value="{{ $block['type'] ?? 'paragraph' }}" class="block-type">

                            <button
                                type="button"
                                class="quote-toggle text-4xl font-serif leading-tight opacity-0 group-hover:opacity-100 group-focus-within:opacity-100 transition-opacity duration-200 hover:text-gray-600 {{ ($block['type'] ?? 'paragraph') === 'quote' ? 'active text-gray-700' : 'text-gray-400' }}"
                            >
                                &ldquo;
                            </button>

                            <textarea
                                rows="1"
                                placeholder="Tell your story..."
                                name="content[{{ $index }}][text]"
                                class="
                                    story-block
                                    w-full
                                    resize-none
                                    overflow-hidden
                                    border-none
                                    outline-none
                                    text-xl
                                    leading-relaxed
                                    font-serif
                                    placeholder-gray-400
                                    mb-5
                                    {{ ($block['type'] ?? 'paragraph') === 'quote' ? 'italic text-gray-700' : '' }}
                                "
                            >{{ $block['text'] ?? '' }}</textarea>
                        </div>
                    @endforeach

                @else

                    <div class="story-wrapper group relative flex items-start gap-2" data-index="0">
                        <input type="hidden" name="content[0][type]" value="paragraph" class="block-type">

                        <button
                            type="button"
                            class="quote-toggle text-4xl font-serif text-gray-400 leading-tight opacity-0 group-hover:opacity-100 group-focus-within:opacity-100 transition-opacity duration-200 hover:text-gray-600"
                        >
                            &ldquo;
                        </button>

                        <textarea
                            rows="1"
                            placeholder="Tell your story..."
                            name="content[0][text]"
                            class="
                                story-block
                                w-full
                                resize-none
                                overflow-hidden
                                border-none
                                outline-none
                                text-xl
                                leading-relaxed
                                font-serif
                                placeholder-gray-400
                                mb-5
                            "
                        ></textarea>
                    </div>

                @endif

            </div>

        </div>

    </form>


    <div
        x-data="{ openModal: {{ $errors->any() ? 'true' : 'false' }} }"
        x-show="openModal"
        x-transition.opacity
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-2xl border border-gray-100 p-6 w-[420px] max-w-[90%]">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="ti ti-alert-circle text-red-500 text-lg" aria-hidden="true"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Ada beberapa kesalahan</span>
                </div>
                <button type="button" @click="openModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="ti ti-x text-lg" aria-hidden="true"></i>
                </button>
            </div>

            <hr class="border-gray-100 mb-4">

            {{-- Error list --}}
            <ul class="flex flex-col gap-2">
                @foreach ($errors->all() as $error)
                    <li class="flex items-start gap-2 bg-red-50 rounded-lg px-3 py-2.5">
                        <i class="ti ti-point-filled text-red-400 text-sm mt-0.5 shrink-0" aria-hidden="true"></i>
                        <span class="text-sm text-red-600">{{ $error }}</span>
                    </li>
                @endforeach
            </ul>

        </div>
    </div>
    

    <script></script>
  <script src="{{asset('js/write.js')}}"></script>
@endsection