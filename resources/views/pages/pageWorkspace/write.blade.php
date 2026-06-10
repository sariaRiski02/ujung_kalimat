@extends('layouts.workspace')

@section('content')

    <form action="{{route('workspace.write')}}" method="POST" class=" min-h-200 transition-all duration-300 p-8 items-center"
        :class="open ? 'ml-56' : 'ml-14'">
        @csrf
        {{--  header --}}
        <div class="mb-8 flex items-center justify-between ">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-serif">New Article</h1>
            </div>

            <div class="flex items-center gap-3">

                {{-- Toggle Premium --}}
                <button
                    type="button"
                    x-data="{ premium: true }"
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
                    type="button"
                    class="flex items-center gap-2 border border-gray-300 rounded-full px-4 py-1.5 text-sm font-medium text-gray-500"
                >
                    <i class="ti ti-device-floppy" aria-hidden="true"></i>
                    Save as draft
                </button>

                {{-- Publish --}}
                <button
                    type="submit"
                    class="bg-green-600 text-white text-sm font-medium px-5 py-1.5 rounded-full"
                >
                    Publish
                </button>

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
            ></textarea>

            <!-- Content -->
            <div id="editor" class="flex flex-col">

                <textarea
                    rows="1"
                    placeholder="Tell your story..."
                    name="content[]"
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

        </div>

    </main>


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
    

  <script>
    document.addEventListener('DOMContentLoaded', () => {

        const editor = document.getElementById('editor');

        // Auto resize
        function autoResize() {
            this.style.height = '0px';
            this.style.height = this.scrollHeight + 'px';
        }

        // Pasang auto resize pada textarea yang sudah ada
        document
            .querySelectorAll('.story-block, .title-block')
            .forEach(el => {
                autoResize.call(el);
                el.addEventListener('input', autoResize);
            });

        // Enter = block baru
        editor.addEventListener('keydown', (e) => {

            if (
                e.target.classList.contains('story-block') &&
                e.key === 'Enter' &&
                !e.shiftKey
            ) {

                e.preventDefault();

                const textarea = document.createElement('textarea');

                textarea.rows = 1;

                textarea.placeholder = 'Continue..';
                textarea.name = 'content[]';

                textarea.className = `
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
                `;

                e.target.after(textarea);

                textarea.addEventListener('input', autoResize);

                textarea.focus();
            }

            if(e.target.classList.contains('story-block') && e.key === 'Backspace' && e.target.value === '') {
                const previous = e.target.previousElementSibling;

                if(previous && previous.classList.contains('story-block')) {
                    e.preventDefault();
                    previous.focus();
                    e.target.remove();
                }
            }


             if(e.target.classList.contains('title-block') && e.key === 'Enter') {
                e.preventDefault();
                const storyBlock = document.querySelector('.story-block');
                storyBlock.focus();
             }
        });

        editor.addEventListener('block-text', () => {

        })



    });

    
    document
        .getElementById('coverImage')
        .addEventListener('change', function (e) {

            const file = e.target.files[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (event) {

                const preview =
                    document.getElementById('previewImage');

                const placeholder =
                    document.getElementById('placeholderText');

                preview.src = event.target.result;

                preview.classList.remove('hidden');

                placeholder.classList.add('hidden');
            };

            reader.readAsDataURL(file);
        });

  </script>
@endsection