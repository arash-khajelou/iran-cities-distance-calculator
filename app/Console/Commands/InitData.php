<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use JsonMachine\JsonMachine;

class InitData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init';

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
        $data_array = JsonMachine::fromFile(base_path("data/data.json"));
        Schema::disableForeignKeyConstraints();
        foreach ($data_array as $index => $location) {
            Location::create([
                "id" => intval($location["ID"]),
                "name" => $location["Name"],
                "type" => $location["Type"],
                "parent_id" => $location["ParentId"],
                "ostan_id" => $location["ostan"],
                "shahr_id" => $location["shahr"],
                "bakhsh_id" => $location["bakhsh"],
                "dehestan_id" => $location["dehestan"]
            ]);
        }
        Schema::enableForeignKeyConstraints();
        return 0;
    }
}
