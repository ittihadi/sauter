<!DOCTYPE html>
<html>

<head>
    <title>Sauter</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex items-center h-screen">
    <div class="m-auto min-w-8/10 sm:min-w-sm h-auto bg-zinc-100 rounded-xl shadow-xl border border-gray-300">
        <div class="p-8">
            <h1 class="text-3xl text-gray-800 mb-4 font-bold">Welcome</h1>
            <form role="form" action="/register" method="post" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-1 mb-2">
                    <label class="text-gray-700 uppercase text-sm">Username</label>
                    <input type="text" class="bg-white rounded-md shadow-md border border-gray-300 p-2" name="username" id="register-username" value="{{ old('username') }}" required />
                    @error('username')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror()
                </div>
                <div class="grid grid-cols-1 gap-1 mb-2">
                    <label class="text-gray-700 uppercase text-sm w-full">Email</label>
                    <input type="text" class="bg-white rounded-md shadow-md border border-gray-300 p-2" name="email" id="register--email" required value="{{ old('email') }}" />
                    @error('email')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror()
                </div>
                <div class="grid grid-cols-1 gap-1 mb-2">
                    <label class="text-gray-700 uppercase text-sm w-full">Password</label>
                    <input type="password" class="bg-white rounded-md shadow-md border border-gray-300 p-2" name="password" id="register-password" required value="{{ old('password') }}" />
                </div>
                <div class="grid grid-cols-1 gap-1 mb-2">
                    <label class="text-gray-700 uppercase text-sm w-full">Confirm Password</label>
                    <input type="password" class="bg-white rounded-md shadow-md border border-gray-300 p-2" name="password_confirmation" id="register-password-confirm" required value="{{ old('password_confirmation') }}" />
                    @error('password')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror()
                </div>
                <button class="w-full mt-4 bg-green-400 p-2 rounded-md shadow-md text-gray-100 text-md font-bold cursor-pointer hover:bg-green-500 hover:text-white active:bg-green-600 transition-colors motion-reduce:transition-none duration-100" type="submit">Register</button>
                <a href="/login" class="text-xs text-blue-500 py-1 pr-1 active:underline">Already have an account? Log in</a>
            </form>
        </div>
    </div>

    <!-- @if ($errors->any()) -->
    <!-- <div class="alert alert-danger"> -->
    <!--     <ul> -->
    <!--         @foreach ($errors->all() as $error) -->
    <!--         <li>{{ $error }}</li> -->
    <!--         @endforeach -->
    <!--     </ul> -->
    <!-- </div> -->
    <!-- @endif -->
</body>

</html>
