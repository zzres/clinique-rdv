<x-admin-layout>
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold">Rendez-vous du {{ $date->format('d/m/Y') }}</h1>

        <form method="GET" class="flex items-center gap-2">
            <input type="date" name="date" value="{{ $date->format('Y-m-d') }}"
                   onchange="this.form.submit()"
                   class="border rounded-lg px-3 py-2">
        </form>
    </div>

    @if ($appointments->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
            Aucun rendez-vous ce jour-là.
        </div>
    @else 
        <div class="space-y-3">
            @foreach ($appointments as $appointment)
                <div class="bg-white rounded-lg shadow p-5 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3
                    {{ $appointment->status === 'cancelled' ? 'opacity-50' : '' }}">
                    <div>
                        <p class="font-semibold">
                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                            - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                            — {{ $appointment->consultationType->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Patient : {{ $appointment->user->name }} ({{ $appointment->user->email }})
                        </p>
                        @if ($appointment->notes)
                            <p class="text-sm text-gray-500 mt-1">Motif : {{ $appointment->notes }}</p>
                        @endif 
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-xs px-2 py-1 rounded-full
                            {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ $appointment->status === 'cancelled' ? 'Annulé' : 'Confirmé' }}
                        </span>

                        @if ($appointment->status !== 'cancelled')
                            <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST"
                                  onsubmit="return confirm('Annuler ce rendez-vous ?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-red-600 text-sm hover:underline">Annuler</button> 
                            </form>
                        @endif 
                    </div>
                </div>
            @endforeach 
        </div>
    @endif 
</x-admin-layout>