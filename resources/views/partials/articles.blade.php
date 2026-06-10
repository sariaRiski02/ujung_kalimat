<article class="py-6 border-b border-gray-200">

    @foreach ($articles as $article)
        <a href="{{ route('article.show', $article->slug) }}" class="block hover:no-underline  rounded-lg p-4 transition-shadow hover:shadow-md">

            <!-- Author Row -->
            <div class="flex items-center gap-2 mb-3">
                <img
                    src="https://i.pravatar.cc/40?img=12"
                    alt=""
                    class="w-5 h-5 rounded-full object-cover"
                >
                <div class="flex items-center gap-1.5 text-sm text-gray-500 flex-wrap">
                    <span class="font-medium text-gray-900">{{ $article->user->name }}</span>
                    <span>·</span>
                    <span>{{ $article->created_at->format('d M Y') . ' · ' . $article->created_at->diffForHumans() }}</span>
                    <span>·</span>
                    <span>{{ $article->is_premium ? '⭐ ' . 'Premium' : 'Free'  }} Article</span>
                    
                </div>
            </div>

            <!-- Body -->
            <div class="flex gap-4 items-start">
                <!-- Text -->
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl font-bold text-gray-900 leading-snug mb-1.5 font-serif tracking-tight">
                        {{$article->title}}
                    </h2>

                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 mb-3">
                        {{ str($article->clean_content)->limit(100) }}
                    </p>

                    

                    
                </div>

                <!-- Thumbnail -->
                <div class="shrink-0">
                    <img
                        src="https://picsum.photos/seed/medium/300/200"
                        alt=""
                        class="w-28 h-[72px] object-cover rounded-sm"
                    >
                </div>

            </div>

            <!-- Tags + Actions -->
            <div class="mt-4 flex items-center justify-between">
                
                <!-- Stats -->
                <div class="flex items-center gap-4 mt-2.5 text-xs text-gray-400">
                    <span class="flex items-center gap-1">
                        <i class="ti ti-heart text-lg"></i> {{ $article->love }}
                    </span>
                    <span class="flex items-center gap-1">
                        <i class="ti ti-message text-lg"></i> 
                    </span>
                </div>

                <div class="flex items-center gap-4 text-gray-400">
                    <button class="hover:text-gray-900 transition-colors" aria-label="Bagikan">
                        <i class="ti ti-share text-lg"></i>
                    </button>
                    <button class="hover:text-gray-700 transition-colors">
                        <i class="ti ti-bookmark text-lg"></i>
                    </button>
                </div>
            </div>
        </a>
    @endforeach

</article>


