<?php

namespace App\Console\Commands;

use App\Models\ScheduleData;
use Illuminate\Console\Command;
use App\Services\MessageService;

class MessageSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MessageSchedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MessageSchedule';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MessageService $messageService)
    {
        parent::__construct();
        $this->messageService = $messageService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $update_user = ScheduleData::where('type', 'set_message_handling')->get();
        foreach($update_user as $data)
        {
            $this->messageService->setMessageHandlingBySenderId($data->input_id,0);
        }
        $update_user = ScheduleData::where('type', 'set_message_handling')->delete();
    }
}
