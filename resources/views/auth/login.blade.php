<x-layouts.app>
    <div class="max-w-md mx-auto mt-20 p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-6 text-center">Přihlášení</h1>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/login" class="space-y-4 mb-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Heslo</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500">
                <label for="remember" class="ml-2 block text-sm text-gray-900">Zapamatovat si mě</label>
            </div>

            <button type="submit" class="w-full bg-red-600 text-white p-3 rounded-md hover:bg-red-700 transition font-bold">
                Přihlásit se
            </button>
        </form>

        <div class="relative flex py-5 items-center">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="flex-shrink mx-4 text-gray-400 text-sm">nebo</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <div class="space-y-4">
            <a href="/auth/google" class="flex items-center justify-center gap-3 w-full border p-3 rounded-md hover:bg-gray-50 transition">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-5 h-5">
                <span>Přihlásit přes Google</span>
            </a>

            <a href="/auth/facebook" class="flex items-center justify-center gap-3 w-full bg-[#1877F2] text-white p-3 rounded-md hover:bg-blue-600 transition">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                <span>Přihlásit přes Facebook</span>
            </a>
        </div>

        <p class="mt-8 text-center text-gray-500 text-sm">
            Nemáte účet? <a href="/register" class="text-red-600 hover:underline">Zaregistrujte se</a>
        </p>
    </div>
</x-layouts.app>
