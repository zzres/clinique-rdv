<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

        {{-- Overlay sombre sur mobile quand la sidebar est ouverte --}}
        <div x-show="sidebarOpen"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"
             style="display: none;">
        </div>

        {{-- Sidebar --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-gray-900 text-white flex-shrink-0 flex flex-col transition-transform duration-200">
            <div class="p-6 text-xl font-bold border-b border-gray-800 flex justify-between items-center">
                Espace Admin
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">✕</button>
            </div>

            <nav class="p-4 space-y-1 flex-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-4 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600' : 'hover:bg-gray-800' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.availabilities.index') }}"
                   class="block px-4 py-2 rounded {{ request()->routeIs('admin.availabilities.*') ? 'bg-indigo-600' : 'hover:bg-gray-800' }}">
                    Disponibilités
                </a>
                {{-- Liens suivants activés au fur et à mesure --}}
                <a href="{{ route('admin.unavailabilities.index') }}"
                   class="block px-4 py-2 rounded {{ request()->routeIs('admin.unavailabilities.*') ? 'bg-indigo-600' : 'hover:bg-gray-800' }}">
                    Indisponibilités
                </a>

                <a href="{{ route('admin.appointments.index') }}"
                   class="block px-4 py-2 rounded {{ request()->routeIs('admin.appointments.*') ? 'bg-indigo-600' : 'hover:bg-gray-800' }}">
                    Rendez-vous
                </a>
            </nav>

            <div class="p-4 border-t border-gray-800">
                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-400 hover:text-white">
                    ← Retour au site
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-400 hover:text-white">
                        Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        {{-- Contenu principal --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Barre supérieure mobile --}}
            <div class="lg:hidden bg-white border-b p-4 flex items-center gap-4">
                <button @click="sidebarOpen = true" class="text-gray-700">☰</button>
                <span class="font-semibold">Espace Admin</span>
            </div>

            <main class="flex-1 p-4 lg:p-8 overflow-x-hidden">
                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-6">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-6">{{ session('error') }}</div>
                @endif

                {{ $slot }}
            </main>
        </div>

    </div>
</body>
</html>