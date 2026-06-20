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
            <img
                src="https://i.pravatar.cc/80?img=12"
                alt="Lamda"
                class="w-10 h-10 rounded-full object-cover"
            >
            <div>
                <p class="text-sm font-medium text-gray-900">{{$article->user->name}}</p>
                <div class="flex items-center gap-1.5 text-sm text-gray-500">
                    <span>{{$article->created_at->format('d M Y')}}</span>
                    
                </div>
            </div>
        </div>

        <hr class="border-t border-gray-200 mb-5">

        {{-- Stats + actions --}}
        <div class="flex items-center justify-between font-sans">
            <div class="flex items-center gap-5 text-sm text-gray-500">
                <button class="flex items-center gap-1.5 hover:text-gray-900 transition-colors">
                    <i class="ti ti-heart text-lg"></i>
                    {{ $article->love }}
                </button>
                <button class="flex items-center gap-1.5 hover:text-gray-900 transition-colors">
                    <i class="ti ti-message text-lg"></i>
                    0
                </button>
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

        
    </article>

   

    @if (!auth()->user()?->isSubscriber())
            {{-- ====== PAYWALL OVERLAY (STATIC) ====== --}}
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
                    
                    <a href="#"
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

    

    
    
    <hr class="border-t border-gray-200 my-5">
    @include('pages.pageArticle.comment')


    <hr class="border-t border-gray-200 my-10">

    @include('partials.articles')

     <!-- Stats -->
    <div class="flex items-center gap-4 mt-2.5 text-xs text-gray-400">
        
        <a href="#" class="ml-auto flex items-center gap-1 text-gray-900 font-medium hover:underline underline-offset-2">
            Read More <i class="ti ti-arrow-right text-sm"></i>
        </a>
    </div>

</main>
@endsection