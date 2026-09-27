<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Ride;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RideController extends Controller
{
    /**
     * List all rides with filters.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->get('status');
        $paymentFilter = $request->get('payment_status');
        $search = $request->get('search');

        $query = Ride::with(['passenger.user', 'driver.user'])->latest();

        if ($statusFilter && in_array($statusFilter, ['pending', 'assigned', 'accepted', 'in_progress', 'completed', 'cancelled'])) {
            $query->where('status', $statusFilter);
        }

        if ($paymentFilter && in_array($paymentFilter, ['paid', 'unpaid', 'waived'])) {
            $query->where('payment_status', $paymentFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pickup_location', 'like', "%{$search}%")
                  ->orWhere('dropoff_location', 'like', "%{$search}%")
                  ->orWhereHas('passenger.user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('driver.user', function ($dq) use ($search) {
                      $dq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $rides = $query->paginate(15)->withQueryString();

        $drivers = Driver::where('status', 'active')->with('user')->get();

        return view('admin.rides.index', compact('rides', 'statusFilter', 'paymentFilter', 'search', 'drivers'));
    }

    /**
     * Show a single ride with assign and payment forms.
     */
    public function show(Ride $ride): View
    {
        $ride->load(['passenger.user', 'driver.user']);

        $drivers = Driver::where('status', 'active')
            ->where('availability', 'available')
            ->with('user')
            ->get();

        return view('admin.rides.show', compact('ride', 'drivers'));
    }

    /**
     * Assign a driver to a ride.
     */
    public function assign(Request $request, Ride $ride): RedirectResponse
    {
        $data = $request->validate([
            'driver_id' => ['required', 'exists:drivers,id'],
        ]);

        $driver = Driver::whereKey($data['driver_id'])
            ->where('status', 'active')
            ->where('availability', 'available')
            ->first();

        if (! $driver) {
            return back()->withErrors(['driver_id' => 'That driver is not currently available for dispatch.']);
        }

        if ($ride->driver_id && $ride->driver_id !== $driver->id) {
            Driver::whereKey($ride->driver_id)->update(['availability' => 'available']);
        }

        $ride->update([
            'driver_id' => $driver->id,
            'status'    => 'assigned',
        ]);

        // Mark driver as on_trip
        $driver->update(['availability' => 'on_trip']);

        return redirect()->route('admin.rides.show', $ride)
            ->with('success', 'Driver ' . $driver->user->name . ' assigned successfully.');
    }

    /**
     * Cancel a ride.
     */
    public function cancel(Ride $ride): RedirectResponse
    {
        if ($ride->driver_id) {
            Driver::where('id', $ride->driver_id)
                ->update(['availability' => 'available']);
        }

        $ride->update(['status' => 'cancelled']);

        return redirect()->route('admin.rides.show', $ride)
            ->with('success', 'Ride request has been cancelled.');
    }

    /**
     * Change a ride status from the admin panel.
     */
    public function updateStatus(Request $request, Ride $ride): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,assigned,accepted,in_progress,completed,cancelled'],
        ]);

        if ($data['status'] === 'cancelled') {
            return $this->cancel($ride);
        }

        if (in_array($data['status'], ['assigned', 'accepted', 'in_progress']) && ! $ride->driver_id) {
            return back()->withErrors(['status' => 'Assign a driver before setting this ride to assigned, accepted, or in progress.']);
        }

        $updates = ['status' => $data['status']];

        if ($data['status'] === 'in_progress' && ! $ride->pickup_at) {
            $updates['pickup_at'] = now();
        }

        if ($data['status'] === 'completed') {
            if (! $ride->completed_at) {
                $updates['completed_at'] = now();
            }
            if ($ride->payment_status === 'unpaid') {
                $updates['payment_status'] = 'paid';
                $updates['paid_at'] = now();
            }
            if ($ride->driver_id) {
                Driver::whereKey($ride->driver_id)->update(['availability' => 'available']);
            }
        }

        $ride->update($updates);

        $ride->payment()->updateOrCreate([], [
            'amount'         => $ride->fare,
            'payment_method' => $ride->payment_method,
            'payment_status' => $ride->payment_status,
            'paid_at'        => $ride->paid_at,
        ]);

        return redirect()->route('admin.rides.show', $ride)
            ->with('success', 'Ride status updated successfully.');
    }

    /**
     * Reconcile payment status and fare from admin panel.
     */
    public function updatePayment(Request $request, Ride $ride): RedirectResponse
    {
        $data = $request->validate([
            'fare'           => ['required', 'numeric', 'min:0'],
            'payment_status' => ['required', 'in:unpaid,paid,waived'],
            'payment_method' => ['required', 'in:campus_card,student_wallet,cash,online'],
        ]);

        $updates = [
            'fare'           => $data['fare'],
            'payment_status' => $data['payment_status'],
            'payment_method' => $data['payment_method'],
        ];

        if ($data['payment_status'] === 'paid' && ! $ride->paid_at) {
            $updates['paid_at'] = now();
        } elseif ($data['payment_status'] === 'unpaid') {
            $updates['paid_at'] = null;
        }

        $ride->update($updates);

        $ride->payment()->updateOrCreate([], [
            'amount'         => $ride->fare,
            'payment_method' => $ride->payment_method,
            'payment_status' => $ride->payment_status,
            'paid_at'        => $ride->paid_at,
        ]);

        return redirect()->route('admin.rides.show', $ride)
            ->with('success', 'Ride payment information updated.');
    }
}
