<div class="group w-full rounded-md p-4 bg-gray-50 shadow-md border border-gray-100 ">

    <a href="/profile/{{ $authorUsername }}">
        <div class="flex mb-2 items-center">
            <!-- Not enough time to add pictures. -->
            <div class="w-12 h-12 bg-green-200 rounded-full mr-4 saturate-70"
                style="background-color: #{{  substr(md5($authorUsername), 2, 6) }};">
                <!-- Insert picture here -->
            </div>
            <div>
                <span class="text-lg text-gray-800 block">{{ $authorDisplayname ? $authorDisplayname : $authorUsername }}</span>
                <span class="text-md text-gray-300 block">{{ $authorUsername }}</span>
            </div>
        </div>
    </a>

    <div>
        <span id="content-fixed-{{ $postId }}" class="text-xl text-black block px-1">
            {{ $content }}
        </span>
        @if ($currentUser == $authorUsername)
        <div id="content-edit-{{ $postId }}" class="hidden text-xl text-black block">
            <form id="edit-form-{{ $postId }}" role="form" action="/post/edit/{{$postId}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="text" class="w-full py-2 px-2
                    bg-gray-200/60 rounded-md mt-2"
                    name="content" id="edit-content"
                    value="{{ $content }}" required />
            </form>
        </div>
        @endif
    </div>

    <hr class="border-gray-200 my-1" />

    <div class="flex items-center">
        <!-- Need to make timezones, bleh -->
        <span class="text-gray-400 text-xs grow">Posted on {{
            Carbon\Carbon::parse($time)->format('Y/m/d')
            }}</span>

        <!-- Limits editing to 5 mins -->
        @if ($currentUser == $authorUsername)
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
