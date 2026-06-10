<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Judul Artikel Anda | Ujung Kalimat</title>
</head>
<body class="bg-white text-black font-serif antialiased leading-relaxed">

    @yield('content')
    
    <script>
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
    </script>
    
</body>
</html>