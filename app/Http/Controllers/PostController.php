<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\Post;
use App\Models\SavedPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * View all posts
     */
    public function showHome(): View
    {
        $saves = SavedPost::query()
            ->where('save_author_id', '=', Auth::check() ? Auth::user()->id : 0);

        $posts = Post::query()
            ->join('users', 'users.id', '=', 'posts.author')
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->leftJoinSub($saves, 'saves', 'saves.save_post_id', '=', 'posts.post_id')
            ->select(
                'users.name as author_username',
                'user_profiles.display_name as author_displayname',
                'posts.content as content',
                'posts.post_id as post_id',
                'posts.created_at as created',
                'posts.updated_at as updated',
                DB::raw('CASE WHEN saves.save_post_id IS NULL THEN 0 ELSE 1 END AS saved'),
            )
            ->where('posts.parent_post_id', '=', null)
            ->orderByDesc('created')
            ->get();

        return view('index', [
            'guest' => !Auth::check(),
            'posts' => $posts,
            'feed' => false,
            'saves' => false,
        ]);
    }

    /**
     * View all posts by followed people
     */
    public function showFollowing(): View
    {
        $followings = Follower::query()
            ->where('follow_sender_id', '=', Auth::user()->id);

        $saves = SavedPost::query()
            ->where('save_author_id', '=', Auth::user()->id);

        $posts = Post::query()
            ->join('users', 'users.id', '=', 'posts.author')
            ->joinSub(
                $followings,
                'following',
                'following.follow_recipient_id',
                '=',
                'users.id'
            )
            ->leftJoinSub(
                $saves,
                'saves',
                'saves.save_post_id',
                '=',
                'posts.post_id'
            )
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select(
                'users.name as author_username',
                'user_profiles.display_name as author_displayname',
                'posts.content as content',
                'posts.post_id as post_id',
                'posts.created_at as created',
                'posts.updated_at as updated',
                DB::raw('CASE WHEN saves.save_post_id IS NULL THEN 0 ELSE 1 END AS saved'),
            )
            ->where('posts.parent_post_id', '=', null)
            ->orderByDesc('created')
            ->get();

        return view('index', [
            'guest' => !Auth::check(),
            'posts' => $posts,
            'feed' => true,
            'saves' => false,
        ]);
    }

    public function showSaved(): View
    {
        $saves = SavedPost::query()
            ->where('save_author_id', '=', Auth::user()->id);

        $posts = Post::query()
            ->join('users', 'users.id', '=', 'posts.author')
            ->joinSub(
                $saves,
                'saves',
                'saves.save_post_id',
                '=',
                'posts.post_id'
            )
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select(
                'users.name as author_username',
                'user_profiles.display_name as author_displayname',
                'posts.content as content',
                'posts.post_id as post_id',
                'posts.created_at as created',
                'posts.updated_at as updated',
                DB::raw('CASE WHEN saves.save_post_id IS NULL THEN 0 ELSE 1 END AS saved'),
            )
            ->orderByDesc('saves.created_at')
            ->get();

        return view('index', [
            'guest' => !Auth::check(),
            'posts' => $posts,
            'feed' => false,
            'saves' => true,
        ]);
    }

    /**
     * View post
     */
    public function show(string $id): View
    {
        // Check if post exists, return not exist page if not
        $postCandidate = Post::query()
            ->join('users', 'users.id', '=', 'posts.author')
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select(
                'users.name as author_username',
                'user_profiles.display_name as author_displayname',
                'posts.content as content',
                'posts.post_id as post_id',
                'posts.created_at as created',
                'posts.updated_at as updated'
            )
            ->where('posts.post_id', '=', $id)
            ->get();

        if ($postCandidate->count() < 1) {
            return view('post', [
                'guest' => !Auth::check(),
                'found' => 0,
                'post' => null,
                'replies' => null,
            ]);
        }

        $post = $postCandidate->first();
        $replies = Post::query()
            ->join('users', 'users.id', '=', 'posts.author')
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select(
                'users.name as author_username',
                'user_profiles.display_name as author_displayname',
                'posts.content as content',
                'posts.post_id as post_id',
                'posts.created_at as created',
                'posts.updated_at as updated'
            )
            ->where('parent_post_id', '=', $post->post_id)
            ->get();

        return view('post', [
            'guest' => !Auth::check(),
            'found' => 1,
            'post' => $post,
            'replies' => $replies,
        ]); // Fetch post data and feed in here
    }

    /**
     * Store newly created post
     */
    public function store(Request $request): RedirectResponse
    {
        $newPost = new Post;

        $candidateId = Str::random(12);
        while (Post::query()->where('post_id', '=', $candidateId)->count() > 0) {
            $candidateId = Str::random(12);
        }

        $newPost->post_id = $candidateId;
        $newPost->author = Auth::id();
        $newPost->content = $request->content;

        $newPost->save();

        return redirect('/');
    }

    /**
     * Edit post
     */
    public function edit(Request $request, string $id): RedirectResponse
    {
        // Find post and edit it
        $post = Post::query()->findOrFail($id);

        if ($post->author != Auth::id())
            return back()->withErrors(['author' => 'You didn\'t post this']);

        $post->content = $request->content;
        $post->save();

        // Find post and edit it (only if within 30 minutes of posting)
        return redirect()->route('home')->with('status', 'Successfully updated post $id');
    }

    /**
     * Delete post
     */
    public function delete(string $id): RedirectResponse
    {
        // Find post and delete it
        $post = Post::query()->findOrFail($id);

        if ($post->author != Auth::id())
            return back()->withErrors(['author' => 'You didn\'t post this']);

        $post->delete();

        return redirect()->intended();
    }

    public function reply(Request $request, string $id): RedirectResponse
    {
        // Make sure parent post exists
        $parent = Post::query()->find($id);
        if (!$parent) {
            return back()->withErrors(['parent' => 'Parent post doesn\'t exist']);
        }

        $newPost = new Post;

        $candidateId = Str::random(12);
        while (Post::query()->where('post_id', '=', $candidateId)->count() > 0) {
            $candidateId = Str::random(12);
        }

        $newPost->post_id = $candidateId;
        $newPost->author = Auth::id();
        $newPost->content = $request->content;
        $newPost->parent_post_id = $id;

        $newPost->save();

        return back();
    }

    public function save(string $id): RedirectResponse
    {
        // Make sure parent post exists
        $parent = Post::query()->find($id);
        if (!$parent) {
            return back()->withErrors(['parent' => 'Parent post doesn\'t exist']);
        }

        $oldSave = SavedPost::query()
            ->where('save_author_id', '=', Auth::user()->id)
            ->where('save_post_id', '=', $id)
            ->get();
        if ($oldSave->count() > 0)
            return back()->withErrors(['existing' => 'Post already saved']);

        $newSave = new SavedPost;

        $newSave->save_author_id = Auth::user()->id;
        $newSave->save_post_id = $id;

        $newSave->save();

        return back();
    }

    public function unsave(string $id): RedirectResponse
    {
        // Find logged in user's save of message and delete
        SavedPost::query()
            ->where('save_author_id',  '=', Auth::user()->id)
            ->where('save_post_id', '=', $id)
            ->delete();

        return back();
    }
}
