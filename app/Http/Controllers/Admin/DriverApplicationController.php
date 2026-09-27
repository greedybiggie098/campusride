<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverApplication;
use App\Models\User;
use App\Notifications\WelcomeUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DriverApplicationController extends Controller
{
    /**
     * Display a listing of driver applications.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');
        $search = $request->get('search');

        $query = DriverApplication::with('user')->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('license_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_model', 'like', "%{$search}%")
                  ->orWhere('plate_number', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(12)->withQueryString();

        $counts = [
            'pending'  => DriverApplication::where('status', 'pending')->count(),
            'approved' => DriverApplication::where('status', 'approved')->count(),
            'rejected' => DriverApplication::where('status', 'rejected')->count(),
            'total'    => DriverApplication::count(),
        ];

        return view('admin.driver-applications.index', compact('applications', 'status', 'search', 'counts'));
    }

    /**
     * Display the specified driver application details.
     */
    public function show(DriverApplication $application): View
    {
        $application->load('user.driver');
        return view('admin.driver-applications.show', compact('application'));
    }

    /**
     * Approve the driver application and create the user + driver account.
     */
    public function approve(Request $request, DriverApplication $application): RedirectResponse
    {
        if ($application->status === 'approved') {
            return back()->withErrors(['error' => 'This application has already been approved.']);
        }

        $request->validate([
            'password'     => ['nullable', 'string', 'min:8'],
            'vehicle_type' => ['required', 'string', 'max:50'],
            'vehicle_model'=> ['required', 'string', 'max:100'],
            'plate_number' => ['required', 'string', 'max:20'],
            'admin_notes'  => ['nullable', 'string', 'max:1000'],
        ]);

        $initialPassword = $request->filled('password') ? $request->input('password') : 'password';

        // Check if a user with this email already exists
        $user = User::where('email', $application->email)->first();

        if (! $user) {
            $user = User::create([
                'name'              => $application->full_name,
                'email'             => $application->email,
                'phone'             => $application->phone,
                'role'              => 'driver',
                'password'          => Hash::make($initialPassword),
                'email_verified_at' => now(),
            ]);
        } else {
            // Upgrade or confirm role is driver
            $user->role = 'driver';
            if ($request->filled('password')) {
                $user->password = Hash::make($initialPassword);
            }
            $user->save();
        }

        // Create or update driver profile
        Driver::updateOrCreate(
            ['user_id' => $user->id],
            [
                'license_number' => $application->license_number,
                'vehicle_type'   => $request->input('vehicle_type', $application->vehicle_type),
                'vehicle_model'  => $request->input('vehicle_model', $application->vehicle_model),
                'plate_number'   => $request->input('plate_number', $application->plate_number),
                'availability'   => 'available',
                'status'         => 'active',
            ]
        );

        // Update application
        $application->update([
            'status'       => 'approved',
            'admin_notes'  => $request->input('admin_notes'),
            'user_id'      => $user->id,
            'reviewed_at'  => now(),
        ]);

        // Dispatch welcome notification
        try {
            $user->notify(new WelcomeUser($user->name, 'driver'));
        } catch (\Throwable $e) {
            // Ignore email delivery errors if local SMTP is not configured
        }

        return redirect()->route('admin.driver-applications.index')
            ->with('success', "Driver application for {$application->full_name} has been approved! Driver account ({$user->email}) created successfully with initial password '{$initialPassword}'.");
    }

    /**
     * Reject the driver application.
     */
    public function reject(Request $request, DriverApplication $application): RedirectResponse
    {
        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $application->update([
            'status'      => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.driver-applications.index')
            ->with('success', "Application for {$application->full_name} has been marked as rejected.");
    }
}
