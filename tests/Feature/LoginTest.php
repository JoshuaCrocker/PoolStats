<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The login form can be displayed.
     *
     * @test
     * @return void
     */
    public function it_displays_the_login_form()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * A valid user can be logged in.
     *
     * @test
     * @return void
     */
    public function it_logs_in_a_valid_user()
    {
        $user = create(User::class);
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret'
        ]);
        $response->assertStatus(302);
        $this->assertAuthenticatedAs($user);
    }

    /**
     * An invalid user cannot be logged in.
     *
     * @test
     * @return void
     */
    public function it_does_not_log_in_an_invalid_user()
    {
        $this->withExceptionHandling();
        $user = create(User::class);
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'invalid'
        ]);
        $response->assertSessionHasErrors();
    }

    /**
     * A logged in user can be logged out.
     *
     * @test
     * @return void
     */
    public function it_logs_out_a_user()
    {
        $user = create(User::class);
        $response = $this->actingAs($user)->post('/logout');
        $response->assertStatus(302);
    }
}
