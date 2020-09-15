<?php

namespace App\Console\Commands;

use App\Jobs\FetchLocationGeoData;
use App\Models\Location;
use Illuminate\Console\Command;

class FetchData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $counter = 0;
        foreach (Location::all() as $index => $location) {
            if ($location->geo_data === null) {
                $counter++;
                dispatch(new FetchLocationGeoData($location))->delay(now()->addSeconds($counter * 20));
            }
        }
        return 0;
    }
}
