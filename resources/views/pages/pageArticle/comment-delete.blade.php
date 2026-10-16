@auth
    @if (auth()->id() === $comment->user_id || auth()->id() === $article->user_id)
        <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Hapus komentar ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs text-gray-400 hover:text-red-600 transition">Hapus</button>
        </form>
    @endif
@endauth
