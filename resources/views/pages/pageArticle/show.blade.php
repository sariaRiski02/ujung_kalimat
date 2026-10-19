@extends('layouts.article')

@section('content')
<main class="max-w-[680px] mx-auto px-6 py-10">

    {{-- Back --}}
    <a href="/"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 transition-colors mb-10 font-sans">
        <i class="ti ti-arrow-left text-sm"></i>
        Kembali ke Beranda
    </a>

    {{-- Header --}}
    <header class="mb-8">
        <h1 class="text-4xl md:text-[42px] font-bold leading-tight tracking-tight text-gray-900 mb-5 font-serif">
            {{$article->title}}
        </h1>
        
        {{-- Author meta --}}
        <div class="flex items-center gap-3 mb-6 font-sans">
            <x-user-avatar :user="$article->user" size="w-10 h-10" />
            <div>
                <p class="text-sm font-medium text-gray-900">{{$article->user->name}}</p>
                <div class="flex items-center gap-1.5 text-sm text-gray-500">
                    <span>{{$article->created_at->format('d M Y')}}</span>
                    
                </div>
            </div>
            @auth
                @if (auth()->id() !== $article->user_id)
                    <form method="POST" action="{{ route($isFollowingAuthor ? 'users.unfollow' : 'users.follow', $article->user) }}" class="ml-auto">
                        @csrf
                        @if ($isFollowingAuthor)
                            @method('DELETE')
                        @endif
                        <button type="submit" class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ $isFollowingAuthor ? 'border border-gray-200 text-gray-800 hover:bg-red-50 hover:text-red-600 hover:border-red-200' : 'bg-black text-white hover:bg-gray-800' }}">
                            {{ $isFollowingAuthor ? 'Following' : 'Follow' }}
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('signin') }}" class="ml-auto px-4 py-1.5 rounded-full bg-black text-white text-xs font-semibold hover:bg-gray-800 transition">Follow</a>
            @endauth
        </div>

        <hr class="border-t border-gray-200 mb-5">

        {{-- Stats + actions --}}
        <div class="flex items-center justify-between font-sans">
            <div class="flex items-center gap-5 text-sm text-gray-500">
                <button class="flex items-center gap-1.5 hover:text-gray-900 transition-colors">
                    <i class="ti ti-heart text-lg"></i>
                    {{ $article->love }}
                </button>
                <a href="#comments" class="flex items-center gap-1.5 hover:text-gray-900 transition-colors">
                    <i class="ti ti-message text-lg"></i>
                    {{ $commentsCount }}
                </a>
            </div>
            <div class="flex items-center gap-4 text-gray-400">
                <button class="hover:text-gray-900 transition-colors" aria-label="Simpan">
                    <i class="ti ti-bookmark text-xl"></i>
                </button>
                <button class="hover:text-gray-900 transition-colors" aria-label="Bagikan">
                    <i class="ti ti-share text-xl"></i>
                </button>
            </div>
        </div>

    </header>

    
    {{-- Cover image --}}

    @if(!blank($article->image))
        <img
            src="{{asset('storage/' . $article->image->first()->url)}}"
            alt=""
            class="w-full h-80 md:h-96 object-cover mb-10"
        >
    @endif

    

    {{-- Body --}}
    <article class="
        prose prose-xl max-w-none
        prose-headings:font-serif prose-headings:font-bold prose-headings:tracking-tight prose-headings:text-gray-900
        prose-p:text-gray-800 prose-p:leading-[1.85] prose-p:font-serif
        prose-blockquote:border-l-[3px] prose-blockquote:border-gray-900 prose-blockquote:pl-6 prose-blockquote:italic prose-blockquote:text-gray-500 prose-blockquote:font-serif prose-blockquote:text-xl
        prose-strong:text-gray-900 prose-strong:font-bold
        prose-a:text-gray-900 prose-a:underline prose-a:underline-offset-2
    ">
        
        {!!$article->content!!}

        
    

   

    @if ($isLocked)
        {{-- ====== PAYWALL OVERLAY ====== --}}
        <div class="relative -mt-40 md:-mt-48">

            {{-- Gradient fade menutupi bagian bawah konten --}}
            <div class="absolute inset-x-0 bottom-0 h-72 md:h-80 bg-gradient-to-t from-white via-white to-transparent pointer-events-none"></div>

            {{-- Lock card --}}
            <div class="relative pt-20 pb-10 text-center font-sans">
                <div class="w-12 h-12 rounded-full bg-gray-900 flex items-center justify-center mx-auto mb-5">
                    <i class="ti ti-lock text-white text-xl"></i>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 mb-2 font-serif">
                    Continue reading this article
                </h3>
                <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                    This article is for members only. Subscribe to unlock full access to premium content.
                </p>

                <div class="flex items-center justify-center gap-3">
                    <a href="{{ route('subscribe') }}"
                    class="bg-gray-900 text-white text-sm font-medium px-6 py-2.5 rounded-full hover:bg-gray-800 transition-colors">
                        Subscribe Now
                    </a>
                    @guest
                        <a href="{{ route('signin') }}"
                        class="text-gray-700 text-sm font-medium px-6 py-2.5 rounded-full border border-gray-300 hover:bg-gray-50 transition-colors">
                            Sign In
                        </a>
                    @endguest
                </div>
            </div>
        </div>
        {{-- ====== END PAYWALL OVERLAY ====== --}}
    @endif
    </article>

    

    <hr class="border-t border-gray-200 my-5">
    @include('pages.pageArticle.comment')


</main>
@endsection