<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TempOptionsXrefCount;

class ComputeOptionXrefCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ComputeOptionXrefCount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '計算option_xref的統計數量';

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
        TempOptionsXrefCount::compute_options_xref_count();
        return 0;
    }
}
