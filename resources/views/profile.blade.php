<!DOCTYPE html>
<html>

<head>
    <title>Sauter - {{ $profile->display_name ? $profile->display_name : $profile->username }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <meta property="og:title" content="Sauter" />
    <meta property="og:description" content="A microblogging project" />
    <meta property="og:type" content="website" />
    <meta property="theme-color" content="#{{ substr(md5($profile->username), 2, 6) }}" />
</head>

<body class="bg-zinc-100">
    <div class="mx-auto min-h-dvh w-full sm:w-2xl bg-white shadow-xl border-x border-gray-300">
        <!-- Header bar, sticky to the top -->
        <x-header :$guest title="Profile" />

        @if ($found)

        <div class="w-full bg-gray-50 p-8 border-b border-gray-200 shadow-md">
            <div class="flex items-start">
                <!-- Not enough time to add pictures. -->
                <div class="w-24 h-24 bg-green-200 rounded-full mr-8 saturate-70"
                    style=" background-color: #{{ substr(md5($profile->username), 2, 6) }};">
                    <!-- Insert picture here -->
                </div>
                <div id="profile-fixed" class="grow">
                    <div>
                        <span class="text-2xl text-gray-800 block">{{ $profile->display_name ? $profile->display_name : $profile->username }}</span>
                        <span class="text-lg text-gray-300 block">{{ $profile->username }}</span>
                        <span class="text-md mt-2">{{ $profile->biography }}</span>
                    </div>
                </div>
                @if ($owner)
                <!-- Edit form -->
                <div id="profile-edit" class="grow hidden bg-gray-200/60 rounded-md -ml-2 -my-1 py-1 flex items-start">
                    <form id="profile-edit-form" class="grow" role="form" action="/profile/edit/{{ $profile->username }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="text"
                            class="w-full text-2xl text-gray-800 block px-2"
                            name="display_name" id="edit-displayname"
                            value="{{ $profile->display_name ? $profile->display_name : $profile->username }}"
                            required />
                        <span class="text-lg text-gray-300 block px-2">{{ $profile->username }}</span>
                        <input type="text"
                            class="w-full text-md text-gray-800 block px-2"
                            name="biography" id="edit-biography"
                            value="{{ $profile->biography }}" />
                    </form>
                    <button class="group py-2 px-4 relative cursor-pointer hidden" id="profile-edit-confirm"
                        type="submit" form="profile-edit-form">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 448 512"
                            class="fill-gray-400 group-hover:fill-gray-500 group-active:fill-green-500"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path d="M64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-242.7c0-17-6.7-33.3-18.7-45.3L352 50.7C340 38.7 323.7 32 306.7 32L64 32zm0 96c0-17.7 14.3-32 32-32l192 0c17.7 0 32 14.3 32 32l0 64c0 17.7-14.3 32-32 32L96 224c-17.7 0-32-14.3-32-32l0-64zM224 288a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" />
                        </svg>
                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 z-10
                            inline-block px-3 py-2 text-sm text-gray-600 bg-gray-100 w-max
                            rounded-lg shadow-sm opacity-0 group-hover:opacity-100 group-active:opacity-100">
                            Save
                        </div>
                    </button>
                </div>
                <div class="p-2 group relative cursor-pointer" onclick="function toggleEdit() {
                    let fixed_elem = document.getElementById('profile-fixed');
                    let edit_elem = document.getElementById('profile-edit');
                    let edit_save = document.getElementById('profile-edit-confirm');
                    fixed_elem.classList.toggle('hidden');
                    edit_elem.classList.toggle('hidden');
                    edit_save.classList.toggle('hidden');
                    } toggleEdit();">
                    <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"
                        class="fill-gray-400 group-hover:fill-gray-500 group-active:fill-gray-500"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
                    </svg>
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 z-10
                            inline-block px-3 py-2 text-sm text-gray-600 bg-gray-100 w-max
                            rounded-lg shadow-sm opacity-0 group-hover:opacity-100 group-active:opacity-100">
                        Edit Profile
                    </div>
                </div>
                @else
                <!-- Follow button if not profile owner -->
                @if ($following)
                <form role="form" action="/unfollow/{{ $profile->username }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <button type="submit"
                        class="py-1 px-2 bg-red-400 text-sm text-white rounded-md shadow-sm mt-1
                            cursor-pointer font-medium
                            hover:bg-red-500 active:bg-red-600
                            transition-colors motion-reduce:transition-none duration-100">
                        Unfollow
                    </button>
                </form>
                @else
                <form role=" form" action="/follow/{{ $profile->username }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <button type="submit"
                        class="py-1 px-2 bg-green-400 text-sm text-white rounded-md shadow-sm mt-1
                            cursor-pointer font-medium
                            hover:bg-green-500 active:bg-green-600
                            transition-colors motion-reduce:transition-none duration-100">
                        Follow
                    </button>
                </form>
                @endif
                @endif
            </div>
        </div>

        <!-- Profile posts -->
        <div class="grid grid-cols-1 gap-1 p-2 sm:p-4 pb-18 sm:pb-20">
            @foreach ($posts as $post)
            <x-post
                current-user="{{ Auth::check() ? Auth::user()->name : 0 }}"
                author-user='{{ $post->author_username }}'
                author-display='{{ $post->author_displayname }}'
                content='{{ $post->content }}'
                post-id='{{ $post->post_id }}'
                time='{{ $post->created }}' />
            @endforeach
        </div>

        @else

        <!-- Profile not found -->
        <div class="min-h-dvh bg-zinc-300 p-2 sm:p-4">
            <div class="w-full p-4 bg-zinc-100 border border-gray-300 shadow-lg rounded-lg">
                <span class="text-xl text-gray-700 block mb-2">User not found...</span>
                <hr class="border-gray-200 my-1" />
                <a href="/" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 448 512"
                        class="fill-gray-600"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
                    </svg>
                    <span class="pl-2 text-md text-gray-600">Return home</span>
                </a>
            </div>
        </div>

        @endif

        <!-- Bottom footer bar -->
        <div class="w-full sm:w-2xl h-16 p-2 fixed bottom-0
                sm:right-1/2 sm:transform sm:translate-x-1/2
                bg-gray-50 border-t border-l border-r border-gray-300 shadow-md
                grid {{ $guest ? 'grid-cols-1' : 'grid-cols-3' }} gap-2">
            <div class="inline-grid justify-items-center">
                <div class="group h-full relative">
                    <a class="cursor-pointer px-8 h-full flex items-center
                        text-lg text-green-800/80
                        bg-gray-100/90 hover:bg-gray-200/70 active:bg-gray-200
                        border border-gray-200 shadow-md rounded-full
                            transition-colors motion-reduce:transition-none duration-100"
                        href="/">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 576 512" class="fill-gray-600"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" />
                        </svg>
                    </a>
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 z-10
                            inline-block px-4 py-2 text-md text-gray-600 bg-gray-100
                            rounded-lg shadow-sm opacity-0 group-hover:opacity-100 group-active:opacity-100">
                        Home
                    </div>
                </div>
            </div>
            @if (!$guest)
            <div class="inline-grid justify-items-center">
                <div class="group h-full relative">
                    <a class="cursor-pointer px-8 h-full flex items-center
                        text-lg text-green-800/80
                        bg-gray-100/90 hover:bg-gray-200/70 active:bg-gray-200
                        border border-gray-200 shadow-md rounded-full
                            transition-colors motion-reduce:transition-none duration-100"
                        href="/following">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 576 512" class="fill-gray-600"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z" />
                        </svg>
                    </a>
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 z-10
                            inline-block px-4 py-2 text-md text-gray-600 bg-gray-100
                            rounded-lg shadow-sm opacity-0 group-hover:opacity-100 group-active:opacity-100">
                        Following
                    </div>
                </div>
            </div>
            @endif
            @if (!$guest)
            <div class="inline-grid justify-items-center">
                <div class="group h-full relative">
                    <a class="cursor-pointer px-8 h-full flex items-center
                        text-lg text-green-800/80
                        bg-gray-100/90 hover:bg-gray-200/70 active:bg-gray-200
                        border border-gray-200 shadow-md rounded-full
                            transition-colors motion-reduce:transition-none duration-100"
                        href="/profile/me">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 448 512" class="fill-gray-600"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                        </svg>
                    </a>
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 z-10
                            inline-block px-4 py-2 text-md text-gray-600 bg-gray-100 w-max
                            rounded-lg shadow-sm opacity-0 group-hover:opacity-100 group-active:opacity-100">
                        My Profile
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</body>


</html>
