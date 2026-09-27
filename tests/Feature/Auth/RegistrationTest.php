<?php

namespace Tests\Feature\Auth;

use App\Models\Passenger;
use App\Models\User;
use App\Notifications\WelcomeUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_login_page_uses_blank_gmail_placeholder(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200)
            ->assertSee('Enter your Gmail address')
            ->assertDontSee('admin@campusride.com')
            ->assertDontSee('admin@gmail.com');
    }

    public function test_new_users_can_register_with_gmail_address(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@gmail.com',
            'phone' => '+1234567890',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'test@gmail.com', 'role' => 'passenger']);
        $registeredUser = User::where('email', 'test@gmail.com')->first();
        $mail = (new WelcomeUser($registeredUser->name, 'passenger'))->toMail($registeredUser);

        Notification::assertSentTo(
            $registeredUser,
            WelcomeUser::class,
            function ($notification, $channels) {
                return $notification->role === 'passenger';
            }
        );
        $this->assertSame('Your CampusRide account was created successfully', $mail->subject);
        $this->assertContains('Your CampusRide Student account has been created successfully.', $mail->introLines);
        $this->assertContains('Use the Gmail address and password you created during registration to log in.', $mail->introLines);
        $this->assertContains('For your security, your password is not included in this email.', $mail->outroLines);
        $response->assertSessionHas('success', 'Your CampusRide account has been created successfully. A welcome email has been sent to your Gmail.');
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_registration_rejects_non_gmail_email_addresses(): void
    {
        $response = $this->from('/register')->post('/register', [
            'full_name' => 'Invalid Email User',
            'email' => 'user@campusride.edu',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'user@campusride.edu']);
    }

    public function test_registration_creates_a_passenger_with_a_hashed_password_and_profile_picture(): void
    {
        Storage::fake('public');

        $this->post('/register', [
            'full_name' => 'Photo User',
            'email' => 'photo@gmail.com',
            'phone' => '08012345678',
            'password' => 'password',
            'password_confirmation' => 'password',
            'profile_picture' => UploadedFile::fake()->createWithContent(
                'profile.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')
            ),
        ])->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'photo@gmail.com')->firstOrFail();

        $this->assertSame('passenger', $user->role);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertNotNull($user->profile_picture);
        $this->assertTrue(Passenger::where('user_id', $user->id)->exists());
        Storage::disk('public')->assertExists($user->profile_picture);
    }
}
