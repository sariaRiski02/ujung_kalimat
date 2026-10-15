
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
    const wrappers = editor.querySelectorAll('.story-wrapper, .image-block');

    wrappers.forEach((wrapper, index) => {
        wrapper.dataset.index = index;

        wrapper.querySelector('.block-type').name = `content[${index}][type]`;

        const textarea = wrapper.querySelector('.story-block');
        const imageFile = wrapper.querySelector('.image-file');
        const imageUrl = wrapper.querySelector('.image-url');

        if (textarea) textarea.name = `content[${index}][text]`;
        if (imageFile) imageFile.name = `content[${index}][image]`;
        if (imageUrl) imageUrl.name = `content[${index}][url]`;
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

    const insertMenuTemplate = document.getElementById('insert-menu-template');
    if (insertMenuTemplate) {
        wrapper.appendChild(insertMenuTemplate.content.cloneNode(true));
    }

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

// Insert menu ("+" beside a line): UI only for now
function closeInsertMenus(except = null) {
    editor.querySelectorAll('.insert-menu.is-open').forEach(menu => {
        if (menu === except) return;
        menu.classList.remove('is-open');
        menu.querySelector('.insert-trigger').setAttribute('aria-expanded', 'false');
    });
}

editor.addEventListener('click', (e) => {
    const trigger = e.target.closest('.insert-trigger');

    if (!trigger) return;

    const menu = trigger.closest('.insert-menu');
    const isOpen = menu.classList.toggle('is-open');

    trigger.setAttribute('aria-expanded', String(isOpen));
    closeInsertMenus(menu);
});

document.addEventListener('click', (e) => {
    if (!e.target.closest('.insert-menu')) closeInsertMenus();
});

editor.addEventListener('input', () => closeInsertMenus());

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeInsertMenus();
});

// Inline image: show the chosen image inside the editor
function createImageBlock(file) {
    const figure = document.createElement('figure');
    figure.className = 'image-block group relative my-4';

    const image = document.createElement('img');
    image.src = URL.createObjectURL(file);
    image.alt = file.name;
    image.className = 'mx-auto max-h-[32rem] w-auto max-w-full rounded-lg';
    image.addEventListener('load', () => URL.revokeObjectURL(image.src), { once: true });

    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.setAttribute('aria-label', 'Remove image');
    removeButton.className = `
        image-remove
        absolute
        top-2
        right-2
        flex
        items-center
        justify-center
        w-8
        h-8
        rounded-full
        bg-white/90
        text-gray-700
        shadow
        opacity-0
        group-hover:opacity-100
        focus:opacity-100
        transition-opacity
        hover:text-red-600
    `;
    removeButton.innerHTML = '<i class="ti ti-x" aria-hidden="true"></i>';

    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.className = 'block-type';
    typeInput.value = 'image';

    // The original input is cleared after selection, so the file travels in its own input
    const fileInput = document.createElement('input');
    const transfer = new DataTransfer();
    transfer.items.add(file);
    fileInput.type = 'file';
    fileInput.className = 'image-file hidden';
    fileInput.files = transfer.files;

    figure.append(typeInput, fileInput, image, removeButton);

    return figure;
}

editor.addEventListener('change', (e) => {
    const input = e.target;

    if (!input.matches('.insert-menu input[type="file"]')) return;

    const file = input.files[0];

    input.value = '';

    if (!file || !file.type.startsWith('image/')) return;

    const currentWrapper = input.closest('.story-wrapper');
    const currentTextarea = currentWrapper.querySelector('.story-block');
    const figure = createImageBlock(file);

    closeInsertMenus();

    if (currentTextarea.value.trim() === '') {
        // An empty line stays below the image so the writer can keep typing
        currentWrapper.before(figure);
        reindexBlocks();
        currentTextarea.focus();
        return;
    }

    const { wrapper, textarea } = createBlock();

    currentWrapper.after(figure);
    figure.after(wrapper);
    reindexBlocks();
    textarea.focus();
});

