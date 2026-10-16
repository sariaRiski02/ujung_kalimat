<section class="mt-20 font-sans" id="comments">
    <h2 class="text-sm uppercase tracking-widest text-gray-500 mb-10">Komentar ({{ $commentsCount }})</h2>

    {{-- Form Komentar Baru --}}
    @auth
        <form method="POST" action="{{ route('comments.store', $article) }}" class="mb-14">
            @csrf
            <textarea
                name="body"
                rows="3"
                maxlength="2000"
                required
                placeholder="Tulis komentar..."
                class="w-full border-b border-black/20 focus:border-black outline-none resize-none text-base text-black placeholder:text-gray-400 pb-2 bg-transparent transition-colors font-sans"
            >{{ old('body') }}</textarea>
            @error('body')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
            <div class="flex justify-end mt-3">
                <button type="submit" class="text-xs uppercase tracking-widest bg-black text-white px-6 py-2 rounded-full hover:bg-gray-800 transition">
                    Kirim
                </button>
            </div>
        </form>
    @else
        <p class="mb-14 text-sm text-gray-500">
            <a href="{{ route('signin') }}" class="text-black underline underline-offset-2">Masuk</a> untuk menulis komentar.
        </p>
    @endauth

    {{-- Daftar Komentar --}}
    <div class="space-y-10" id="comments-list">
        @forelse ($comments as $comment)
            <div class="comment-item" id="comment-{{ $comment->id }}" x-data="{ replying: false }">
                <div class="flex items-start gap-3">
                    <x-user-avatar :user="$comment->user" size="w-7 h-7" text="text-xs" class="mt-0.5" />
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-medium text-black">{{ $comment->user->name }}</span>
                            <span class="text-xs text-gray-400" title="{{ $comment->created_at }}">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-base text-gray-700 leading-relaxed whitespace-pre-line break-words">{{ $comment->body }}</p>

                        <div class="flex items-center gap-4 mt-3">
                            @auth
                                <button type="button" @click="replying = ! replying; $nextTick(() => replying && $refs.reply.focus())" class="text-xs text-gray-400 hover:text-black transition">
                                    Balas
                                </button>
                            @endauth
                            @include('pages.pageArticle.comment-delete', ['comment' => $comment])
                        </div>

                        @auth
                            <form method="POST" action="{{ route('comments.store', $article) }}" x-show="replying" x-cloak class="mt-4">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <textarea
                                    x-ref="reply"
                                    name="body"
                                    rows="2"
                                    maxlength="2000"
                                    required
                                    placeholder="Balas komentar {{ $comment->user->name }}..."
                                    class="w-full border-b border-black/20 focus:border-black outline-none resize-none text-sm text-black placeholder:text-gray-400 pb-2 bg-transparent transition-colors"
                                ></textarea>
                                <div class="flex justify-end gap-3 mt-2">
                                    <button type="button" @click="replying = false" class="text-xs text-gray-400 hover:text-black transition">
                                        Batal
                                    </button>
                                    <button type="submit" class="text-xs uppercase tracking-widest bg-black text-white px-5 py-1.5 rounded-full hover:bg-gray-800 transition">
                                        Kirim
                                    </button>
                                </div>
                            </form>
                        @endauth

                        {{-- Replies --}}
                        @if ($comment->replies->isNotEmpty())
                            <div class="mt-6 space-y-6 pl-4 border-l border-black/10">
                                @foreach ($comment->replies as $reply)
                                    <div class="flex items-start gap-3" id="comment-{{ $reply->id }}">
                                        <x-user-avatar :user="$reply->user" size="w-6 h-6" text="text-xs" class="mt-0.5" />
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-sm font-medium text-black">{{ $reply->user->name }}</span>
                                                <span class="text-xs text-gray-400" title="{{ $reply->created_at }}">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line break-words">{{ $reply->body }}</p>
                                            <div class="flex items-center gap-4 mt-2">
                                                @include('pages.pageArticle.comment-delete', ['comment' => $reply])
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400">Belum ada komentar.</p>
        @endforelse
    </div>
</section>
