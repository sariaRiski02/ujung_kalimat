<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\WithdrawRequest;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Services\ArticleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class workspaceController extends Controller
{
    public function __construct(
        protected ArticleService $service
    ) {}

    public function subscribe(Request $request)
    {
        return view('pages.subscribe', [
            'subscriptionEndsAt' => $request->user()?->subscriptionEndsAt(),
        ]);
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();

        return view('pages.pageWorkspace.dashboard', [
            'articlesCount' => $user->article()->count(),
            'readersCount' => (int) $user->article()->sum('views'),
            'commentsCount' => Comment::whereRelation('article', 'user_id', $user->id)->count(),
            'followersCount' => $user->followers()->count(),
            'followingCount' => $user->following()->count(),
            'recentArticles' => $user->article()->latest()->orderByDesc('id')->limit(5)->get(['id', 'title', 'slug', 'status', 'created_at']),
        ]);
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

        return redirect()->route('workspace.articles')->with('success', $request->status === 'draft' ? 'article has been saved as draft' : 'article has been updated');
    }

    public function store(StoreArticleRequest $request)
    {
        $this->service->store($request);

        return redirect()->route('workspace.articles')->with('success', $request->status === 'draft' ? 'article has been saved as draft' : 'article has been store');
    }

    // public function delete(Article $article){
    //     $article->delete();
    //     return redirect()->route('workspace.articles')->with('success', 'article has been deleted');
    // }

    public function monetization(Request $request)
    {
        $user = $request->user();

        return view('pages.pageWorkspace.monetization', [
            'ratePerView' => config('monetization.rate_per_view'),
            'minWithdrawal' => config('monetization.min_withdrawal'),
            'totalEarnings' => $user->totalEarnings(),
            'totalWithdrawn' => $user->totalWithdrawn(),
            'availableBalance' => $user->availableBalance(),
            'premiumViews' => (int) $user->article()->where('is_premium', true)->sum('views'),
            'articles' => $user->article()->orderByDesc('is_premium')->orderByDesc('views')->get(['id', 'title', 'slug', 'is_premium', 'views']),
            'withdrawals' => $user->withdrawals()->latest()->latest('id')->get(),
        ]);
    }

    public function withdraw(WithdrawRequest $request): RedirectResponse
    {
        $withdrawal = DB::transaction(function () use ($request) {
            $user = User::whereKey($request->user()->id)->lockForUpdate()->first();

            if ($request->integer('amount') > $user->availableBalance()) {
                return null;
            }

            return $user->withdrawals()->create([
                'amount' => $request->integer('amount'),
                'phone_number' => $request->string('phone_number')->toString(),
            ]);
        });

        if (! $withdrawal) {
            return back()->withErrors(['amount' => 'The amount exceeds your available balance.']);
        }

        return redirect()->route('workspace.monetization')->with('success', 'Withdrawal requested. The money will be transferred to '.$withdrawal->phone_number.'.');
    }

    public function following(Request $request)
    {
        return view('pages.pageWorkspace.following', [
            'followings' => $request->user()->following()->orderByPivot('created_at', 'desc')->get(),
        ]);
    }

    public function followers(Request $request)
    {
        $user = $request->user();

        return view('pages.pageWorkspace.followers', [
            'followers' => $user->followers()->orderByPivot('created_at', 'desc')->get(),
            'followingIds' => $user->following()->pluck('users.id'),
        ]);
    }

    public function profile(Request $request)
    {
        return view('pages.pageWorkspace.profile', [
            'user' => $request->user(),
            'articleCount' => $request->user()->article()->count(),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $request->user()->update($request->safe()->only(['name', 'username', 'bio', 'city', 'country']));

        return redirect()->route('workspace.profile')->with('success', 'Profile updated successfully.');
    }

    public function updateProfilePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:min_width=100,min_height=100,max_width=6000,max_height=6000'],
        ], [
            'photo.image' => 'The file must be an image.',
            'photo.mimes' => 'The photo must be a JPG, PNG, or WEBP file.',
            'photo.max' => 'The photo may not be larger than 2 MB.',
            'photo.dimensions' => 'The photo must be at least 100x100 pixels.',
            'photo.uploaded' => 'The photo failed to upload. Make sure it is no larger than 2 MB.',
        ]);

        $user = $request->user();
        $oldPhoto = $user->hasUploadedProfilePhoto() ? $user->profile_photo_path : null;

        $user->update([
            'profile_photo_path' => $request->file('photo')->store('profile-photos', 'public'),
        ]);

        if ($oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return redirect()->route('workspace.profile')->with('success', 'Profile photo updated successfully.');
    }

    public function deleteProfilePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasUploadedProfilePhoto()) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->update(['profile_photo_path' => null]);

        return redirect()->route('workspace.profile')->with('success', 'Profile photo removed successfully.');
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
                ->withErrors(['current_password' => 'The password you entered is incorrect.'], 'confirmPassword');
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->route('workspace.profile.reset-password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update(['password' => $validated['password']]);

        $request->session()->forget('auth.password_confirmed_at');

        return redirect()->route('workspace.profile')->with('success', 'Password updated successfully.');
    }
}
