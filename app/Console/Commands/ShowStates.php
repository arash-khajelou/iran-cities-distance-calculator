<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class ShowStates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'show-states';

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
        $states = Location::where("type", "1")->where("lat", null)->get();
        foreach ($states as $state) {
            echo $state->name . " ( " . $state->getParentName() . " ) " . "\n";
            $geo_data_items = json_decode($state->geo_data);
            foreach ($geo_data_items as $geo_data_item) {
                if ($geo_data_item->properties->osm_value === "province") {
                    $state->lat = $geo_data_item->geometry->coordinates[0];
                    $state->lon = $geo_data_item->geometry->coordinates[1];
                    $state->save();
                    break;
                }
                echo "\t - " . $geo_data_item->properties->name .
                    " -> " . $geo_data_item->properties->osm_key . " -> " . $geo_data_item->properties->osm_type .
                    " -> " . $geo_data_item->properties->osm_value . "\n";
            }
        }

        return 0;
    }
}
