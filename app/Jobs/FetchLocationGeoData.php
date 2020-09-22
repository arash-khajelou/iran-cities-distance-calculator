<?php

namespace App\Jobs;

use App\Models\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Builder;
use Ixudra\Curl\Facades\Curl;

class FetchLocationGeoData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Location
     */
    private $location;

    public static $types = [
        "کشور", "استان", "شهرستان", "بخش", "دهستان"
    ];

    /**
     * Create a new job instance.
     *
     * @param Location $location
     */
    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $result = $this->fetch()->get();
        Log::info(json_encode($result));
        $features = [];
        if (isset($result->features)) {
            foreach ($result->features as $feature) {
                if (isset($feature->properties) and
                    isset($feature->properties->country) and
                    $feature->properties->country === "Iran") {
                    array_push($features, $feature);
                }
            }
        }

        if (count($features) > 0) {
            $this->location->geo_data = $features;
        }
        $this->location->is_fetched = true;
        $this->location->save();

    }

    /**
     * @return Builder
     */
    protected function fetch()
    {
        $name = self::$types[$this->location->type] . " " . $this->location->name;
        return Curl::to("https://photon.komoot.de/api/")->asJson()->withData([
            "q" => $name
        ]);
    }
}
