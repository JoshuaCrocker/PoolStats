<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Displays the reset password request form.
     *
     * @test
     * @return void
     */
    public function it_displays_the_password_reset_form()
    {
        $response = $this->get('password/reset');
        $response->assertStatus(200);
    }

    /**
     * Sends the password reset email when the user exists.
     *
     * @test
     * @return void
     */
    public function it_sends_the_password_reset_email_for_valid_emails()
    {
        $user = create(User::class);
        $this->expectsNotification($user, ResetPassword::class);
        $response = $this->post('password/email', ['email' => $user->email]);
        $response->assertStatus(302);
    }

    /**
     * Does not send a password reset email when the user does not exist.
     *
     * @test
     * @return void
     */
    public function it_does_not_send_the_password_reset_email_for_invalid_emails()
    {
        $this->doesntExpectJobs(ResetPassword::class);
        $this->post('password/email', ['email' => 'invalid@email.com']);
    }

    /**
     * Displays the form to reset a password.
     *
     * @test
     * @return void
     */
    public function it_displays_the_password_change_form()
    {
        $response = $this->get('/password/reset/token');
        $response->assertStatus(200);
    }

    /**
     * Allows a user to reset their password.
     *
     * @test
     * @return void
     */
    public function it_changes_a_users_password()
    {
        $user = factory(User::class)->create();
        $token = Password::createToken($user);
        $response = $this->post('/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
