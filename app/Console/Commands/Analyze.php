<?php

namespace App\Console\Commands;

use App\Models\Location;
use Exception;
use Illuminate\Console\Command;

class Analyze extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analyze';

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

    public static $data_keys = [
        "osm_key", "osm_type", "osm_value"
    ];

    public static $data = [
        "osm_key" => [], "osm_type" => [], "osm_value" => []
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->analyzeDistances();

        return 0;
    }

    public function analyzeDistances()
    {
        $locations = Location::where("type", 2)->get();

        foreach ($locations as $location) {
            $parent = $location->parent;
            if ($parent !== null) {
                $distance = sqrt(pow(($parent->lon - $location->lon), 2) + pow(($parent->lat - $location->lat), 2));
                echo "$distance - l:$location->id - s:$parent->id - [$location->lon, $location->lat] - [$parent->lon, $parent->lat]\n$distance - $location->name - $parent->name \n";
            } else {
                echo "0 - l:$location->id - s:0 - [$location->lon, $location->lat] - [, ]\n0 - $location->name - null \n";
            }
        }
    }

    public function analyzeKeys()
    {
        Location::chunk(10, function ($locations) {
            foreach ($locations as $location) {
                if ($location->geo_data !== null) {
                    $geo_data = json_decode($location->geo_data);
                    foreach ($geo_data as $datum) {
                        if (in_array($datum->properties->osm_type, ["N", "R"])) {
                            foreach (Analyze::$data_keys as $key) {
                                try {
                                    if (isset(Analyze::$data[$key][$datum->properties->{$key}])) {
                                        Analyze::$data[$key][$datum->properties->{$key}] += 1;
                                    } else {
                                        Analyze::$data[$key][$datum->properties->{$key}] = 1;
                                    }
                                } catch (Exception $e) {
                                    dd($datum, $e);
                                }
                            }
                        }
                    }
                }
            }
        });

        foreach (Analyze::$data_keys as $key) {
            arsort(Analyze::$data[$key]);
        }

        dd(Analyze::$data);
    }
}
