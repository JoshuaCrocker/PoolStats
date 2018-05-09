<?php

namespace Tests\PageLoad;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthLoadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function login()
    {
        $route = route('login');
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function register()
    {
        $route = route('register');
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function forgot_password()
    {
        $route = route('password.request');
        $request = $this->get($route);
        $request->assertSuccessful();
    }
}
