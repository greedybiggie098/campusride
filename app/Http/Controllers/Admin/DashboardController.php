<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Driver;
use App\Models\DriverApplication;
use App\Models\Passenger;
use App\Models\Ride;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $pendingRides = Ride::with(['passenger.user', 'driver.user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $recentRides = Ride::with(['passenger.user', 'driver.user'])
            ->latest()
            ->take(8)
            ->get();

        $availableDrivers = Driver::where('status', 'active')
            ->where('availability', 'available')
            ->with('user')
            ->get();

        $allDrivers = Driver::with('user')->get();

        $stats = [
            'pending'             => Ride::where('status', 'pending')->count(),
            'assigned'            => Ride::whereIn('status', ['assigned', 'accepted'])->count(),
            'active'              => Ride::where('status', 'in_progress')->count(),
            'completed'           => Ride::where('status', 'completed')->count(),
            'total_rides'         => Ride::count(),
            'total_revenue'       => Ride::where('status', 'completed')->sum('fare'),
            'students'            => Passenger::count(),
            'drivers'             => $allDrivers->count(),
            'avail_drivers'       => $availableDrivers->count(),
            'inquiries'           => ContactMessage::where('status', 'new')->count(),
            'pending_driver_apps' => DriverApplication::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('pendingRides', 'recentRides', 'availableDrivers', 'allDrivers', 'stats'));
    }
}
