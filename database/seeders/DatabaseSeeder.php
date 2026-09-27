<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\Driver;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin Accounts
        User::firstOrCreate(
            ['email' => 'campusride.admin@gmail.com'],
            [
                'name'              => 'CampusRide Admin',
                'role'              => 'admin',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Drivers
        $driver1User = User::firstOrCreate(
            ['email' => 'alex.driver@campusride.com'],
            [
                'name'              => 'Alex Driver',
                'phone'             => '+1 (555) 234-5678',
                'role'              => 'driver',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $driver1 = Driver::firstOrCreate(
            ['user_id' => $driver1User->id],
            [
                'license_number' => 'DRV-2026-042',
                'vehicle_type'   => 'Van',
                'vehicle_model'  => 'Toyota HiAce (White)',
                'plate_number'   => 'CR-8899-XY',
                'availability'   => 'available',
                'status'         => 'active',
            ]
        );

        $driver2User = User::firstOrCreate(
            ['email' => 'marcus.driver@campusride.com'],
            [
                'name'              => 'Marcus Vance',
                'phone'             => '+1 (555) 345-6789',
                'role'              => 'driver',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $driver2 = Driver::firstOrCreate(
            ['user_id' => $driver2User->id],
            [
                'license_number' => 'DRV-2026-118',
                'vehicle_type'   => 'Minibus Shuttle',
                'vehicle_model'  => 'Ford Transit Shuttle (Blue)',
                'plate_number'   => 'CR-4421-FD',
                'availability'   => 'available',
                'status'         => 'active',
            ]
        );

        $driver3User = User::firstOrCreate(
            ['email' => 'elena.driver@campusride.com'],
            [
                'name'              => 'Elena Gomez',
                'phone'             => '+1 (555) 456-7890',
                'role'              => 'driver',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $driver3 = Driver::firstOrCreate(
            ['user_id' => $driver3User->id],
            [
                'license_number' => 'DRV-2026-250',
                'vehicle_type'   => 'Electric Cart',
                'vehicle_model'  => 'Club Car Carryall (Green)',
                'plate_number'   => 'CR-1122-EV',
                'availability'   => 'on_trip',
                'status'         => 'active',
            ]
        );

        // 3. Students / Passengers
        $student1User = User::firstOrCreate(
            ['email' => 'passenger@campusride.com'],
            [
                'name'              => 'Jane Passenger',
                'phone'             => '+1 (555) 876-5432',
                'role'              => 'passenger',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $student1 = Passenger::firstOrCreate(
            ['user_id' => $student1User->id],
            [
                'student_id' => 'STU-2026-001',
                'course'     => 'Computer Science',
                'department' => 'Sciences & Tech',
                'year_level' => '3rd Year',
                'status'     => 'active',
            ]
        );

        $student2User = User::firstOrCreate(
            ['email' => 'michael.student@campusride.com'],
            [
                'name'              => 'Michael Chen',
                'phone'             => '+1 (555) 987-6543',
                'role'              => 'passenger',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $student2 = Passenger::firstOrCreate(
            ['user_id' => $student2User->id],
            [
                'student_id' => 'STU-2026-104',
                'course'     => 'Mechanical Engineering',
                'department' => 'Engineering',
                'year_level' => '2nd Year',
                'status'     => 'active',
            ]
        );

        $student3User = User::firstOrCreate(
            ['email' => 'sarah.student@campusride.com'],
            [
                'name'              => 'Sarah Jenkins',
                'phone'             => '+1 (555) 678-1234',
                'role'              => 'passenger',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $student3 = Passenger::firstOrCreate(
            ['user_id' => $student3User->id],
            [
                'student_id' => 'STU-2026-215',
                'course'     => 'Business Administration',
                'department' => 'Business & Economics',
                'year_level' => '4th Year',
                'status'     => 'active',
            ]
        );

        // 4. Sample Rides with Payment & Lifecycle
        if (Ride::count() === 0) {
            // Completed Ride 1
            Ride::create([
                'passenger_id'     => $student1->id,
                'driver_id'        => $driver1->id,
                'pickup_location'  => 'North Residence Halls',
                'dropoff_location' => 'Main Library Quad',
                'status'           => 'completed',
                'fare'             => 2.50,
                'payment_method'   => 'campus_card',
                'payment_status'   => 'paid',
                'paid_at'          => now()->subHours(5),
                'requested_at'     => now()->subHours(6),
                'pickup_at'        => now()->subHours(5)->subMinutes(45),
                'completed_at'     => now()->subHours(5)->subMinutes(30),
                'notes'            => 'Has heavy backpack with laptop gear.',
            ]);

            // Completed Ride 2
            Ride::create([
                'passenger_id'     => $student2->id,
                'driver_id'        => $driver2->id,
                'pickup_location'  => 'Engineering Complex Block B',
                'dropoff_location' => 'Student Union Center',
                'status'           => 'completed',
                'fare'             => 3.00,
                'payment_method'   => 'student_wallet',
                'payment_status'   => 'paid',
                'paid_at'          => now()->subHours(3),
                'requested_at'     => now()->subHours(4),
                'pickup_at'        => now()->subHours(3)->subMinutes(50),
                'completed_at'     => now()->subHours(3)->subMinutes(35),
            ]);

            // In-Progress Ride
            Ride::create([
                'passenger_id'     => $student3->id,
                'driver_id'        => $driver3->id,
                'pickup_location'  => 'Science Lab Complex',
                'dropoff_location' => 'South Sports Pavilion',
                'status'           => 'in_progress',
                'fare'             => 2.50,
                'payment_method'   => 'campus_card',
                'payment_status'   => 'paid',
                'paid_at'          => now()->subMinutes(15),
                'requested_at'     => now()->subMinutes(30),
                'pickup_at'        => now()->subMinutes(10),
                'notes'            => 'Waiting under covered awning at Gate 2.',
            ]);

            // Accepted Ride
            Ride::create([
                'passenger_id'     => $student1->id,
                'driver_id'        => $driver1->id,
                'pickup_location'  => 'Campus Health & Wellness Center',
                'dropoff_location' => 'North Residence Halls',
                'status'           => 'accepted',
                'fare'             => 2.50,
                'payment_method'   => 'campus_card',
                'payment_status'   => 'unpaid',
                'requested_at'     => now()->subMinutes(15),
            ]);

            // Pending Ride 1
            Ride::create([
                'passenger_id'     => $student2->id,
                'driver_id'        => null,
                'pickup_location'  => 'Metro Transit Plaza Gate',
                'dropoff_location' => 'Engineering Hall 3',
                'status'           => 'pending',
                'fare'             => 3.50,
                'payment_method'   => 'cash',
                'payment_status'   => 'unpaid',
                'requested_at'     => now()->subMinutes(8),
                'is_priority'      => true,
                'notes'            => 'Heading to mid-term exam, urgent pickup appreciated.',
            ]);

            // Pending Ride 2
            Ride::create([
                'passenger_id'     => $student3->id,
                'driver_id'        => null,
                'pickup_location'  => 'Fine Arts Auditorium',
                'dropoff_location' => 'East Gate Parking Lot',
                'status'           => 'pending',
                'fare'             => 2.50,
                'payment_method'   => 'student_wallet',
                'payment_status'   => 'unpaid',
                'requested_at'     => now()->subMinutes(3),
            ]);
        }

        Ride::query()->each(function (Ride $ride): void {
            Payment::updateOrCreate(
                ['ride_id' => $ride->id],
                [
                    'amount'         => $ride->fare,
                    'payment_method' => $ride->payment_method,
                    'payment_status' => $ride->payment_status,
                    'paid_at'        => $ride->paid_at,
                ]
            );
        });

        // 5. Sample Contact Messages
        if (ContactMessage::count() === 0) {
            ContactMessage::create([
                'name'    => 'Prof. Robert Miller',
                'email'   => 'rmiller@campus.edu',
                'phone'   => '+1 (555) 777-8899',
                'subject' => 'Accessibility Shuttle Inquiry for Guest Speaker',
                'message' => 'Hello, our department has a visiting lecturer tomorrow at 2 PM requiring wheelchair-accessible transport between the campus hotel and the auditorium.',
                'status'  => 'new',
            ]);

            ContactMessage::create([
                'name'    => 'Taylor Reed (Student Council)',
                'email'   => 'treed@student.edu',
                'phone'   => '+1 (555) 888-9900',
                'subject' => 'Late-Night Shuttle Request for Homecoming Week',
                'message' => 'Can we schedule extended shuttle operating hours for Friday night during homecoming festivities until 2:00 AM?',
                'status'  => 'in_progress',
            ]);
        }
    }
}
