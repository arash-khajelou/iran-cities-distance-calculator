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
        foreach (Location::where("type", 2)->get() as $index => $location) {
            if ($location->geo_data === null and !$location->is_fetched) {
                $counter++;
                dispatch(new FetchLocationGeoData($location));
            }
        }
        return 0;
    }
}
