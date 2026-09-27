<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of students/users.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $roleFilter = $request->get('role');

        $query = User::with(['passenger', 'driver'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('passenger', function ($pq) use ($search) {
                      $pq->where('student_id', 'like', "%{$search}%")
                         ->orWhere('course', 'like', "%{$search}%")
                         ->orWhere('department', 'like', "%{$search}%");
                  });
            });
        }

        if ($roleFilter && in_array($roleFilter, ['passenger', 'driver', 'admin'])) {
            $query->where('role', $roleFilter);
        }

        $users = $query->paginate(12)->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'roleFilter'));
    }

    /**
     * Show form to create a new user/student.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user/student.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'role'       => ['required', 'in:passenger,admin'],
            'password'   => ['required', 'string', 'min:8'],
            // Student specific fields
            'student_id' => ['nullable', 'string', 'max:50'],
            'course'     => ['nullable', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],
            'year_level' => ['nullable', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'phone'             => $data['phone'] ?? null,
            'role'              => $data['role'],
            'password'          => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        if ($data['role'] === 'passenger') {
            Passenger::create([
                'user_id'    => $user->id,
                'student_id' => $data['student_id'] ?? ('STU-' . date('Y') . '-' . rand(100, 999)),
                'course'     => $data['course'] ?? 'General Studies',
                'department' => $data['department'] ?? 'Undergraduate',
                'year_level' => $data['year_level'] ?? '1st Year',
                'status'     => 'active',
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User account created successfully.');
    }

    /**
     * Display a user profile with ride history.
     */
    public function show(User $user): View
    {
        $user->load(['passenger.rides.driver.user', 'driver.rides']);

        $rides = $user->passenger ? $user->passenger->rides()->with('driver.user')->latest()->take(15)->get() : collect();

        return view('admin.users.show', compact('user', 'rides'));
    }

    /**
     * Show form to edit a user.
     */
    public function edit(User $user): View
    {
        $user->load('passenger');
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user and student profile.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone'      => ['nullable', 'string', 'max:30'],
            'password'   => ['nullable', 'string', 'min:8'],
            // Student specific fields
            'student_id' => ['nullable', 'string', 'max:50'],
            'course'     => ['nullable', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],
            'year_level' => ['nullable', 'string', 'max:50'],
            'status'     => ['nullable', 'in:active,inactive'],
        ]);

        $userData = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ];

        if (! empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        $user->update($userData);

        if ($user->passenger) {
            $user->passenger->update([
                'student_id' => $data['student_id'] ?? $user->passenger->student_id,
                'course'     => $data['course'] ?? $user->passenger->course,
                'department' => $data['department'] ?? $user->passenger->department,
                'year_level' => $data['year_level'] ?? $user->passenger->year_level,
                'status'     => $data['status'] ?? $user->passenger->status,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User profile updated successfully.');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own admin account.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User account deleted successfully.');
    }
}
