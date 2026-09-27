<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Passenger;
use App\Models\User;
use App\Notifications\WelcomeUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $selectedRole = $request->query('role', 'student');
        return view('auth.register', compact('selectedRole'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! $request->filled('full_name') && $request->filled('name')) {
            $request->merge(['full_name' => $request->input('name')]);
        }

        $accountType = $request->input('account_type', 'student');

        if ($accountType === 'driver') {
            $request->validate([
                'full_name'        => ['required', 'string', 'max:255'],
                'email'            => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:255', 'ends_with:@gmail.com', 'unique:'.User::class],
                'phone'            => ['required', 'string', 'max:30'],
                'profile_picture'  => ['nullable', 'image', 'max:2048'],
                'license_number'   => ['required', 'string', 'max:50'],
                'vehicle_type'     => ['required', 'string', 'max:50'],
                'vehicle_model'    => ['required', 'string', 'max:100'],
                'plate_number'     => ['required', 'string', 'max:20'],
                'password'         => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name'            => $request->full_name,
                'email'           => $request->email,
                'phone'           => $request->phone,
                'role'            => 'driver',
                'password'        => Hash::make($request->password),
                'email_verified_at' => now(),
                'profile_picture' => $request->hasFile('profile_picture')
                    ? $request->file('profile_picture')->store('profile-pictures', 'public')
                    : null,
            ]);

            Driver::create([
                'user_id'        => $user->id,
                'license_number' => $request->license_number,
                'vehicle_type'   => $request->vehicle_type,
                'vehicle_model'  => $request->vehicle_model,
                'plate_number'   => $request->plate_number,
                'availability'   => 'available',
                'status'         => 'active',
            ]);

            try {
                $user->notify(new WelcomeUser($user->name, 'driver'));
            } catch (\Throwable $e) {
                // Ignore mail errors on local environment
            }

            event(new Registered($user));
            Auth::login($user);

            return redirect(route('dashboard', absolute: false))
                ->with('success', 'Your CampusRide Driver account has been created successfully! Welcome aboard.');
        }

        // Student registration
        $request->validate([
            'full_name'        => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:255', 'ends_with:@gmail.com', 'unique:'.User::class],
            'phone'            => ['nullable', 'string', 'max:25'],
            'profile_picture'  => ['nullable', 'image', 'max:2048'],
            'student_id'       => ['nullable', 'string', 'max:50', 'unique:passengers,student_id'],
            'course'           => ['nullable', 'string', 'max:100'],
            'department'       => ['nullable', 'string', 'max:100'],
            'year_level'       => ['nullable', 'string', 'max:50'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'            => $request->full_name,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'role'            => 'passenger',
            'password'        => Hash::make($request->password),
            'email_verified_at' => now(),
            'profile_picture' => $request->hasFile('profile_picture')
                ? $request->file('profile_picture')->store('profile-pictures', 'public')
                : null,
        ]);

        Passenger::create([
            'user_id'    => $user->id,
            'student_id' => $request->student_id ?? ('STU-' . date('Y') . '-' . random_int(100, 999)),
            'course'     => $request->course ?? 'General Studies',
            'department' => $request->department ?? 'Undergraduate',
            'year_level' => $request->year_level ?? '1st Year',
            'status'     => 'active',
        ]);

        try {
            $user->notify(new WelcomeUser($user->name, 'passenger'));
        } catch (\Throwable $e) {
            // Ignore mail errors on local environment
        }

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false))
            ->with('success', 'Your CampusRide account has been created successfully. A welcome email has been sent to your Gmail.');
    }
}
