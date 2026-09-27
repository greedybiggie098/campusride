<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use App\Models\AppSetting;
use App\Models\Ride;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RideController extends Controller
{
    /**
     * Standard university campus transit locations.
     */
    protected array $campusLocations = [
        'Main Library Quad',
        'Student Union Center',
        'North Residence Halls',
        'South Sports Pavilion',
        'Science & Lab Complex',
        'Engineering Complex Block B',
        'Campus Health & Wellness Center',
        'Metro Transit Plaza Gate',
        'Fine Arts Auditorium',
        'East Gate Parking Lot',
    ];

    /**
     * Show the ride request form.
     */
    public function create(): View
    {
        $locations = $this->campusLocations;
        return view('passenger.rides.create', compact('locations'));
    }

    /**
     * Store a new ride request.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'pickup_location'  => ['required', 'string', 'max:255'],
            'dropoff_location' => ['required', 'string', 'max:255', 'different:pickup_location'],
            'payment_method'   => ['nullable', 'in:campus_card,student_wallet,cash'],
            'is_priority'      => ['nullable', 'boolean'],
            'notes'            => ['nullable', 'string', 'max:500'],
        ]);

        $passenger = auth()->user()->passenger;

        if (! $passenger) {
            $passenger = Passenger::create([
                'user_id' => auth()->id(),
                'status'  => 'active',
            ]);
        }

        $isPriority = $request->boolean('is_priority');
        $baseFare = (float) AppSetting::getValue('base_fare', '2.50');
        $priorityFare = (float) AppSetting::getValue('priority_fare', '3.50');
        $fare = $isPriority ? $priorityFare : $baseFare;

        $ride = Ride::create([
            'passenger_id'     => $passenger->id,
            'pickup_location'  => $data['pickup_location'],
            'dropoff_location' => $data['dropoff_location'],
            'fare'             => $fare,
            'payment_method'   => $data['payment_method'] ?? 'campus_card',
            'payment_status'   => 'unpaid',
            'is_priority'      => $isPriority,
            'notes'            => $data['notes'] ?? null,
            'status'           => 'pending',
            'requested_at'     => now(),
        ]);

        $ride->payment()->create([
            'amount'         => $fare,
            'payment_method' => $ride->payment_method,
            'payment_status' => 'unpaid',
        ]);

        return redirect()->route('passenger.rides.index')
            ->with('success', 'Ride request placed! Campus dispatch is finding an available shuttle.');
    }

    /**
     * List the passenger's rides with optional status filter.
     */
    public function index(Request $request): View
    {
        $passenger = auth()->user()->passenger;

        if (! $passenger) {
            $passenger = Passenger::create(['user_id' => auth()->id()]);
        }

        $query = $passenger->rides()->with('driver.user')->latest();

        $statusFilter = $request->get('status');
        if ($statusFilter && in_array($statusFilter, ['pending', 'assigned', 'accepted', 'in_progress', 'completed', 'cancelled'])) {
            $query->where('status', $statusFilter);
        }

        $rides = $query->paginate(10)->withQueryString();

        return view('passenger.rides.index', compact('rides', 'statusFilter'));
    }

    /**
     * Show a single ride detail and digital receipt.
     */
    public function show(Ride $ride): View
    {
        abort_unless($ride->passenger_id === auth()->user()->passenger?->id, 403);

        $ride->load('driver.user');

        return view('passenger.rides.show', compact('ride'));
    }

    /**
     * Cancel a pending ride.
     */
    public function cancel(Ride $ride): RedirectResponse
    {
        abort_unless($ride->passenger_id === auth()->user()->passenger?->id, 403);

        if ($ride->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending ride requests can be cancelled.']);
        }

        $ride->update(['status' => 'cancelled']);

        return redirect()->route('passenger.rides.index')
            ->with('success', 'Your ride request has been cancelled.');
    }
}
