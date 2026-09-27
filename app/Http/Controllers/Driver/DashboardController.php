<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $driver = auth()->user()->driver;

        if (! $driver) {
            $driver = Driver::create([
                'user_id'      => auth()->id(),
                'availability' => 'available',
                'status'       => 'active',
            ]);
        }

        $activeRides = $driver->rides()
            ->whereIn('status', ['assigned', 'accepted', 'in_progress'])
            ->with('passenger.user')
            ->latest()
            ->get();

        $completedRides = $driver->rides()
            ->where('status', 'completed')
            ->latest()
            ->get();

        $stats = [
            'active_trips'     => $activeRides->count(),
            'total_completed'  => $completedRides->count(),
            'total_fare'       => $completedRides->sum('fare'),
            'today_completed'  => $completedRides->where('completed_at', '>=', today())->count(),
        ];

        return view('driver.dashboard', compact('driver', 'activeRides', 'stats'));
    }

    /**
     * Toggle driver shift availability (available <-> off_duty).
     */
    public function toggleAvailability(): RedirectResponse
    {
        $driver = auth()->user()->driver;

        if (! $driver) {
            return back()->withErrors(['error' => 'Driver profile not found.']);
        }

        // If driver has an active trip in progress, don't allow off_duty
        $hasActiveTrip = $driver->rides()->where('status', 'in_progress')->exists();
        if ($hasActiveTrip) {
            return back()->withErrors(['error' => 'You cannot change shift status while a trip is in progress.']);
        }

        $newStatus = ($driver->availability === 'available') ? 'off_duty' : 'available';
        $driver->update(['availability' => $newStatus]);

        $label = $newStatus === 'available' ? 'You are now ONLINE & ready for campus trips.' : 'You are now OFF DUTY.';
        return back()->with('success', $label);
    }
}
