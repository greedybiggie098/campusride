<?php

namespace Tests\Feature;

use App\Models\Driver;
use App\Models\Passenger;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RideRequestFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $passengerUser;
    protected Passenger $passenger;
    protected User $adminUser;
    protected User $driverUser;
    protected Driver $driver;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Passenger
        $this->passengerUser = User::factory()->create([
            'email' => 'passenger@campusride.com',
            'role'  => 'passenger',
        ]);
        $this->passenger = Passenger::create([
            'user_id' => $this->passengerUser->id,
            'status'  => 'active',
        ]);

        // 2. Create Admin
        $this->adminUser = User::factory()->create([
            'email' => 'admin@campusride.com',
            'role'  => 'admin',
        ]);

        // 3. Create Driver
        $this->driverUser = User::factory()->create([
            'email' => 'driver@campusride.com',
            'role'  => 'driver',
        ]);
        $this->driver = Driver::create([
            'user_id'       => $this->driverUser->id,
            'status'        => 'active',
            'availability'  => 'available',
            'vehicle_model' => 'Toyota HiAce',
            'plate_number'  => 'CR-8899-XY',
        ]);
    }

    public function test_complete_passenger_admin_driver_ride_flow(): void
    {
        // ─── Step 1: Passenger tests ─────────────────────────────────────────
        // Access passenger dashboard
        $response = $this->actingAs($this->passengerUser)->get('/passenger/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Request a Ride');

        // Access ride request form
        $response = $this->actingAs($this->passengerUser)->get('/passenger/rides/create');
        $response->assertStatus(200);

        // Submit ride request: Main Gate -> Student Hostel
        $response = $this->actingAs($this->passengerUser)->post('/passenger/rides', [
            'pickup_location'  => 'Main Gate',
            'dropoff_location' => 'Student Hostel',
            'notes'            => 'Near security post',
        ]);
        $response->assertRedirect(route('passenger.rides.index'));

        // Verify ride was created with status: pending and driver: not assigned
        $ride = Ride::latest()->first();
        $this->assertNotNull($ride);
        $this->assertSame('Main Gate', $ride->pickup_location);
        $this->assertSame('Student Hostel', $ride->dropoff_location);
        $this->assertSame('pending', $ride->status);
        $this->assertNull($ride->driver_id);

        // View passenger ride details
        $response = $this->actingAs($this->passengerUser)->get("/passenger/rides/{$ride->id}");
        $response->assertStatus(200);
        $response->assertSee('Pending');
        $response->assertSee('Not assigned');

        // ─── Step 2: Admin tests ─────────────────────────────────────────────
        // Admin views dashboard
        $response = $this->actingAs($this->adminUser)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Main Gate');
        $response->assertSee('Student Hostel');

        // Admin assigns driver
        $response = $this->actingAs($this->adminUser)->patch("/admin/rides/{$ride->id}/assign", [
            'driver_id' => $this->driver->id,
        ]);
        $response->assertRedirect(route('admin.rides.show', $ride));

        $ride->refresh();
        $this->assertSame('assigned', $ride->status);
        $this->assertSame($this->driver->id, $ride->driver_id);
        $this->driver->refresh();
        $this->assertSame('on_trip', $this->driver->availability);

        // ─── Step 3: Driver tests (assigned → accepted → in_progress → completed) ─
        // Driver opens driver dashboard
        $response = $this->actingAs($this->driverUser)->get('/driver/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Main Gate');
        $response->assertSee('Student Hostel');

        // 3a. Driver accepts ride: assigned → accepted
        $response = $this->actingAs($this->driverUser)->patch("/driver/rides/{$ride->id}/accept");
        $response->assertRedirect(route('driver.rides.show', $ride));
        $ride->refresh();
        $this->assertSame('accepted', $ride->status);

        // 3b. Driver starts ride: accepted → in_progress
        $response = $this->actingAs($this->driverUser)->patch("/driver/rides/{$ride->id}/status", [
            'status' => 'in_progress',
        ]);
        $response->assertRedirect(route('driver.rides.show', $ride));
        $ride->refresh();
        $this->assertSame('in_progress', $ride->status);
        $this->assertNotNull($ride->pickup_at);

        // 3c. Driver completes ride: in_progress → completed
        $response = $this->actingAs($this->driverUser)->patch("/driver/rides/{$ride->id}/status", [
            'status' => 'completed',
        ]);
        $response->assertRedirect(route('driver.rides.show', $ride));
        $ride->refresh();
        $this->assertSame('completed', $ride->status);
        $this->assertNotNull($ride->completed_at);

        // Driver availability is released
        $this->driver->refresh();
        $this->assertSame('available', $this->driver->availability);
    }
}
