<?php

namespace App\Console\Commands;

use App\Models\Location;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ShowCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'show-cities';

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
        $cities = Location::where("type", "2")->where("lon", null)->get();
        $count = count($cities);
        foreach ($cities as $outer_index => $city) {
            if ($city->geo_data !== null) {
                echo $city->id . "[$outer_index of $count]: " . $city->name . " - " . $city->getParentName() . "\n";
                $geo_data = json_decode($city->geo_data);
                foreach ($geo_data as $index => $geo_item) {
                    echo $index . ": [" . $geo_item->geometry->coordinates[0] . ", " .
                        $geo_item->geometry->coordinates[1] . "]" . json_encode($geo_item->properties) . "\n";
                }
                $choice = $this->ask("select number:", 0);
                $city->lat = $geo_data[$choice]->geometry->coordinates[0];
                $city->lon = $geo_data[$choice]->geometry->coordinates[1];
                $city->save();
            }
        }

        return 0;
    }
}
