<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Mes rendez-vous</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif 

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
        @endif 

        @if ($appointments->isEmpty())
            <div class="text-center py-12 text-gray-500">
                <p>Tu n'as pas encore de rendez-vous.</p>
                <a href="{{ route('appointments.create') }}" class="text-indigo-600 hover:underline">Prendre rendez-vous</a>
            </div>
        @else 
            <div class="space-y-4">
                @foreach ($appointments as $appointment)
                    @php 
                        $appointmentDateTime = \Carbon\Carbon::parse($appointment->date->format('Y-m-d') . ' ' . $appointment->start_time);
                        $isPast = $appointmentDateTime->isPast();
                        $isCancelled = $appointment->status === 'cancelled';
                    @endphp

                    <div class="bg-gray-50 rounded-lg p-5 {{ $isPast || $isCancelled ? 'opacity-60' : '' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold">{{ $appointment->consultationType->name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $appointment->date->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                                </p>
                                @if ($appointment->notes)
                                    <p class="text-sm text-gray-500 mt-1">{{ $appointment->notes }}</p>
                                @endif 
                            </div>

                            <span class="text-xs px-2 py-1 rounded-full
                                {{ $isCancelled ? 'bg-red-100 text-red-700' : '' }}
                                {{ !$isCancelled && $isPast ? 'bg-gray-200 text-gray-600' : '' }}
                                {{ !$isCancelled && !$isPast ? 'bg-green-100 text-green-700' : '' }}">
                                {{ $isCancelled ? 'Annulé' : ($isPast ? 'Passé' : 'A venir') }}
                            </span>
                        </div>

                        @if (!$isPast && !$isCancelled)
                            <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="mt-3"
                                  onsubmit="return confirm('Annuler ce rendez-vous ?')">
                                @csrf 
                                @method('PATCH')
                                <button type="submit" class="text-red-600 text-sm hover:underline">Annuler</button>
                            </form>
                        @endif 
                    </div>
                @endforeach 
            </div>

            <div class="mt-6">
                {{ $appointments->links() }}
            </div>
        @endif 
    </div>
</x-app-layout>