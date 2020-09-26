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
        $cities = Location::where("type", "4")->where("lon", null)->get();
        $count = count($cities);
        foreach ($cities as $outer_index => $city) {
            $parent = $city->parent;
            $min_distance_amount = 100;
            $min_distance_id = -1;
            $available_choices = [];
            $menu_items = [];

            echo $city->id . "[$outer_index of $count]: " . $city->name . " - " . $city->getParentName() . "\n";
            $flag = false;
            if ($city->geo_data !== null) {
                $geo_data = json_decode($city->geo_data);
                foreach ($geo_data as $index => $geo_item) {
                    if ($geo_item->properties->osm_type === "N") {
                        $distance = " - ";
                        if ($parent !== null) {
                            $distance = sqrt(pow(($parent->lon - $geo_item->geometry->coordinates[1]), 2) +
                                pow(($parent->lat - $geo_item->geometry->coordinates[0]), 2));
                            if ($distance < $min_distance_amount) {
                                $min_distance_amount = $distance;
                                $min_distance_id = $index;
                            }
                            if ($distance < 1) {
                                array_push($available_choices, $index);
                            }
                        }
                        if ($distance < 2.5)
                            array_push($menu_items, $index . ": ($distance) [" . $geo_item->geometry->coordinates[1] . ", " .
                                $geo_item->geometry->coordinates[0] . "] -> [" . ($parent !== null ? $parent->lon : 0) . ", " .
                                ($parent !== null ? $parent->lat : 0) . "]" .
                                json_encode($geo_item->properties));
                    }
                }
                $data_length = count($geo_data);
                if (count($available_choices) > 0) {
                    $choice = min($available_choices);
                } else {
                    if (count($menu_items) > 0) {
                        foreach ($menu_items as $menu_item) {
                            echo $menu_item . "\n";
                        }
                        echo "p: parent point. \n";
                        echo $data_length . ": manual data.\n";
//                        $choice = $this->ask("select number", $min_distance_id);
                        $choice = "p";
                    } else {
                        $choice = "p";
                    }
                }
                if (strtolower($choice) === "p") {
                    if ($parent !== null) {
                        $city->lat = $parent->lat;
                        $city->lon = $parent->lon;
                        $city->save();
                        $flag = true;
                    }
                } else if ($choice >= 0 and $choice < intval($data_length)) {
                    $city->lat = $geo_data[$choice]->geometry->coordinates[0];
                    $city->lon = $geo_data[$choice]->geometry->coordinates[1];
                    $city->save();
                    $flag = true;
                }
            }
            if (!$flag) {
                $city->lat = null;//$this->ask("lat");
                $city->lon = null;//$this->ask("lon");
                $city->save();
            }
        }

        return 0;
    }
}
