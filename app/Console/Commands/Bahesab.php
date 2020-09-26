<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Builder;
use Ixudra\Curl\Facades\Curl;

class Bahesab implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bahesab';

    /**
     * @var Location
     */
    private $start_point;

    /**
     * @var Location
     */
    private $end_point;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @param $start_point
     * @param $end_point
     */
    public function __construct($start_point, $end_point)
    {
        $this->start_point = $start_point;
        $this->end_point = $end_point;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $result = $this->fetch()->get();
        Log::info(json_encode($result));
        return 0;
    }

    /**
     * @return Builder
     */
    protected function fetch()
    {
        $name = Location::$types[$this->location->type] . " " . $this->location->name;
        return Curl::to("https://photon.komoot.de/api/")->asJson()->withData([
            "q" => $name
        ]);
    }
}
