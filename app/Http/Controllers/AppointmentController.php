<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ConsultationType;
use App\Services\AppointmentAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = auth()->user()->appointments()
            ->with('consultationType')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    public function cancel(Appointment $appointment)
    {
        if ($appointment->user_id !== auth()->id()) {
            abort(403);
        }

        $appointmentDateTime = Carbon::parse($appointment->date->format('Y-m-d') . ' ' . $appointment->start_time);

        if ($appointmentDateTime->isPast()) {
            return back()->with('error', 'Impossible d\'annuler un rendez-vous déjà passé.');
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('seccess', 'Rendez-vous annulé.');
    }

    public function create()
    {
        $consultationTypes = ConsultationType::all();

        return view('appointments.create', compact('consultationTypes'));
    }

    public function getSlots(Request $request, AppointmentAvailabilityService $availabilityService)
    {
        $validated = $request->validate([
            'consultation_type_id' => 'required|exists:consultation_types,id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $consultationType = ConsultationType::findOrFail($validated['consultation_type_id']);
        $date = Carbon::parse($validated['date']);

        $slots = $availabilityService->getAvailableSlots($date, $consultationType);

        return response()->json([
            'slots' => array_map(fn($slot) => [
                'start' => $slot['start']->format('H:i'),
                'end' => $slot['end']->format('H:i'),
            ], $slots),
        ]);
    }

    public function store(Request $request, AppointmentAvailabilityService $availabilityService)
    {
        $validated = $request->validate([
            'consultation_type_id' => 'required|exists:consultation_types,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ]);

        $consultationType = ConsultationType::findOrFail($validated['consultation_type_id']);
        $date = Carbon::parse($validated['date']);

        //Revérification côté serveur : le créneau demandé est-il toujours disponible ?
        $availableSlots = $availabilityService->getAvailableSlots($date, $consultationType);

        $requestedStart = $validated['start_time'];
        $slotStillAvailable = collect($availableSlots)->contains(
            fn($slot) => $slot['start']->format('H:i') === $requestedStart 
        );

        if (!$slotStillAvailable) {
            return back()->with('error', 'Ce créneau vient d\'être réservé par quelqu\'un d\'autre. Merci d\'en choisir un autre.');
        }

        $startDateTime = Carbon::parse($date->format('Y-m-d') . '' . $requestedStart);
        $endDateTime = $startDateTime->copy()->addMinutes($consultationType->duration_minutes);

        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'consultation_type_id' => $consultationType->id,
            'date' => $date,
            'start_time' => $startDateTime->format('H:i:s'),
            'end_time' => $endDateTime->format('H:i:s'),
            'status' => 'confirmed',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('appointments.confirmation', $appointment)
            ->with('success', 'Rendez-vous confirmé !');
    }

    public function confirmation(Appointment $appointment)
    {
        if ($appointment->user_id !== auth()->id()) {
            abort(403);
        }

        $appointment->load('consultationType');

        return view('appointments.confirmation', compact('appointment'));
    }
}
