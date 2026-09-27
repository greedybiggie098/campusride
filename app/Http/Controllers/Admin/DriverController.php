<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use App\Notifications\WelcomeUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DriverController extends Controller
{
    /**
     * List all drivers with vehicle & availability status.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $availFilter = $request->get('availability');

        $query = Driver::with(['user', 'rides'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('license_number', 'like', "%{$search}%")
                  ->orWhere('plate_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_model', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($availFilter && in_array($availFilter, ['available', 'on_trip', 'off_duty'])) {
            $query->where('availability', $availFilter);
        }

        $drivers = $query->paginate(10)->withQueryString();

        return view('admin.drivers.index', compact('drivers', 'search', 'availFilter'));
    }

    /**
     * Show form to create new driver.
     */
    public function create(): View
    {
        return view('admin.drivers.create');
    }

    /**
     * Store a newly created driver.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email:rfc,dns', 'max:255', 'ends_with:@gmail.com', 'unique:users'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'password'       => ['required', 'string', 'min:8'],
            // Driver & Vehicle details
            'license_number' => ['required', 'string', 'max:50'],
            'vehicle_type'   => ['required', 'string', 'max:50'],
            'vehicle_model'  => ['required', 'string', 'max:100'],
            'plate_number'   => ['required', 'string', 'max:20'],
            'availability'   => ['required', 'in:available,off_duty'],
            'status'         => ['required', 'in:active,inactive'],
        ]);

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'phone'             => $data['phone'] ?? null,
            'role'              => 'driver',
            'password'          => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        Driver::create([
            'user_id'        => $user->id,
            'license_number' => $data['license_number'],
            'vehicle_type'   => $data['vehicle_type'],
            'vehicle_model'  => $data['vehicle_model'],
            'plate_number'   => $data['plate_number'],
            'availability'   => $data['availability'],
            'status'         => $data['status'],
        ]);

        $user->notify(new WelcomeUser($user->name, 'driver'));

        return redirect()->route('admin.drivers.index')->with('success', 'Your CampusRide account has been created successfully. A welcome email has been sent to the driver\'s Gmail.');
    }

    /**
     * Show driver profile and vehicle trip performance.
     */
    public function show(Driver $driver): View
    {
        $driver->load(['user', 'rides.passenger.user']);

        $rides = $driver->rides()->with('passenger.user')->latest()->take(15)->get();

        $stats = [
            'total_trips' => $driver->rides()->count(),
            'completed'   => $driver->rides()->where('status', 'completed')->count(),
            'total_fare'  => $driver->rides()->where('status', 'completed')->sum('fare'),
        ];

        return view('admin.drivers.show', compact('driver', 'rides', 'stats'));
    }

    /**
     * Show form to edit a driver.
     */
    public function edit(Driver $driver): View
    {
        $driver->load('user');
        return view('admin.drivers.edit', compact('driver'));
    }

    /**
     * Update driver details and vehicle info.
     */
    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', Rule::unique('users')->ignore($driver->user_id)],
            'phone'          => ['nullable', 'string', 'max:30'],
            'password'       => ['nullable', 'string', 'min:8'],
            // Vehicle & Driver fields
            'license_number' => ['required', 'string', 'max:50'],
            'vehicle_type'   => ['required', 'string', 'max:50'],
            'vehicle_model'  => ['required', 'string', 'max:100'],
            'plate_number'   => ['required', 'string', 'max:20'],
            'availability'   => ['required', 'in:available,on_trip,off_duty'],
            'status'         => ['required', 'in:active,inactive'],
        ]);

        $userData = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ];

        if (! empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        $driver->user->update($userData);

        $driver->update([
            'license_number' => $data['license_number'],
            'vehicle_type'   => $data['vehicle_type'],
            'vehicle_model'  => $data['vehicle_model'],
            'plate_number'   => $data['plate_number'],
            'availability'   => $data['availability'],
            'status'         => $data['status'],
        ]);

        return redirect()->route('admin.drivers.index')->with('success', 'Driver and vehicle details updated successfully.');
    }

    /**
     * Delete driver.
     */
    public function destroy(Driver $driver): RedirectResponse
    {
        $user = $driver->user;
        $driver->delete();
        $user->delete();

        return redirect()->route('admin.drivers.index')->with('success', 'Driver removed from campus fleet.');
    }
}
