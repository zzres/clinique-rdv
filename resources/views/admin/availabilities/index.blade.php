<x-admin-layout>
    <h1 class="text-2xl font-bold mb-6">Disponibilités hebdomadaires</h1>

    <p class="text-gray-500 mb-6">Coche les créneaux de 30 minutes où tu es disponible pour recevoir des patients.</p>

    <form action="{{ route('admin.availabilities.update') }}" method="POST">
        @csrf 

        <div class="bg-white rounded-lg shadow p-4 overflow-x-auto">
            <table class="min-w-[700]">
                <thead>
                    <tr>
                        <th class="p-2 text-sm text-gray-500 text-left">Heure</th>
                        @foreach ($days as $dayNum => $dayName)
                            <th class="p-2 text-sm font-semibold text-center">{{ $dayName }}</th>
                        @endforeach 
                    </tr>
                </thead>
                <tbody>
                    @foreach ($slots as $slot)
                        <tr class="border-t">
                            <td class="p-2 text-sm text-gray-500">{{ $slot }}</td>
                            @foreach ($days as $dayNum => $dayName)
                                <td class="p-2 tex-center">
                                    <input 
                                        type="checkbox"
                                        name="grid[{{ $dayNum }}][]"
                                        value="{{ $slot }}"
                                        {{ $grid[$dayNum][$slot] ? 'checked' : '' }}
                                        class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500"
                                    >
                                </td>
                            @endforeach 
                        </tr>
                    @endforeach 
                </tbody>
            </table>
        </div>

        <button type="submit" class="mt-6 bg-indigo-600 text-white px-6 py-3 rounded hover:bg-indigo-700">
            Enregistrer les disponibilités 
        </button>
    </form>
</x-admin-layout>