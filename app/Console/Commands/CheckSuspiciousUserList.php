<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\SuspiciousUserListTable;
use App\Models\SuspiciousUser;

class CheckSuspiciousUserList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CheckSuspiciousUserList';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '確認疑似八大名單';

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
        //SuspiciousUserListTable::check_medium_long_term_without_adv_verification();
        SuspiciousUser::check_weekly_communication_count();
        return 0;
    }
}
