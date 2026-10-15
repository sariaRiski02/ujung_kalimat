<?php

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $filter = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function setFilter(string $filter): void
    {
        $this->filter = in_array($filter, ['draft', 'published'], true) ? $filter : '';
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->reset('search');
        $this->resetPage();
    }

    public function toggleStatus(int $articleId): void
    {
        $article = $this->ownedArticle($articleId);
        $article->update(['status' => $article->status === 'draft' ? 'published' : 'draft']);

        $this->dispatch('notify', message: 'Status artikel berhasil diubah.');
    }

    public function togglePremium(int $articleId): void
    {
        $article = $this->ownedArticle($articleId);
        $article->update(['is_premium' => ! $article->is_premium]);

        $this->dispatch('notify', message: 'Tipe artikel berhasil diubah.');
    }

    public function delete(int $articleId): void
    {
        $this->ownedArticle($articleId)->delete();

        $this->dispatch('notify', message: 'Artikel berhasil dihapus.');
    }

    /**
     * @return array{all: int, draft: int, published: int}
     */
    #[Computed]
    public function counts(): array
    {
        return [
            'all' => $this->searchQuery()->count(),
            'draft' => $this->searchQuery()->where('status', 'draft')->count(),
            'published' => $this->searchQuery()->where('status', 'published')->count(),
        ];
    }

    #[Computed]
    public function articles()
    {
        $query = $this->searchQuery();

        if ($this->filter !== '') {
            $query->where('status', $this->filter);
        }

        return $query->latest()->orderByDesc('id')->paginate(10);
    }

    private function ownedArticle(int $articleId): Article
    {
        return Article::where('user_id', Auth::id())->findOrFail($articleId);
    }

    /**
     * @return Builder<Article>
     */
    private function searchQuery(): Builder
    {
        $query = Article::query()->where('user_id', Auth::id());
        $term = trim($this->search);

        if ($term === '') {
            return $query;
        }

        $premiumValue = [
            'premium' => 1,
            'berbayar' => 1,
            'gratis' => 0,
            'free' => 0,
        ][strtolower($term)] ?? null;

        return $query->where(function (Builder $q) use ($term, $premiumValue) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('content', 'like', "%{$term}%")
                ->orWhere('status', $term);

            if (! is_null($premiumValue)) {
                $q->orWhere('is_premium', $premiumValue);
            }
        });
    }
};
?>

<div
    x-data="{ openDeleteModal: false, deleteId: null, deleteTitle: '', toast: '', toastTimer: null }"
    x-on:notify.window="toast = $event.detail.message; clearTimeout(toastTimer); toastTimer = setTimeout(() => toast = '', 3000)"
    x-on:keydown.window="if ($event.key === '/' && !['INPUT', 'TEXTAREA'].includes($event.target.tagName)) { $event.preventDefault(); $refs.search.focus() }"
