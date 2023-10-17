<?php

namespace App\Console\Commands;

use App\Models\Label;
use Illuminate\Console\Command;

class InitializeLabels extends Command
{
    protected $signature = 'app:initialize-labels';

    protected $description = 'Insert all UI label keys into the database';

    public function handle()
    {
        foreach (Label::KEYS as $key) {
            Label::firstOrCreate(['key' => $key]);
        }
    }
}
