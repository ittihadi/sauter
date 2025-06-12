<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\Post;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(string $name): View
    {
        $profile = User::query()
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select(
                'users.id as id',
                'users.name as username',
                'user_profiles.display_name as display_name',
                'user_profiles.biography as biography',
            )
            ->where('users.name', '=', $name)
            ->get();

        $posts = Post::query()
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
            ->where('users.name', '=', $name)
            ->orderByDesc('created')
            ->get();

        // This CAN be better.
        $user = User::query()->where('name', '=', $name)->get();
        $following = Follower::query()
            ->where('follow_sender_id', '=', Auth::check() ? Auth::user()->id : 0)
            ->where('follow_recipient_id', '=', $user->count() > 0 ? $user->first()->id : 0)
            ->get();

        $followers = Follower::query()
            ->where('follow_recipient_id', '=', $user->count() > 0 ? $user->first()->id : 0)
            ->count();

        return view('profile', [
            'guest' => !Auth::check(),
            'found' => $profile->count() != 0,
            'profile' => $profile->first(),
            'posts' => $posts,
            'owner' => $profile->count() > 0 && Auth::check() ?
                $profile->first()->username == Auth::user()->name :
                false,
            'following' => $following->count() > 0,
            'followers' => $followers,
        ]);
    }

    /**
     * Edit profile
     */
    public function edit(Request $request, string $username): RedirectResponse
    {
        if ($username != Auth::user()->name)
            return back()->withErrors(['username' => 'You don\'t own this profile']);

        // Find user data
        $user = User::query()
            ->where('name', '=', $username)
            ->get();

        if (!$user)
            return back()->withErrors(['username' => 'Invalid username']);

        // Find profile and edit it
        $profile = UserProfile::query()->findOrNew($user->first()->id);

        $profile->user_id = $user->first()->id;
        $profile->display_name = $request->display_name;
        $profile->biography = $request->biography;

        $profile->save();

        return back();
    }

    /**
     * Follow a profile
     */
    public function follow(string $username): RedirectResponse
    {
        // Ensure user exists
        $user = User::query()->where('name', '=', $username)->get();
        if ($user->count() == 0)
            return back()->withErrors('User not found');

        // Ensure relation doesn't already exist
        $oldFollowing = Follower::query()
            ->where('follow_sender_id', '=', Auth::user()->id)
            ->where('follow_recipient_id', '=', $user->first()->id)
            ->get();

        if ($oldFollowing->count() > 0)
            return back()->withErrors(['existing' => 'Relation already exists']);

        $following = new Follower;

        $following->follow_sender_id = Auth::user()->id;
        $following->follow_recipient_id = $user->first()->id;
        $following->timestamps = false;

        $following->save();

        return back();
    }

    /**
     * Unfollow a profile
     */
    public function unfollow(string $username): RedirectResponse
    {
        // Ensure user exists
        $user = User::query()->where('name', '=', $username)->get();
        if ($user->count() == 0)
            return back()->withErrors('User not found');

        Follower::query()
            ->where('follow_sender_id', '=', Auth::user()->id)
            ->where('follow_recipient_id', '=', $user->first()->id)
            ->delete();

        return back();
    }
}
