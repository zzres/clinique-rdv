<x-admin-layout>
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
    <p class="text-gray-500 mt-2">Bienvenue, {{ auth()->user()->name }} !</p>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Rendez-vous aujourd'hui</p>
            <p class="text-3xl font-bold mt-1 text-indigo-600">{{ $stats['todayCount'] }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Rendez-vous de cette semaine</p>
            <p class="text-3xl font-bold mt-1 text-indigo-600">{{ $stats['weekCount'] }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Patients uniques</p>
            <p class="text-3xl font-bold mt-1">{{ $stats['totalPatients'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-4">Prochains rendez-vous</h2>

        @forelse ($nextAppointments as $appointment)
            <div class="flex justify-between items-center py-3 border-b last:border-0">
                <div>
                    <p class="font-medium">
                        {{ $appointment->date->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                        — {{ $appointment->consultationType->name }}
                    </p>
                    <p class="text-sm text-gray-500">{{ $appointment->user->name }}</p>
                </div>
            </div>
        @empty 
            <p class="text-gray-500">Aucun rendez-vous à venir.</p>
        @endforelse
    </div>
</x-admin-layout>