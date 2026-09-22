<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
//use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'todayCount' => Appointment::whereDate('date', Carbon::today())
                ->where('status', '!=', 'cancelled')
                ->count(),
            'weekCount' => Appointment::whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('status', '!=', 'cancelled')
                ->count(),
            'totalPatients' => Appointment::distinct('user_id')->count('user_id'),
        ];

        $nextAppointments = Appointment::with(['user', 'consultationType'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) {
                $query->where('date', '>', Carbon::today())
                    ->orWhere(function ($q) {
                        $q->where('date', Carbon::today())
                          ->where('start_time', '>=', Carbon::now()->format('H:i:s'));
                    });
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'nextAppointments'));
    }
}
