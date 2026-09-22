<?php 

namespace App\Services;

use App\Models\Availability;
use App\Models\Unavailability;
use App\Models\Appointment;
use App\Models\ConsultationType;
use Carbon\Carbon;

class AppointmentAvailabilityService
{
    public function getAvailableSlots(Carbon $date, ConsultationType $consultationType): array 
    {
        $theoreticalSlots = $this->generateTheoreticalSlots($date, $consultationType->duration_minutes);

        $unavailabilities = Unavailability::whereDate('date', $date)->get();
        $appointments = Appointment::whereDate('date', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        $now = Carbon::now();

        return array_values(array_filter($theoreticalSlots, function ($slot) use ($unavailabilities, $appointments, $now) {
            if ($slot['start']->lt($now)) {
                return false;
            }
            
            return !$this->isSlotBlocked($slot['start'], $slot['end'], $unavailabilities, $appointments);
        }));
    }

    private function generateTheoreticalSlots(Carbon $date, int $durationMinutes): array 
    {
        $dayOfWeek = $date->dayOfWeek;

        $availabilities = Availability::where('day_of_week', $dayOfWeek)->get();

        $slots = [];

        foreach ($availabilities as $availability) {
            $blockStart = Carbon::parse($date->format('Y-m-d') . ' ' . $availability->start_time);
            $blockEnd = Carbon::parse($date->format('Y-m-d') . ' ' . $availability->end_time);

            $current = $blockStart->copy();

            while ($current->copy()->addMinutes($durationMinutes)->lte($blockEnd)) {
                $slots[] = [
                    'start' => $current->copy(),
                    'end' => $current->copy()->addMinutes($durationMinutes),
                ];

                $current->addMinutes($durationMinutes);
            }
        }

        return $slots;
    }

    private function isSlotBlocked(Carbon $slotStart, Carbon $slotEnd, $unavailabilities, $appointments): bool 
    {
        foreach ($unavailabilities as $unavailability) {
            if (is_null($unavailability->start_time) || is_null($unavailability->end_time)) {
                return true;
            }

            $blockStart = Carbon::parse($unavailability->date->format('Y-m-d') . ' ' . $unavailability->start_time);
            $blockEnd = Carbon::parse($unavailability->date->format('Y-m-d') . ' ' . $unavailability->end_time);

            if ($this->overlaps($slotStart, $slotEnd, $blockStart, $blockEnd)) {
                return true;
            }
        }

        foreach ($appointments as $appointment) {
            $appointmentStart = Carbon::parse($appointment->date->format('Y-m-d') . ' ' . $appointment->start_time);
            $appointmentEnd = Carbon::parse($appointment->date->format('Y-m-d') . ' ' . $appointment->end_time);

            if ($this->overlaps($slotStart, $slotEnd, $appointmentStart, $appointmentEnd)) {
                return true;
            }
        }

        return false;
    }

    private function overlaps(Carbon $startA, Carbon $endA, Carbon $startB, Carbon $endB): bool 
    {
        return $startA->lt($endB) && $endA->gt($startB);
    }
}