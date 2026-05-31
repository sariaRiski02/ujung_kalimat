
function openSearch() {
    const overlay = document.getElementById('searchOverlay');
    overlay.classList.add('active');
    setTimeout(() => document.getElementById('searchInput').focus(), 50);
}
function closeSearch() {
    document.getElementById('searchOverlay').classList.remove('active');
    document.getElementById('searchInput').value = '';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeSearch();
});


// Like toggle
function toggleLike(btn) {
    const isLiked = btn.classList.contains('liked');
    const countEl = btn.querySelector('.like-count');
    const count = parseInt(countEl.textContent);

    if (isLiked) {
        btn.classList.remove('liked');
        countEl.textContent = count - 1;
    } else {
        btn.classList.add('liked');
        countEl.textContent = count + 1;
    }
}

// Reply form toggle
function toggleReplyForm(btn) {
    const actionsDiv = btn.closest('div');
    const replyForm = actionsDiv.nextElementSibling;

    if (replyForm && replyForm.classList.contains('reply-form')) {
        replyForm.classList.toggle('hidden');
        if (!replyForm.classList.contains('hidden')) {
            replyForm.querySelector('textarea').focus();
        }
    }
}