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
        $states = Location::where("type", "1")->get();
        $count = count($states);
        foreach ($states as $outer_index => $city) {
            if ($city->geo_data !== null) {
                echo $city->id . "[$outer_index of $count]: " . $city->name . " - " . $city->getParentName() . "\n";
                $geo_data = json_decode($city->geo_data);
                foreach ($geo_data as $index => $geo_item) {
                    echo $index . ": [" . $geo_item->geometry->coordinates[1] . ", " .
                        $geo_item->geometry->coordinates[0] . "]" . json_encode($geo_item->properties) . "\n";
                }
                $data_length = count($geo_data);
                echo $data_length . ": manual data.\n";
                $choice = $this->ask("select number", 0);
                if ($choice === "$data_length") {
                    $city->lat = $this->ask("lat");
                    $city->lon = $this->ask("lon");
                } else {
                    $city->lat = $geo_data[$choice]->geometry->coordinates[0];
                    $city->lon = $geo_data[$choice]->geometry->coordinates[1];
                }
                $city->save();
            }
        }
        return 0;
    }
}
