<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AudioTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function test_user_can_choose_an_audio_from_the_listing(): void
    {
        $test = Str::of(__FUNCTION__)->title()->replace('_', ' ');
        $this->browse(function (Browser $browser) use ($test) {
            $browser->loginAs($this->user(), 'twill_users')
                ->visit('/admin')
                ->assertRouteIs('twill.dashboard')
                ->clickLink('Audio')
                ->assertRouteIs('twill.audio.index')
                ->screenshot("$test 1 - Audio Listing")
                ->click('.tablecell a') // The title of the first item
                ->screenshot("$test 2 - Audio Edit");
        });
    }
}
