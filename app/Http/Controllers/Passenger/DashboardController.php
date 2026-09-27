<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $passenger = auth()->user()->passenger;

        if (! $passenger) {
            // Self-heal if user doesn't have a passenger profile yet
            $passenger = \App\Models\Passenger::create([
                'user_id' => auth()->id(),
                'status'  => 'active',
            ]);
        }

        $allRides = $passenger->rides()->with('driver.user')->latest()->get();

        $activeRide = $allRides->first(function ($r) {
            return in_array($r->status, ['pending', 'assigned', 'accepted', 'in_progress']);
        });

        $stats = [
            'total_rides'     => $allRides->count(),
            'active_rides'    => $allRides->whereIn('status', ['pending', 'assigned', 'accepted', 'in_progress'])->count(),
            'completed_rides' => $allRides->where('status', 'completed')->count(),
            'total_spent'     => $allRides->where('status', 'completed')->sum('fare'),
        ];

        $recentRides = $allRides->take(5);

        return view('passenger.dashboard', compact('passenger', 'activeRide', 'stats', 'recentRides'));
    }
}