editor.addEventListener('click', (e) => {
    const removeButton = e.target.closest('.image-remove');

    if (removeButton) {
        removeButton.closest('.image-block').remove();
        reindexBlocks();
    }
});

// Arrow key navigation between the title and story blocks
const mirrorProps = [
    'boxSizing', 'width', 'fontFamily', 'fontSize', 'fontWeight', 'fontStyle',
    'letterSpacing', 'wordSpacing', 'textTransform', 'textIndent', 'lineHeight',
    'paddingTop', 'paddingRight', 'paddingBottom', 'paddingLeft',
    'borderTopWidth', 'borderRightWidth', 'borderBottomWidth', 'borderLeftWidth',
];

// Tell whether the caret sits on the first / last visual line of a textarea
function caretLinePosition(textarea) {
    const style = getComputedStyle(textarea);
    const mirror = document.createElement('div');

    mirrorProps.forEach(prop => { mirror.style[prop] = style[prop]; });
    Object.assign(mirror.style, {
        position: 'absolute',
        visibility: 'hidden',
        top: '0',
        left: '-9999px',
        whiteSpace: 'pre-wrap',
        overflowWrap: 'break-word',
    });

    const caret = textarea.selectionStart;
    const marker = document.createElement('span');
    marker.textContent = '\u200b';

    mirror.append(
        document.createTextNode(textarea.value.substring(0, caret)),
        marker,
        document.createTextNode(textarea.value.substring(caret)),
    );
    document.body.appendChild(mirror);

    const fontSize = parseFloat(style.fontSize);
    const lineHeight = parseFloat(style.lineHeight) || fontSize * 1.2;
    const paddingTop = parseFloat(style.paddingTop) || 0;
    const paddingBottom = parseFloat(style.paddingBottom) || 0;
    const markerTop = marker.offsetTop - paddingTop;
    const contentHeight = mirror.scrollHeight - paddingTop - paddingBottom;

    mirror.remove();

    return {
        isFirstLine: markerTop < lineHeight / 2,
        isLastLine: contentHeight - (markerTop + lineHeight) < lineHeight / 2,
    };
}

function focusBlock(target, atEnd) {
    const position = atEnd ? target.value.length : 0;

    target.focus();
    target.setSelectionRange(position, position);
}

document.addEventListener('keydown', (e) => {
    const field = e.target;

    if (
        !(field instanceof HTMLTextAreaElement) ||
        !(field.classList.contains('title-block') || field.classList.contains('story-block')) ||
        e.shiftKey || e.ctrlKey || e.altKey || e.metaKey || e.isComposing
    ) {
        return;
    }

    const fields = [...document.querySelectorAll('.title-block, .story-block')];
    const index = fields.indexOf(field);
    const hasCollapsedCaret = field.selectionStart === field.selectionEnd;
    let target = null;
    let atEnd = false;

    if (e.key === 'ArrowUp' && caretLinePosition(field).isFirstLine) {
        target = fields[index - 1];
        atEnd = true;
    } else if (e.key === 'ArrowDown' && caretLinePosition(field).isLastLine) {
        target = fields[index + 1];
    } else if (e.key === 'ArrowLeft' && hasCollapsedCaret && field.selectionStart === 0) {
        target = fields[index - 1];
        atEnd = true;
    } else if (e.key === 'ArrowRight' && hasCollapsedCaret && field.selectionEnd === field.value.length) {
        target = fields[index + 1];
    }

    if (target) {
        e.preventDefault();
        focusBlock(target, atEnd);
    }
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

        if (previousWrapper && previousWrapper.classList.contains('image-block')) {
            e.preventDefault();
            previousWrapper.remove();
            reindexBlocks();
        } else if (previousWrapper && previousWrapper.classList.contains('story-wrapper')) {
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


const coverImageInput = document.getElementById('coverImage');

if (coverImageInput) {
    coverImageInput.addEventListener('change', function (e) {

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
}
