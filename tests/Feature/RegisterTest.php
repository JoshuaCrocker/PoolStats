<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The registration form can be displayed.
     *
     * @test
     * @return void
     */
    public function it_displays_the_register_form()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /**
     * A valid user can be registered.
     *
     * @test
     * @return void
     */
    public function it_registeres_a_valid_user()
    {
        $user = make(User::class);
        $response = $this->post('register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);
        $response->assertStatus(302);
        $this->assertAuthenticated();
    }

    /**
     * An invalid user is not registered.
     *
     * @test
     * @return void
     */
    public function it_does_not_register_an_invalid_user()
    {
        $this->withExceptionHandling();
        $user = make(User::class);
        $response = $this->post('register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'secret',
            'password_confirmation' => 'invalid'
        ]);
        $response->assertSessionHasErrors();
    }
}
