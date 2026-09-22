<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8"
        x-data="{
            consultationTypeId: '',
            date: '',
            slots: [],
            selectedSlots: null,
            loading: false,
            notes: '',
            
            fetchSlots() {
                if (!this.consultationTypeId || !this.date) {
                    this.slots = [];
                    return;
                }
                    
                this.loading = true;
                this.selectedSlot = null;
                
                fetch(`{{ route('appointments.slots') }}?consultation_type_id=${this.consultationTypeId}&date=${this.date}`)
                    .then(res => res.json())
                    .then(data => {
                        this.slots = data.slots;
                        this.loading = false;
                    });
            }
        }">

        <h1 class="text-3xl font-bold mb-6">Prendre rendez-vous</h1>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf 

            <div class="mb-4">
                <label class="block font-medium mb-2">Type de consultation</label>
                <select name="consultation_type_id" x-model="consultationTypeId" @change="fetchSlots()"
                        class="w-full border rounded-lg p-3">
                    <option value="">-- Choisir --</option>
                    @foreach ($consultationTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->duration_minutes }} min)</option>
                    @endforeach 
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-2">Date souhaitée</label>
                <input type="date" name="date" x-model="date" @change="fetchSlots()"
                    min="{{ now()->format('Y-m-d') }}"
                    class="w-full border rounded-lg p-3">
            </div>

            <div class="mb-4" x-show="date && consultationTypeId">
                <label class="block font-medium mb-2">Créneaux disponibles</label>

                <p x-show="loading" class="text-gray-500">Recherche des créneaux...</p>

                <p x-show="!loading && slots.length === 0" class="text-gray-500">
                    Aucun créneau disponible ce jour-là. Essaie une autre date.
                </p>

                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2" x-show="!loading">
                    <template x-for="slot in slots" :key="slot.start">
                        <button
                            type="button"
                            @click="selectedSlot = slot.start"
                            :class="selectedSlot === slot.start ? 'bg-indigo-600 text-white' : 'bg-gray-100 hover:bg-gray-200'"
                            class="py-2 rounded text-sm"
                            x-text="slot.start"
                        ></button>
                    </template>
                </div>
            </div>

            <input type="hidden" name="start_time" :value="selectedSlot">

            <div class="mb-6" x-show="selectedSlot">
                <label class="block font-medium mb-2">Motif de consultation (optionnel)</label>
                <textarea name="note" x-model="notes" rows="3" class="w-full border rounded-lg p-3"></textarea>
            </div>

            <button type="submit" :disabled="!selectedSlot"
                    class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-lg hover:gb-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                Confirmer le rendez-vous
            </button>
        </form>  
    </div>
</x-app-layout>