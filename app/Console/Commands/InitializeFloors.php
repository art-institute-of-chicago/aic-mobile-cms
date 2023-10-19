<?php

namespace App\Console\Commands;

use App\Models\Floor;
use Illuminate\Console\Command;

class InitializeFloors extends Command
{
    protected $signature = 'app:initialize-floors';

    protected $description = 'Insert all floors into the database';

    public function handle()
    {
        foreach (Floor::LEVELS as $level => $geoId) {
            Floor::firstOrCreate(['level' => $level, 'geo_id' => $geoId]);
        }
    }
}
