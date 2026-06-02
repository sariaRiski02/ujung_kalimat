<section class="mt-20 font-sans">
    <h2 class="text-sm uppercase tracking-widest text-gray-500 mb-10">Komentar (3)</h2>

    <!-- Form Komentar Baru -->
    <div class="mb-14">
        <textarea
            rows="3"
            placeholder="Tulis komentar..."
            class="w-full border-b border-black/20 focus:border-black outline-none resize-none text-base text-black placeholder:text-gray-400 pb-2 bg-transparent transition-colors font-sans"
        ></textarea>
        <div class="flex justify-end mt-3">
            <button class="text-xs uppercase tracking-widest bg-black text-white px-6 py-2 rounded-full hover:bg-gray-800 transition">
                Kirim
            </button>
        </div>
    </div>

    <!-- Daftar Komentar -->
    <div class="space-y-10" id="comments-list">

        <!-- Komentar 1 -->
        <div class="comment-item">
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 rounded-full bg-black text-white text-xs flex items-center justify-center font-medium flex-shrink-0 mt-0.5">A</div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm font-medium text-black">Andi</span>
                        <span class="text-xs text-gray-400">26 Mei 2026</span>
                    </div>
                    <p class="text-base text-gray-700 leading-relaxed">
                        Tulisan yang sangat relevan. Saya sendiri sering kehilangan fokus hanya karena notifikasi yang tidak penting.
                    </p>
                    <!-- Aksi -->
                    <div class="flex items-center gap-4 mt-3">
                        <button onclick="toggleLike(this)" class="like-btn flex items-center gap-1.5 text-xs text-gray-400 hover:text-black transition group">
                            <svg class="w-3.5 h-3.5 stroke-current fill-none group-[.liked]:fill-black group-[.liked]:stroke-black transition" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            <span class="like-count">4</span>
                        </button>
                        <button onclick="toggleReplyForm(this)" class="text-xs text-gray-400 hover:text-black transition">
                            Balas
                        </button>
                    </div>

                    <!-- Form Reply (tersembunyi) -->
                    <div class="reply-form hidden mt-4">
                        <textarea
                            rows="2"
                            placeholder="Balas komentar Andi..."
                            class="w-full border-b border-black/20 focus:border-black outline-none resize-none text-sm text-black placeholder:text-gray-400 pb-2 bg-transparent transition-colors"
                        ></textarea>
                        <div class="flex justify-end gap-3 mt-2">
                            <button onclick="toggleReplyForm(this.closest('.reply-form').previousElementSibling.querySelector('[onclick]'))" class="text-xs text-gray-400 hover:text-black transition">
                                Batal
                            </button>
                            <button class="text-xs uppercase tracking-widest bg-black text-white px-5 py-1.5 rounded-full hover:bg-gray-800 transition">
                                Kirim
                            </button>
                        </div>
                    </div>

                    <!-- Replies -->
                    <div class="mt-6 space-y-6 pl-4 border-l border-black/10">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 text-xs flex items-center justify-center font-medium flex-shrink-0 mt-0.5">L</div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-medium text-black">Lamda</span>
                                    <span class="text-xs text-gray-400">26 Mei 2026</span>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed">Terima kasih Andi, semoga tulisan ini bisa sedikit membantu!</p>
                                <div class="flex items-center gap-4 mt-2">
                                    <button onclick="toggleLike(this)" class="like-btn flex items-center gap-1.5 text-xs text-gray-400 hover:text-black transition group">
                                        <svg class="w-3 h-3 stroke-current fill-none group-[.liked]:fill-black group-[.liked]:stroke-black transition" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                        </svg>
                                        <span class="like-count">1</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Komentar 2 -->
        <div class="comment-item">
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 rounded-full bg-black text-white text-xs flex items-center justify-center font-medium flex-shrink-0 mt-0.5">S</div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm font-medium text-black">Sari</span>
                        <span class="text-xs text-gray-400">27 Mei 2026</span>
                    </div>
                    <p class="text-base text-gray-700 leading-relaxed">
                        "Keheningan yang disengaja" — frasa ini langsung mengingatkan saya pada konsep deep work-nya Cal Newport.
                    </p>
                    <div class="flex items-center gap-4 mt-3">
                        <button onclick="toggleLike(this)" class="like-btn flex items-center gap-1.5 text-xs text-gray-400 hover:text-black transition group">
                            <svg class="w-3.5 h-3.5 stroke-current fill-none group-[.liked]:fill-black group-[.liked]:stroke-black transition" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            <span class="like-count">7</span>
                        </button>
                        <button onclick="toggleReplyForm(this)" class="text-xs text-gray-400 hover:text-black transition">
                            Balas
                        </button>
                    </div>
                    <div class="reply-form hidden mt-4">
                        <textarea
                            rows="2"
                            placeholder="Balas komentar Sari..."
                            class="w-full border-b border-black/20 focus:border-black outline-none resize-none text-sm text-black placeholder:text-gray-400 pb-2 bg-transparent transition-colors"
                        ></textarea>
                        <div class="flex justify-end gap-3 mt-2">
                            <button onclick="toggleReplyForm(this.closest('.reply-form').previousElementSibling.querySelector('[onclick]'))" class="text-xs text-gray-400 hover:text-black transition">
                                Batal
                            </button>
                            <button class="text-xs uppercase tracking-widest bg-black text-white px-5 py-1.5 rounded-full hover:bg-gray-800 transition">
                                Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Komentar 3 -->
        <div class="comment-item">
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 rounded-full bg-black text-white text-xs flex items-center justify-center font-medium flex-shrink-0 mt-0.5">R</div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm font-medium text-black">Reza</span>
                        <span class="text-xs text-gray-400">28 Mei 2026</span>
                    </div>
                    <p class="text-base text-gray-700 leading-relaxed">
                        Setuju. Tapi tantangannya justru di sana — membangun kebiasaan hening di tengah dunia yang by design memang tidak mau kita diam.
                    </p>
                    <div class="flex items-center gap-4 mt-3">
                        <button onclick="toggleLike(this)" class="like-btn flex items-center gap-1.5 text-xs text-gray-400 hover:text-black transition group">
                            <svg class="w-3.5 h-3.5 stroke-current fill-none group-[.liked]:fill-black group-[.liked]:stroke-black transition" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            <span class="like-count">2</span>
                        </button>
                        <button onclick="toggleReplyForm(this)" class="text-xs text-gray-400 hover:text-black transition">
                            Balas
                        </button>
                    </div>
                    <div class="reply-form hidden mt-4">
                        <textarea
                            rows="2"
                            placeholder="Balas komentar Reza..."
                            class="w-full border-b border-black/20 focus:border-black outline-none resize-none text-sm text-black placeholder:text-gray-400 pb-2 bg-transparent transition-colors"
                        ></textarea>
                        <div class="flex justify-end gap-3 mt-2">
                            <button onclick="toggleReplyForm(this.closest('.reply-form').previousElementSibling.querySelector('[onclick]'))" class="text-xs text-gray-400 hover:text-black transition">
                                Batal
                            </button>
                            <button class="text-xs uppercase tracking-widest bg-black text-white px-5 py-1.5 rounded-full hover:bg-gray-800 transition">
                                Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>