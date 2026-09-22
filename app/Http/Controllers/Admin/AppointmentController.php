<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date) : Carbon::today();

        $appointments = Appointment::with(['user', 'consultationType'])
            ->whereDate('date', $date)
            ->orderBy('start_time')
            ->get();

        return view('admin.appointments.index', compact('appointments', 'date'));
    }

    public function cancel(Appointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Rendez-vous annulé.');
    }
}
