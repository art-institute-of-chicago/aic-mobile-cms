<?php

namespace Tests\Browser;

use App\Models\Api\Audio as ApiAudio;
use App\Models\Audio;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TitleTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function test_title_is_always_displayed(): void
    {
        $test = Str::of(__FUNCTION__)->title()->replace('_', ' ');
        $apiAudio = ApiAudio::query()->limit(1)->get()->first();
        $this->browse(function (Browser $browser) use ($apiAudio, $test) {
            $audio = Audio::factory(['datahub_id' => $apiAudio->id, 'title' => 'Test Title'])->create();
            $browser->loginAs($this->user(), 'twill_users')
                ->visit("/admin/audio/$audio->id/edit")
                ->assertDontSeeIn('.titleEditor h2', 'Missing Title')
                ->assertSeeIn('.titleEditor h2', $audio->title)
                ->screenshot("$test 1 - Local Audio Title");

            $audio->fill(['title' => null])->save();
            $browser->visit("/admin/audio/$audio->id/edit")
                ->assertDontSeeIn('.titleEditor h2', 'Missing Title')
                ->assertSeeIn('.titleEditor h2', $apiAudio->title)
                ->screenshot("$test 2 - API Audio Title");
        });
    }
}
