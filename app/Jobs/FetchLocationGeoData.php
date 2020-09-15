<?php

namespace App\Jobs;

use App\Models\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Ixudra\Curl\Builder;
use Ixudra\Curl\Facades\Curl;

class FetchLocationGeoData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Location
     */
    private $location;

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
            $this->location->save();
        }
    }

    /**
     * @return Builder
     */
    protected function fetch()
    {
        return Curl::to("https://photon.komoot.de/api/")->asJson()->withData([
            "q" => $this->location->name
        ]);
    }
}
