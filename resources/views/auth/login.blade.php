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
            <h1 class="text-3xl text-gray-800 mb-4 font-bold">Welcome Back</h1>
            <form role="form" action="/login" method="post" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-1 mb-2">
                    <label class="text-gray-700 uppercase text-sm w-full">Email</label>
                    <input type="text" class="bg-white rounded-md shadow-md border border-gray-300 p-2" name="email" id="login-email" required value="{{ old('email') }}" />
                </div>
                <div class="grid grid-cols-1 gap-1 mb-2">
                    <label class="text-gray-700 uppercase text-sm w-full">Password</label>
                    <input type="password" class="bg-white rounded-md shadow-md border border-gray-300 p-2" name="password" id="login-password" required />
                </div>
                <div class="gap-1 mb-2">
                    <input type="checkbox" class="bg-gray-500 p-1 align-middle" name="remember" id="login-remember" />
                    <label class="text-gray-600 uppercase text-sm w-full align-middle">Remember Me</label>
                </div>
                @error('email')
                <span class="block w-full px-4 py-2 text-sm text-red-500 bg-red-100/60 border border-red-300/70 rounded-md">{{ $message }}</span>
                @enderror
                <button class="w-full mt-4 bg-green-400 p-2 rounded-md shadow-md text-gray-100 text-md font-bold cursor-pointer hover:bg-green-500 hover:text-white active:bg-green-600 transition-colors motion-reduce:transition-none duration-100" type="submit">Log In</button>
                <a href="/register" class="text-xs text-blue-500 py-1 pr-1 active:underline">Or register a new account</a>
            </form>
        </div>
    </div>
</body>


</html>
