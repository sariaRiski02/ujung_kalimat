<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class workspaceController extends Controller
{
    public function __construct(
        protected ArticleService $service
    ) {}

    public function dashboard()
    {
        return view('pages.pageWorkspace.dashboard');
    }

    public function write()
    {
        return view('pages.pageWorkspace.write');
    }

    public function update(Article $article)
    {

        $contents = $article->array_content;
        $title = $article->clean_title;
        $article->load('image');

        return view('pages.pageWorkspace.update', compact(
            'article', 'contents', 'title'
        ));
    }

    public function edit(StoreArticleRequest $request, Article $article)
    {
        $this->service->update($article, $request);

        return redirect()->route('workspace.articles')->with('success', 'article has been updated');
    }

    public function store(StoreArticleRequest $request)
    {
        $this->service->store($request);

        return redirect()->route('workspace.articles')->with('success', 'article has been store');
    }

    // public function delete(Article $article){
    //     $article->delete();
    //     return redirect()->route('workspace.articles')->with('success', 'article has been deleted');
    // }

    public function monetization()
    {
        return view('pages.pageWorkspace.monetization');
    }

    public function following()
    {
        return view('pages.pageWorkspace.following');
    }

    public function followers()
    {
        return view('pages.pageWorkspace.followers');
    }

    public function profile()
    {
        return view('pages.pageWorkspace.profile');
    }

    public function analytics()
    {
        return view('pages.pageWorkspace.analyticDetailArticle');
    }

    public function resetPassword()
    {
        return view('pages.pageWorkspace.reset-password');
    }

    public function confirmPassword(Request $request): RedirectResponse
    {
        $request->validateWithBag('confirmPassword', [
            'current_password' => ['required', 'string'],
        ]);

        if (! Hash::check($request->input('current_password'), $request->user()->password)) {
            return back()
                ->withErrors(['current_password' => 'Password yang kamu masukkan salah.'], 'confirmPassword');
        }

        $request->session()->passwordConfirmed();

        return redirect()->route('workspace.profile.reset-password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update(['password' => $validated['password']]);

        $request->session()->forget('auth.password_confirmed_at');

        return redirect()->route('workspace.profile')->with('success', 'Password berhasil diperbarui.');
    }
}
