<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6 text-center font-medium">
                {{ session('success') }}
            </div>
        @endif 

        <div class="text-center mb-8">
            <div class="bg-5xl mb-4">✅</div>
            <h1 class="text-2xl font-bold">Rendez-vous confirmé</h1>
            <p class="text-gray-500 mt-1">Référence n°{{ $appointment->id }}</p>
        </div>

        <div class="bg-gray-50 rounded-lg p-6 mb-6 space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-500">Type de consultation</span>
                <span class="font-medium">{{ $appointment->consultationType->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Date</span>
                <span class="font-medium">{{ $appointment->date->format('d/m/Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Heure</span>
                <span class="font-medium">
                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                    - 
                    {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                </span>
            </div>
            @if ($appointment->notes)
                <div class="pt-3 border-t">
                    <span class="text-gray-500 block mb-1">Motif</span>
                    <p>{{ $appointment->notes }}</p>
                </div>
            @endif 
        </div>

        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded hover:bg-indigo-700">
                Retour à l'accueil
            </a>
        </div>
    </div>
</x-app-layout>