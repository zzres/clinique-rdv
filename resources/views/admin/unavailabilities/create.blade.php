<x-admin-layout>
    <h1 class="text-2xl font-bold mb-6">Ajouter une indisponibilté</h1>

    <div class="bg-white rounded-lg shadow p-6 max-w-lg" x-data="{ fullDay: true }">
        <form action="{{ route('admin.unavailabilities.store') }}" method="POST">
            @csrf 

            <div class="mb-4">
                <label class="block font-medium mb-2">Date</label>
                <input type="date" name="date" value="{{ old('date') }}"
                        class="w-full border rounded-lg p-3 @error('date') border-red-500 @enderror">
                @error('date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror 
            </div>

            <div class="mb-4 flex items-center gap-2">
                <input type="checkbox" name="full_day" value="1" x-model="fullDay" id="full_day" class="w-5 h-5">
                <label for="full_day">Journée entière</label>
            </div>

            <div class="mb-4" x-show="!fullDay">
                <label class="block font-medium mb-2">De</label>
                <input type="time" name="start_time" value="{{ old('start_time') }}"
                        class="w-full border rounded-lg p-3 @error('start_time') border-red-500 @enderror">
                @error('start_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror 
            </div>

            <div class="mb-4" x-show="!fullDay">
                <label class="block font-medium mb-2">À</label>
                <input type="time" name="end_time" value="{{ old('end_time') }}"
                       class="w-full border rounded-lg p-3 @error('end_time') border-red-500 @enderror">
                @error('end_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-2">Motif (optionnel)</label>
                <input type="text" name="reason" value="{{ old('reason') }}" placeholder="Ex: Congé, formation..."
                        class="w-full border rounded-lg p-3">
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded hover:bg-indigo-700">
                    Ajouter
                </button>
                <a href="{{ route('admin.unavailabilities.index') }}" class="px-6 py-3 rounded border hover:bg-gray-50">
                    Annuler 
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>