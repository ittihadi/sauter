<div class="w-full h-16 p-3 sticky top-0 z-10 bg-gray-50 border-b border-gray-100 shadow-md flex items-center content-center">
    @if ($title == "Home")
    <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 576 512" class="ml-2 mr-6 fill-gray-600"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
        <path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" />
    </svg>
    <span class="text-xl text-gray-600 h-full content-center grow">Sauter</span>
    @else
    <a href='/'>
        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 448 512" class="ml-2 mr-6 fill-gray-600"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
            <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
        </svg>
    </a>
    <span class="text-xl text-gray-600 h-full content-center grow">{{ $title }}</span>
    @endif
    @if ($guest)
    <a href="/register" class="text-gray-500 text-sm bg-zinc-100/60 hover:text-gray-600 hover:bg-gray-200/60 active:bg-gray-300/60 transition-colors motion-reduce:transition-none duration-100 h-full content-center px-4 mx-2 rounded-md shadow-md border border-gray-200/60">Sign Up</a>
    <a href="/login" class="text-gray-500 text-sm bg-zinc-100 hover:text-gray-600 hover:bg-gray-200 active:bg-gray-300 transition-colors motion-reduce:transition-none duration-100 h-full content-center px-4 rounded-md shadow-md border border-gray-200">Sign In</a>
    @else
    <a href="/logout" class="text-gray-500/70 text-sm bg-gray-100/70 hover:text-red-600/70 hover:bg-red-100/70 active:bg-red-200/70 active:text-red-600/70 transition-colors motion-reduce:transition-none duration-200 h-full content-center px-4 rounded-md shadow-md border border-gray-200/70 hover:border-red-200/70">Sign Out</a>
    @endif
</div>
