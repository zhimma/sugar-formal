<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Forum;

class ForumCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ForumCheck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '檢查過去一週內討論區是否符合規定';

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
        $last_week_time = Carbon::now()->subDays(7);
        
        
        $forums = Forum::with(['posts_of_forum' => function($query) use ($last_week_time){$query->where('created_at','>=',$last_week_time);}])
        ->where('forum.created_at','<',$last_week_time);
        
        
        $forums = $forums->get();
        foreach($forums as $form)
        {
            $is_warned = false;
            
            if((count($form->posts_of_forum->where('type','main')) < 1) && (count($form->posts_of_forum->where('type','sub')) < 3))
            {
                $is_warned = true;
            }

            if($is_warned)
            {
                if($form->is_warned)
                {
                    Forum::close_forum($form->id);
                }
                else
                {
                    Forum::warn_forum($form->id);
                }
                
            }
        }
    }
}
