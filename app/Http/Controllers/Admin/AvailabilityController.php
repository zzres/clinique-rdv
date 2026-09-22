<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    private function generateSlotStarts(): array 
    {
        $slots = [];
        $current = Carbon::createFromTime(7, 0);
        $end = Carbon::createFromTime(20, 0);

        while ($current->lt($end)) {
            $slots[] = $current->format('H:i');
            $current->addMinutes(30);
        }

        return $slots;
    }

    public function index()
    {
        $slots = $this->generateSlotStarts();
        $availabilities = Availability::all();

        $grid = [];
        foreach (range(0, 6) as $day) {
            foreach ($slots as $slot) {
                $grid[$day][$slot] = $this->isSlotCovered($day, $slot, $availabilities);
            }
        }

        $days = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 0 => 'Dimanche'];

        return view('admin.availabilities.index', compact('grid', 'slots', 'days'));
    }

    private function isSlotCovered(int $day, string $slotStart, $availabilities): bool 
    {
        $slotStartTime = Carbon::createFromFormat('H:i', $slotStart);
        $slotEndTime = $slotStartTime->copy()->addMinutes(30);

        foreach ($availabilities as $availability) {
            if ($availability->day_of_week != $day) {
                continue;
            }

            $availStart = Carbon::createFromFormat('H:i:s', $availability->start_time);
            $availEnd = Carbon::createFromFormat('H:i:s', $availability->end_time);

            if ($slotStartTime->gte($availStart) && $slotEndTime->lte($availEnd)) {
                return true;
            }
        }

        return false;
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'grid' => 'array',
            'grid.*' => 'array',
        ]);

        $selectedByDay = $validated['grid'] ?? [];

        Availability::query()->delete();

        foreach ($selectedByDay as $day => $selectedSlots) {
            sort($selectedSlots);
            $ranges = $this->mergeSlotsIntoRanges($selectedSlots);

            foreach ($ranges as $range) {
                Availability::create([
                    'day_of_week' => $day,
                    'start_time' => $range['start'],
                    'end_time' => $range['end'],
                ]);
            }
        }

        return redirect()->route('admin.availabilities.index')->with('success', 'Disponibilités mises à jour.');
    }

    private function mergeSlotsIntoRanges(array $slots): array 
    {
        $ranges = [];
        $current = null;

        foreach ($slots as $slot) {
            $slotStart = Carbon::createFromFormat('H:i', $slot);
            $slotEnd = $slotStart->copy()->addMinutes(30);

            if ($current === null) {
                $current = ['start' => $slotStart, 'end' => $slotEnd];
            } elseif ($slotStart->equalTo($current['end'])) {
                $current['end'] = $slotEnd;
            } else {
                $ranges[] = ['start' => $current['start']->format('H:i:s'), 'end' => $current['end']->format('H:i:s')];
                $current = ['start' => $slotStart, 'end' => $slotEnd];
            }
        }

        if ($current) {
            $ranges[] = ['start' => $current['start']->format('H:i:s'), 'end' => $current['end']->format('H:i:s')];
        }

        return $ranges;
    }
}
