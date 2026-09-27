<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\AppSetting;
use App\Models\Driver;
use App\Models\DriverApplication;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    /**
     * Show the CampusRide Home page.
     */
    public function home(): View
    {
        $stats = [
            'active_drivers'  => Driver::where('status', 'active')->where('availability', 'available')->count(),
            'total_rides'     => Ride::count(),
            'completed_rides' => Ride::where('status', 'completed')->count(),
        ];

        $baseFare = AppSetting::getValue('base_fare', '2.50');

        $popularHubs = [
            ['name' => 'Main Library Quad', 'icon' => 'bi-book', 'category' => 'Academic Hub', 'shuttle_mins' => '3 mins'],
            ['name' => 'Student Union Center', 'icon' => 'bi-cup-hot', 'category' => 'Dining & Lounge', 'shuttle_mins' => '4 mins'],
            ['name' => 'North Residence Halls', 'icon' => 'bi-houses', 'category' => 'Dormitory', 'shuttle_mins' => '5 mins'],
            ['name' => 'Science & Lab Complex', 'icon' => 'bi-cpu', 'category' => 'Research', 'shuttle_mins' => '2 mins'],
            ['name' => 'South Sports Pavilion', 'icon' => 'bi-trophy', 'category' => 'Athletics & Gym', 'shuttle_mins' => '6 mins'],
            ['name' => 'Metro Transit Plaza Gate', 'icon' => 'bi-train-front', 'category' => 'Commuter Hub', 'shuttle_mins' => '5 mins'],
        ];

        return view('public.home', compact('stats', 'popularHubs', 'baseFare'));
    }

    /**
     * Show the About Us page.
     */
    public function about(): View
    {
        return view('public.about');
    }

    /**
     * Show the Mobility Services page.
     */
    public function services(): View
    {
        return view('public.services');
    }

    /**
     * Show the Contact & Help page.
     */
    public function contact(): View
    {
        return view('public.contact');
    }

    /**
     * Handle public contact inquiries.
     */
    public function submitContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::create([
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status'  => 'new',
        ]);

        return back()->with('success', 'Thank you! Your message has been received by Campus Dispatch. We will follow up shortly.');
    }

    /**
     * Show the Apply to Drive application page.
     */
    public function applyToDrive(): View
    {
        return view('public.apply-driver');
    }

    /**
     * Handle driver application submission.
     */
    public function submitDriverApplication(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name'        => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:255', 'ends_with:@gmail.com'],
            'phone'            => ['required', 'string', 'max:30'],
            'license_number'   => ['required', 'string', 'max:50'],
            'vehicle_type'     => ['required', 'string', 'max:50'],
            'vehicle_model'    => ['required', 'string', 'max:100'],
            'plate_number'     => ['required', 'string', 'max:20'],
            'experience_years' => ['nullable', 'string', 'max:50'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);

        if (DriverApplication::where('email', $data['email'])->where('status', 'pending')->exists()) {
            return back()->withInput()->withErrors([
                'email' => 'A driver application with this Gmail address is already pending review. Our team will contact you soon.',
            ]);
        }

        if (User::where('email', $data['email'])->where('role', 'driver')->exists()) {
            return back()->withInput()->withErrors([
                'email' => 'A driver account is already registered with this Gmail address. You can log in directly.',
            ]);
        }

        DriverApplication::create([
            'full_name'        => $data['full_name'],
            'email'            => $data['email'],
            'phone'            => $data['phone'],
            'license_number'   => $data['license_number'],
            'vehicle_type'     => $data['vehicle_type'],
            'vehicle_model'    => $data['vehicle_model'],
            'plate_number'     => $data['plate_number'],
            'experience_years' => $data['experience_years'] ?? null,
            'notes'            => $data['notes'] ?? null,
            'status'           => 'pending',
        ]);

        return redirect()->route('driver.apply')->with('success', 'Your driver application has been submitted successfully! The CampusRide operations team will review your credentials and contact you at your Gmail address.');
    }
}
