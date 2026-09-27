<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RideController extends Controller
{
    /**
     * List rides assigned to this driver with optional status filtering.
     */
    public function index(Request $request): View
    {
        $driver = auth()->user()->driver;

        if (! $driver) {
            abort(403, 'Driver profile not found.');
        }

        $query = $driver->rides()->with('passenger.user')->latest();

        $statusFilter = $request->get('status');
        if ($statusFilter && in_array($statusFilter, ['assigned', 'accepted', 'in_progress', 'completed', 'cancelled'])) {
            $query->where('status', $statusFilter);
        }

        $rides = $query->paginate(10)->withQueryString();

        return view('driver.rides.index', compact('rides', 'statusFilter'));
    }

    /**
     * Show a single ride detail for trip execution.
     */
    public function show(Ride $ride): View
    {
        abort_unless($ride->driver_id === auth()->user()->driver?->id, 403);
        $ride->load('passenger.user');

        return view('driver.rides.show', compact('ride'));
    }

    /**
     * Accept an assigned ride.
     */
    public function accept(Ride $ride): RedirectResponse
    {
        $driver = auth()->user()->driver;
        abort_unless($driver && $ride->driver_id === $driver->id, 403);

        if ($ride->status !== 'assigned') {
            return back()->withErrors(['status' => 'Only assigned rides can be accepted.']);
        }

        $ride->update(['status' => 'accepted']);

        return redirect()->route('driver.rides.show', $ride)
            ->with('success', 'Ride accepted! Head to the pickup location.');
    }

    /**
     * Update ride status (assigned → accepted → in_progress → completed).
     */
    public function updateStatus(Request $request, Ride $ride): RedirectResponse
    {
        $driver = auth()->user()->driver;
        abort_unless($driver && $ride->driver_id === $driver->id, 403);

        $data = $request->validate([
            'status' => ['required', 'in:accepted,in_progress,completed'],
        ]);

        $allowedTransitions = [
            'assigned'    => ['accepted', 'in_progress'],
            'accepted'    => ['in_progress'],
            'in_progress' => ['completed'],
        ];

        if (! in_array($data['status'], $allowedTransitions[$ride->status] ?? [], true)) {
            return back()->withErrors(['status' => 'That status change is not permitted for this trip.']);
        }

        $updates = ['status' => $data['status']];

        if ($data['status'] === 'in_progress') {
            $updates['pickup_at'] = now();
            $driver->update(['availability' => 'on_trip']);
        }

        if ($data['status'] === 'completed') {
            $updates['completed_at'] = now();
            $updates['payment_status'] = 'paid';
            $updates['paid_at'] = now();
            $ride->payment()->updateOrCreate([], [
                'amount'         => $ride->fare,
                'payment_method' => $ride->payment_method,
                'payment_status' => 'paid',
                'paid_at'        => now(),
            ]);
            // Free up the driver
            $driver->update(['availability' => 'available']);
        }

        $ride->update($updates);

        $msg = $data['status'] === 'completed'
            ? 'Trip completed and fare payment verified! You are now available for new rides.'
            : 'Passenger picked up! Trip is now in progress.';

        return redirect()->route('driver.rides.show', $ride)->with('success', $msg);
    }
}
