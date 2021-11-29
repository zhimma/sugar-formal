<?php

namespace App\Models;

use \Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ImagesCompareStatus extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'images_compare_status';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = '';

    protected $guarded = ['id'];
    
    public function isHoldTooLong() {
        return ($this->status==1 && $this->start_time && Carbon::now()->diffInMinutes(Carbon::parse($this->start_time))>10);
    }
    
    public function isQueueTooLong() {
        return ($this->queue && $this->qstart_time && Carbon::now()->diffInMinutes(Carbon::parse($this->qstart_time))>10);
    }    

}
