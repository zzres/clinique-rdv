<x-admin-layout>
    <div class="flex justify-between items-center mb-2">
        <h1 class="test-2xl font-bold">Indisponibilités</h1>
        <a href="{{ route('admin.unavailabilities.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            + Ajouter
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-left min-w-[500px]">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-sm font-semibold text-gray-600">Date</th>
                    <th class="px-6 py-3 text-sm font-semibold text-gray-600">Plage</th>
                    <th class="px-6 py-3 text-sm font-semibold text-gray-600">Motif</th>
                    <th class="px-6 py-3 text-sm font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($unavailabilities as $unavailability)
                    <tr class="border-b last:border-0">
                        <td class="px-6 py-4">{{ $unavailability->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            @if ($unavailability->start_time)
                                {{ \Carbon\Carbon::parse($unavailability->start_time)->format('H:i') }}
                                - {{ \Carbon\Carbon::parse($unavailability->end_time)->format('H:i') }}
                            @else 
                                <span class="text-gray-500">Journée entière</span>
                            @endif 
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $unavailability->reason ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.unavailabilities.destroy', $unavailability) }}" method="POST" class="inline"
                                   onsubmit="return confirm('Supprimer cette indisponibilité ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Supprimer</button> 
                            </form>
                        </td>
                    </tr>
                @empty 
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Aucune indisponibilité enregistrée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $unavailabilities->links() }}
    </div>
</x-admin-layout>