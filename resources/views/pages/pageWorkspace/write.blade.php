@extends('layouts.workspace')

@section('content')

    <form class=" min-h-200 transition-all duration-300 p-8 items-center"
        :class="open ? 'ml-56' : 'ml-14'">

        {{--  header --}}
        <div class="mb-8 flex items-center justify-between ">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-serif">New Article</h1>
            </div>

                                
            <div>
                <button class="bg-green-600 text-white text-base font-medium px-6 py-2 rounded-full min-w-[50px] text-center">
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
                        name="cover_image"
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