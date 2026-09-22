<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unavailability;
use Illuminate\Http\Request;

class UnavailabilityController extends Controller
{
    public function index()
    {
        $unavailabilities = Unavailability::orderBy('date', 'desc')->paginate(15);

        return view('admin.unavailabilities.index', compact('unavailabilities'));
    }

    public function create()
    {
        return view('admin.unavailabilities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'full_day' => 'nullable|boolean',
            'start_time' => 'required_if:full_day,false|nullable|date_format:H:i',
            'end_time' => 'required_if:full_day,false|nullable|date_format:H:i|after:start_time',
            'reason' => 'nullable|string|max:255',
        ]);

        Unavailability::create([
            'date' => $validated['date'],
            'start_time' => $request->boolean('full_day') ? null : ($validated['start_time'] ?? null),
            'end_time' => $request->boolean('full_day') ? null : ($validated['end_time'] ?? null),
            'reason' => $validated['reason'] ?? null,
        ]);

        return redirect()->route('admin.unavailabilities.index')->with('success', 'Indisponilité ajoutée.');
    }

    public function destroy(Unavailability $unavailability)
    {
        $unavailability->delete();

        return redirect()->route('admin.unavailabilities.index')->with('Indisponibilité supprimée.');
    }
}
