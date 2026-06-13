
document.addEventListener('DOMContentLoaded', () => {

const editor = document.getElementById('editor');

// Auto resize
function autoResize() {
    this.style.height = '0px';
    this.style.height = this.scrollHeight + 'px';
}

const quoteClasses = ['border-l-4', 'border-gray-300', 'pl-4', 'italic', 'text-gray-700'];

// === BARU: reindex semua wrapper biar name="content[N][...]" selalu urut ===
function reindexBlocks() {
    const wrappers = editor.querySelectorAll('.story-wrapper');

    wrappers.forEach((wrapper, index) => {
        wrapper.dataset.index = index;

        const typeInput = wrapper.querySelector('.block-type');
        const textarea = wrapper.querySelector('.story-block');

        typeInput.name = `content[${index}][type]`;
        textarea.name = `content[${index}][text]`;
    });
}

// Toggle blockquote style untuk satu wrapper
function toggleQuote(button) {
    const wrapper = button.closest('.story-wrapper');
    const textarea = wrapper.querySelector('.story-block');
    const typeInput = wrapper.querySelector('.block-type'); // BARU

    const isActive = button.classList.contains('active');

    if (isActive) {
        button.classList.remove('active', 'text-gray-700');
        button.classList.add('text-gray-400');
        wrapper.classList.remove(...quoteClasses);
        textarea.classList.remove('italic');
        typeInput.value = 'paragraph'; // BARU
    } else {
        button.classList.add('active', 'text-gray-700');
        button.classList.remove('text-gray-400');
        wrapper.classList.add(...quoteClasses);
        textarea.classList.add('italic');
        typeInput.value = 'quote'; // BARU
    }
}

// Buat wrapper baru (hidden input + button + textarea)
function createBlock(placeholder = 'Continue..') {

    const wrapper = document.createElement('div');
    wrapper.className = 'story-wrapper group relative flex items-start gap-2';

    // BARU: hidden input untuk type
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.className = 'block-type';
    typeInput.value = 'paragraph';

    const button = document.createElement('button');
    button.type = 'button';
    button.innerHTML = '&ldquo;';
    button.className = `
        quote-toggle
        text-4xl
        font-serif
        text-gray-400
        leading-tight
        opacity-0
        group-hover:opacity-100
        group-focus-within:opacity-100
        transition-opacity
        duration-200
        hover:text-gray-600
    `;

    const textarea = document.createElement('textarea');
    textarea.rows = 1;
    textarea.placeholder = placeholder;
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

    wrapper.appendChild(typeInput); // BARU
    wrapper.appendChild(button);
    wrapper.appendChild(textarea);

    textarea.addEventListener('input', autoResize);
    button.addEventListener('click', () => toggleQuote(button));

    return { wrapper, textarea, button };
}

// Pasang auto resize & toggle pada block yang sudah ada
document
    .querySelectorAll('.story-wrapper')
    .forEach(wrapper => {
        const textarea = wrapper.querySelector('.story-block');
        const button = wrapper.querySelector('.quote-toggle');

        autoResize.call(textarea);
        textarea.addEventListener('input', autoResize);
        button.addEventListener('click', () => toggleQuote(button));
    });
reindexBlocks();
// Pasang auto resize untuk title-block
document
    .querySelectorAll('.title-block')
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

        const currentWrapper = e.target.closest('.story-wrapper');
        const { wrapper, textarea } = createBlock();

        currentWrapper.after(wrapper);
        reindexBlocks(); // BARU

        textarea.focus();
    }

    if (
        e.target.classList.contains('story-block') &&
        e.key === 'Backspace' &&
        e.target.value === ''
    ) {
        const currentWrapper = e.target.closest('.story-wrapper');
        const previousWrapper = currentWrapper.previousElementSibling;

        if (previousWrapper && previousWrapper.classList.contains('story-wrapper')) {
            e.preventDefault();
            const previousTextarea = previousWrapper.querySelector('.story-block');
                previousTextarea.focus();
                currentWrapper.remove();
                reindexBlocks(); // BARU
            }
        }

        if (e.target.classList.contains('title-block') && e.key === 'Enter') {
            e.preventDefault();
            const storyBlock = document.querySelector('.story-block');
            storyBlock.focus();
        }
    });

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

