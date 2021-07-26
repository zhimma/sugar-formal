<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\AdminCommonText;

class CommonTextRead extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'common_text_read';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    protected $guarded = ['id'];
    
    public function admin_common_text() {
        return $this->belongsTo(AdminCommonText::class,'common_text_id');
    }

}
