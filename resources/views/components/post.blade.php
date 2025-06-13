<!-- hover:bg-gray-100 active:bg-gray-200/60 hover:border-gray-200 active:border-gray-200 -->
<div class="group w-full rounded-md p-4 bg-gray-50 shadow-md border border-gray-100 ">

    <div class="flex mb-2 items-start">
        <a href="/profile/{{ $authorUser }}" class="flex items-center grow">
            <!-- Not enough time to add pictures. -->
            <div class="w-6 h-6 rounded-full mr-4 saturate-70"
                style="background-color: #{{ substr(md5($authorUser), 2, 6) }};">
                <!-- Insert picture here -->
            </div>
            <span class="text-lg text-gray-800 grow">{{ $authorDisplay ? $authorDisplay : $authorUser }}</span>
        </a>
        @if (!$saved)
        <form role="action" action="/post/save/{{ $postId }}" method="post" enctype="multipart/form-data"
            class="group/save relative {{ $browser->isMobile() ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}">
            @csrf
            <button class="py-1 px-1 cursor-pointer" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 384 512"
                    class="fill-gray-400 group-hover/save:fill-gray-500 group-active/save:fill-green-500"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d="M0 48C0 21.5 21.5 0 48 0l0 48 0 393.4 130.1-92.9c8.3-6 19.6-6 27.9 0L336 441.4 336 48 48 48 48 0 336 0c26.5 0 48 21.5 48 48l0 440c0 9-5 17.2-13 21.3s-17.6 3.4-24.9-1.8L192 397.5 37.9 507.5c-7.3 5.2-16.9 5.9-24.9 1.8S0 497 0 488L0 48z" />
                </svg>
                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 z-10
                            inline-block px-3 py-2 text-sm text-gray-600 bg-gray-100 w-max
                            rounded-lg shadow-sm opacity-0 group-hover/save:opacity-100 group-active/save:opacity-100">
                    Save post
                </div>
            </button>
        </form>
        @else
        <form role="action" action="/post/unsave/{{ $postId }}" method="post" enctype="multipart/form-data"
            class="group/save relative">
            @csrf
            <button class="py-1 px-1 cursor-pointer" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 384 512"
                    class="fill-gray-400 group-hover/save:fill-gray-500 group-active/save:fill-green-500"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d="M0 48V487.7C0 501.1 10.9 512 24.3 512c5 0 9.9-1.5 14-4.4L192 400 345.7 507.6c4.1 2.9 9 4.4 14 4.4c13.4 0 24.3-10.9 24.3-24.3V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48z" />
                </svg>
                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 z-10
                            inline-block px-3 py-2 text-sm text-gray-600 bg-gray-100 w-max
                            rounded-lg shadow-sm opacity-0 group-hover/save:opacity-100 group-active/save:opacity-100">
                    Unsave post
                </div>
            </button>
        </form>
        @endif
    </div>

    @if ($currentUser == $authorUser)
    <div id="content-edit-{{ $postId }}" class="hidden text-black block">
        <form id="edit-form-{{ $postId }}" role="form" action="/post/edit/{{$postId}}" method="post" enctype="multipart/form-data">
            @csrf
            <textarea class="w-full py-2 px-2
                    bg-gray-200/60 rounded-md mt-2 resize-y"
                    rows="3" name="content" autocomplete="off"
                id="edit-content" required
            >{{ html_entity_decode(trim($content)) }}</textarea>
        </form>
    </div>
    @endif

    <a href="/post/{{ $postId }}">
        <div id="content-fixed-{{ $postId }}">
            <span class="text-black block px-1 whitespace-pre-wrap">{{ html_entity_decode(trim($content)) }}</span>
        </div>
    </a>

    <hr class="border-gray-200 my-1" />

    <div class="flex items-center">
        <!-- Need to make timezones, bleh -->
        <span class="text-gray-400 text-xs grow">Posted on {{
            Carbon\Carbon::parse($time)->format('Y/m/d')
            }}</span>

        <!-- Limits editing to 5 mins -->
        @if ($currentUser == $authorUser)
        <div class="{{ $browser->isMobile() ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }} transition-opacity duration-100 flex"
            id="post-interact-{{ $postId }}">
            @if(-Carbon\Carbon::now()->diffInSeconds($time) < 300)
                <button onclick="function toggleEdit() {
                    let fixed = document.getElementById('content-fixed-{{ $postId }}');
                    let edit = document.getElementById('content-edit-{{ $postId }}');

                    let interactButtons = document.getElementById('post-interact-{{ $postId }}');
                    let editButtons = document.getElementById('post-edit-{{ $postId }}');

                    fixed.classList.toggle('hidden');
                    edit.classList.toggle('hidden');

                    interactButtons.classList.toggle('hidden');
                    editButtons.classList.toggle('hidden');
                }
            toggleEdit();"
                class="text-sm text-gray-500 px-2 hover:bg-gray-200/70 rounded-md cursor-pointer">Edit</button>
                @endif
                <a href="/post/delete/{{ $postId }}" onclick="return confirm('Are you sure?');"
                    class="text-sm text-red-500 px-2 hover:bg-red-100/70 rounded-md">Delete</a>
        </div>
        <div class="hidden flex"
            id="post-edit-{{ $postId }}">
            <button onclick="function toggleEdit() {
                    let fixed = document.getElementById('content-fixed-{{ $postId }}');
                    let edit = document.getElementById('content-edit-{{ $postId }}');

                    let interactButtons = document.getElementById('post-interact-{{ $postId }}');
                    let editButtons = document.getElementById('post-edit-{{ $postId }}');

                    fixed.classList.toggle('hidden');
                    edit.classList.toggle('hidden');

                    interactButtons.classList.toggle('hidden');
                    editButtons.classList.toggle('hidden');
                }
            toggleEdit();"
                class="text-sm text-red-500 px-2 hover:bg-red-200/70 rounded-md cursor-pointer">Cancel</button>
            <button type="submit" form="edit-form-{{ $postId }}"
                class="text-sm text-green-500 px-2 hover:bg-green-100/70 rounded-md cursor-pointer">Done</button>
        </div>
        @else
        <div id="dummy-sizing" class="opacity-0 flex"><button class="text-sm px-2">Dummy</button></div>
        @endif

    </div>
</div>
