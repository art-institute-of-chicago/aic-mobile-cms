<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthenticationTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function test_user_can_login(): void
    {
        $user = User::factory()->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/admin')
                ->assertRouteIs('twill.login.form')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Login')
                ->assertRouteIs('twill.dashboard');
        });
    }

    public function test_user_can_logout(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user(), 'twill_users')
                ->visit('/admin')
                ->assertRouteIs('twill.dashboard')
                ->clickLink('Logout')
                ->assertRouteIs('twill.login.form');
        });
    }
}
