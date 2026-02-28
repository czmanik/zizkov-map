<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Žižkovská noc' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900">
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="text-xl font-bold text-red-600">
                            {{ \App\Models\Setting::get('event_name', 'Žižkovská akce') }}
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="/" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-red-500 text-sm font-medium">Mapa</a>
                        <a href="/program" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-red-500 text-sm font-medium">Program</a>
                        <a href="/mista" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-red-500 text-sm font-medium">Místa</a>
                    </div>
                </div>
                <div class="flex items-center">
                    @auth
                        <a href="/profil" class="text-sm font-medium text-gray-700 mr-4">Můj program</a>
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-red-600">Odhlásit</button>
                        </form>
                    @else
                        <a href="/login" class="text-sm font-medium text-gray-700 mr-4">Přihlásit</a>
                        <a href="/register" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium">Registrace</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-white border-t mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex justify-center space-x-6 mb-4">
                @foreach(\App\Models\Page::where('is_active', true)->get() as $page)
                    <a href="/stranka/{{ $page->slug }}" class="text-gray-500 hover:text-gray-900">{{ $page->title }}</a>
                @endforeach
            </div>
            <p class="text-gray-400 text-sm">&copy; 2026 {{ \App\Models\Setting::get('event_name') }}. IČO: {{ \App\Models\Setting::get('ico') }}</p>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
