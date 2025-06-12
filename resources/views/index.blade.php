<!DOCTYPE html>
<html>

<head>
    <title>Sauter - {{$feed ? 'Following' : 'Home'}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <meta property="og:title" content="Sauter" />
    <meta property="og:description" content="A microblogging project" />
    <meta property="og:type" content="website" />
    <meta property="theme-color" content="#bef5c7" />
</head>

<body class="bg-zinc-100">
    <div class="mx-auto min-h-dvh w-full sm:w-2xl bg-white shadow-xl border-x border-gray-300">
        <!-- Header bar, sticky to the top -->
        <x-header :$guest title="{{$feed ? 'Following' : 'Home'}}" />

        <!-- Upload widget, hidden by default -->
        <div id="new-post-widget" class="hidden sticky top-16 w-full bg-gray-50 p-4
                border-b border-gray-200 shadow-md transition-opacity">
            <span class="text-gray-600 text-2xl px-2">New Post</span>
            <form role="form" action="/post/new" method="post" enctype="multipart/form-data">
                @csrf
                <input type="text" class="w-full bg-transparent p-2
                        focus:outline-0 focus:bg-gray-100/60 rounded-md
                        text-gray-500 mt-2 text-lg" name="content"
                    id="post-content" placeholder="Say something..." required />
                <hr class="border-gray-200 mt-1" />
                <div class="w-full flex items-center">
                    <div class="grow"></div>
                    <button onclick="togglePostDialog();" class="
                            text-gray-50 font-medium cursor-pointer
                            text-md px-4 py-2 bg-red-400 rounded-md mt-4
                            hover:bg-red-500 hover:text-white active:bg-red-600
                            transition-colors motion-reduce:transition-none duration-100
                            mr-2">
                        Cancel</button>
                    <button type="submit" class="
                            text-gray-50 font-medium cursor-pointer
                            text-md px-4 py-2 bg-green-400 rounded-md mt-4
                            hover:bg-green-500 hover:text-white active:bg-green-600
                            transition-colors motion-reduce:transition-none duration-100">
                        Saut</button>
                </div>
            </form>
        </div>

        <!-- Main posts area -->
        <div class="grid grid-cols-1 gap-2 p-2 sm:p-4 pb-18 sm:pb-20">
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

        <!-- Bottom footer bar -->
        <div class="w-full sm:w-2xl h-16 p-2 fixed bottom-0
                sm:right-1/2 sm:transform sm:translate-x-1/2
                bg-gray-50 border-t border-l border-r border-gray-300 shadow-md
                grid {{ $guest ? 'grid-cols-3' : 'grid-cols-5' }} gap-2">
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
            <div class="inline-grid justify-items-center">
                <div class="group h-full relative">
                    <a type="button" class="cursor-pointer px-8 h-full flex items-center
                        text-lg text-green-800/80
                        bg-green-100/90 hover:bg-green-200/70 active:bg-green-200
                        border border-green-200 shadow-md rounded-full shadow-green-100
                            transition-colors motion-reduce:transition-none duration-100"
                        @if ($guest) href="/login" @else onclick="togglePostDialog()" @endif>
                        <span class="select-none">🗣️</span>
                    </a>
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 z-10
                            inline-block px-4 py-2 text-md text-gray-600 bg-gray-100
                            rounded-lg shadow-sm opacity-0 group-hover:opacity-100 group-active:opacity-100">
                        Saut
                    </div>
                </div>
            </div>
            <div></div>
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
    <script defer>
        function togglePostDialog() {
            let postField = document.getElementById('post-content');
            let postDial = document.getElementById('new-post-widget');
            postDial.classList.toggle('hidden');
            postField.value = '';
        }
    </script>
</body>


</html>