>
    {{-- Filter + Search --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <div class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm overflow-x-auto pb-1 md:pb-0 scrollbar-none">
            @foreach ([['', 'All', 'all'], ['draft', 'Draft', 'draft'], ['published', 'Published', 'published']] as [$value, $label, $countKey])
                <button type="button" wire:key="filter-{{ $countKey }}" wire:click="setFilter('{{ $value }}')"
                    class="px-3 sm:px-4 py-1.5 rounded-full transition font-medium whitespace-nowrap {{ $filter === $value ? 'bg-black text-white shadow-sm' : 'text-gray-500 hover:bg-white hover:shadow-sm' }}">
                    {{ $label }} ({{ $this->counts[$countKey] }})
                </button>
            @endforeach
        </div>

        <div class="relative w-full md:w-72">
            <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm" wire:loading.remove wire:target="search,setFilter"></i>
            <i class="ti ti-loader-2 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm animate-spin" wire:loading wire:target="search,setFilter"></i>
            <input
                x-ref="search"
                type="search"
                wire:model.live.debounce.300ms="search"
                x-on:keydown.escape="$wire.clearSearch()"
                placeholder="Cari judul, isi, status... (tekan /)"
                autocomplete="off"
                class="pl-9 pr-9 py-2 bg-white border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-black/5 shadow-sm w-full [&::-webkit-search-cancel-button]:hidden"
            >
            @if ($search !== '')
                <button type="button" wire:click="clearSearch" title="Hapus pencarian"
                    class="absolute right-2 top-1/2 -translate-y-1/2 p-1 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 transition">
                    <i class="ti ti-x text-sm"></i>
                </button>
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden" wire:loading.class="opacity-60" wire:target="search,setFilter,gotoPage,previousPage,nextPage">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-sm min-w-[640px] table-fixed">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-5 py-3 w-[48%]">Artikel</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3 w-[17%]">Status</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3 w-[17%]">Tipe</th>
                        <th class="text-right text-xs font-semibold text-gray-400 uppercase tracking-wider px-5 py-3 w-[18%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($this->articles as $article)
                        <tr wire:key="article-{{ $article->id }}" class="hover:bg-gray-50/80 transition">
                            <td class="px-5 py-3">
                                <a href="{{ route('workspace.article.update', $article->slug) }}" class="block min-w-0">
                                    <p class="font-semibold text-gray-900 truncate hover:underline" title="{{ $article->title }}">{{ $article->title }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5 truncate">
                                        <span class="font-medium text-gray-500">{{ $article->created_at->format('d M Y') }}</span> ·
                                        {{ str($article->clean_content)->limit(60) }}
                                    </p>
                                </a>
                            </td>

                            {{-- Click the badge to switch draft / published --}}
                            <td class="px-3 py-3 whitespace-nowrap">
                                <button type="button" wire:click="toggleStatus({{ $article->id }})"
                                    title="{{ $article->status === 'published' ? 'Klik untuk jadikan draft' : 'Klik untuk publish' }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold transition cursor-pointer {{ $article->status === 'published' ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                                    <i class="ti {{ $article->status === 'published' ? 'ti-circle-check' : 'ti-file-text' }} text-[11px]"></i>
                                    {{ $article->status === 'published' ? 'Published' : 'Draft' }}
                                </button>
                            </td>

                            {{-- Click the badge to switch free / premium --}}
                            <td class="px-3 py-3 whitespace-nowrap">
                                <button type="button" wire:click="togglePremium({{ $article->id }})"
                                    title="{{ $article->is_premium ? 'Klik untuk jadikan gratis' : 'Klik untuk jadikan premium' }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold transition cursor-pointer {{ $article->is_premium ? 'bg-amber-50 text-amber-700 border border-amber-100 hover:bg-amber-100' : 'bg-gray-50 text-gray-600 border border-gray-100 hover:bg-gray-100' }}">
                                    @if ($article->is_premium)
                                        <i class="ti ti-crown text-[11px]"></i> Premium
                                    @else
                                        Free
                                    @endif
                                </button>
                            </td>

                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('workspace.article.update', $article->slug) }}"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition" title="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <a href="{{ route('article.show', $article->slug) }}" target="_blank" rel="noopener"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition" title="Lihat">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <button type="button"
                                        x-on:click="deleteId = {{ $article->id }}; deleteTitle = @js($article->title); openDeleteModal = true"
                                        class="p-2 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-16 text-gray-400 text-sm">
                                <i class="ti ti-file-off text-2xl mb-2 block"></i>
                                {{ $search !== '' ? 'Tidak ada artikel yang cocok.' : 'Belum ada artikel.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($this->articles->hasPages())
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-gray-100 px-5 py-3">
                <div class="text-xs text-gray-500">
                    <span class="font-semibold text-gray-700">{{ $this->articles->firstItem() }}</span>–<span class="font-semibold text-gray-700">{{ $this->articles->lastItem() }}</span>
                    dari <span class="font-semibold text-gray-700">{{ $this->articles->total() }}</span> artikel
                </div>

                <div class="flex items-center gap-1.5 text-sm flex-wrap justify-center">
                    @if ($this->articles->onFirstPage())
                        <span class="p-2 text-gray-300 cursor-not-allowed"><i class="ti ti-chevron-left text-base"></i></span>
                    @else
                        <button type="button" wire:click="previousPage" class="p-2 rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-50 transition">
                            <i class="ti ti-chevron-left text-base"></i>
                        </button>
                    @endif

                    @for ($i = 1; $i <= $this->articles->lastPage(); $i++)
                        @if ($i === $this->articles->currentPage())
                            <span wire:key="page-{{ $i }}" class="px-3 py-1.5 rounded-full bg-black text-white font-medium text-xs min-w-[32px] text-center shadow-sm">{{ $i }}</span>
                        @else
                            <button type="button" wire:key="page-{{ $i }}" wire:click="gotoPage({{ $i }})" class="px-3 py-1.5 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-medium text-xs min-w-[32px] text-center transition">{{ $i }}</button>
                        @endif
                    @endfor

                    @if ($this->articles->hasMorePages())
                        <button type="button" wire:click="nextPage" class="p-2 rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-50 transition">
                            <i class="ti ti-chevron-right text-base"></i>
                        </button>
                    @else
                        <span class="p-2 text-gray-300 cursor-not-allowed"><i class="ti ti-chevron-right text-base"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Delete confirmation --}}
    <div x-show="openDeleteModal" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/45 px-4">
        <div x-on:click.away="openDeleteModal = false" class="w-full max-w-md rounded-3xl border border-gray-200 bg-white p-6 shadow-2xl">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-500">
                    <i class="ti ti-trash text-xl"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-xl font-semibold text-gray-900">Delete this article?</h3>
                    <p class="text-sm text-gray-500">You are about to delete <span class="font-semibold text-gray-700" x-text="deleteTitle || 'this article'"></span>. This action cannot be undone.</p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" x-on:click="openDeleteModal = false" class="rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">Cancel</button>
                <button type="button" x-on:click="$wire.delete(deleteId); openDeleteModal = false" class="rounded-full bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600 transition shadow-sm">Delete</button>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div x-show="toast" x-cloak x-transition class="fixed top-5 left-1/2 -translate-x-1/2 z-50 w-full max-w-sm px-4">
        <div class="p-4 rounded-xl border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2 shadow-lg bg-white">
            <i class="ti ti-circle-check text-emerald-600"></i>
            <span x-text="toast"></span>
        </div>
    </div>
</div>
