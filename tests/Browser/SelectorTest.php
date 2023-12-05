<?php

namespace Tests\Browser;

use App\Models\Api\Audio;
use App\Models\Selector;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SelectorTest extends DuskTestCase
{
    use DatabaseTruncation;

    const TWILL_DATA_LOADED = 'window["TWILL"].STORE.datatable != {}';

    public function test_user_can_choose_a_selector_from_the_listing(): void
    {
        $test = Str::of(__FUNCTION__)->title()->replace('_', ' ');
        $selector = Selector::factory()->count(3)->create()->first();
        $this->browse(function (Browser $browser) use ($selector, $test) {
            $browser->loginAs($this->user(), 'twill_users')
                ->visit('/admin')
                ->assertRouteIs('twill.dashboard')
                ->clickLink('Selectors')
                ->assertRouteIs('twill.selectors.index')
                ->waitUntil(self::TWILL_DATA_LOADED)
                ->screenshot("$test 1 - Selector Listing")
                ->clickLink($selector->number)
                ->assertRouteIs('twill.selectors.edit', ['selector' => $selector->first()])
                ->waitUntil(self::TWILL_DATA_LOADED)
                ->screenshot("$test 2 - Selector Edit");
        });
    }

    public function test_user_can_add_audio_to_selector(): void
    {
        $this->markTestSkipped('Temporarily skipped');
        $test = Str::of(__FUNCTION__)->title()->replace('_', ' ');
        $selector = Selector::factory()->create();
        $audio = Audio::query()->limit(1)->get()->first();
        $this->browse(function (Browser $browser) use ($selector, $audio, $test) {
            $browser->loginAs($this->user(), 'twill_users')
                ->visit("admin/selectors/$selector->id/edit")
                ->waitUntil(self::TWILL_DATA_LOADED)
                ->screenshot("$test 1 - Selector Edit No Audio")
                ->press('Add audio')
                ->waitFor('.itemlist__table') // Audio browser data table
                ->check('.itemlist__row') // First item in list
                ->screenshot("$test 2 - Selector Edit Audio Select")
                ->press('Attach audio')
                ->press('Update')
                ->assertSee($audio->title)
                ->screenshot("$test 3 - Selector Edit Audio Attach")
                ->refresh()
                ->waitUntil(self::TWILL_DATA_LOADED)
                ->assertSee($audio->title)
                ->screenshot("$test 4 - Selector Edit Audio Refresh");
        });
    }
}
