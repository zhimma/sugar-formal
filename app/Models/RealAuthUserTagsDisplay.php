<?php

namespace App\Models;

use App\Models\User;
use App\Models\RealAuthQuestion;
use App\Models\RealAuthChoice;
use App\Models\RealAuthUserReplyPic;
use App\Models\RealAuthUserApply;
use App\Models\RealAuthUserModify;
use Illuminate\Database\Eloquent\Model;

class RealAuthUserTagsDisplay extends Model
{
    protected $table = 'real_auth_user_tags_display';

    /**
     * The database table used by the model.
     *
     * @var string
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    protected $guarded = ['id'];

}
