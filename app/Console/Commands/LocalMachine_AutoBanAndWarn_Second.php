<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Models\SetAutoBan;

class LocalMachine_AutoBanAndWarn_Second extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LocalMachine_AutoBanAndWarn_Second';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '地端主機自動警示及封鎖';

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
        Log::Info('LocalMachine_AutoBanAndWarn_Second_Start');
        Log::Info('LocalMachine_AutoBanAndWarn_Second_End');
    }
}
