<x-layouts.app>
    <div x-data="{ openTerms: false }" class="max-w-md mx-auto mt-20 p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-6 text-center">Registrace návštěvníka</h1>

        <form method="POST" action="/register" class="space-y-4 mb-6">
            @csrf
            <div>
                <label for="nickname" class="block text-sm font-medium text-gray-700">Přezdívka</label>
                <input type="text" name="nickname" id="nickname" value="{{ old('nickname') }}" required autofocus
                    class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-red-500 focus:ring-red-500 p-2">
                @error('nickname')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-red-500 focus:ring-red-500 p-2">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Heslo</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-red-500 focus:ring-red-500 p-2">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Potvrzení hesla</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-red-500 focus:ring-red-500 p-2">
            </div>

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input type="checkbox" name="terms" id="terms" required
                        class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>
                <div class="ml-3 text-sm">
                    <label for="terms" class="font-medium text-gray-700">Souhlasím s <button type="button" @click="openTerms = true" class="text-red-600 hover:underline">podmínkami webu</button></label>
                    @error('terms')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="w-full bg-red-600 text-white p-3 rounded-md hover:bg-red-700 transition font-bold">
                Zaregistrovat se
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
                <span>Registrovat přes Google</span>
            </a>

            <a href="/auth/facebook" class="flex items-center justify-center gap-3 w-full bg-[#1877F2] text-white p-3 rounded-md hover:bg-blue-600 transition">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                <span>Registrovat přes Facebook</span>
            </a>
        </div>

        <div class="mt-8 text-center">
            <a href="/login" class="text-sm text-red-600 hover:underline">Již máte účet? Přihlaste se</a>
        </div>

        <!-- Terms Modal -->
        <div x-show="openTerms" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openTerms" @click="openTerms = false" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="openTerms" class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <h3 class="text-lg font-bold leading-6 text-gray-900">Podmínky webu</h3>
                        <div class="mt-4 prose prose-sm max-h-60 overflow-y-auto">
                            <p>Toto jsou obecné podmínky používání webu Žižkovská noc.</p>
                            <p>1. Registrací souhlasíte s uchováním vašich údajů pro účely fungování vašeho osobního programu.</p>
                            <p>2. Vaše údaje nebudou poskytnuty třetím stranám.</p>
                            <p>3. Vyhrazujeme si právo na změnu programu.</p>
                            <p>Zde bude doplněn finální text podmínek.</p>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6">
                        <button type="button" @click="openTerms = false" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                            Zavřít
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
