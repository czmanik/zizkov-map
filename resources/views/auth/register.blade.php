<x-layouts.app>
    <div class="max-w-md mx-auto mt-20 p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-6 text-center">Registrace návštěvníka</h1>

        <p class="text-gray-600 mb-8 text-center">
            Pro uložení vašeho osobního programu se prosím zaregistrujte pomocí sociálních sítí.
        </p>

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
    </div>
</x-layouts.app>
