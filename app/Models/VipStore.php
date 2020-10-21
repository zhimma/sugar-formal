<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VipStore extends Model
{
    public $table = "member_vip_stroe";

    public $primaryKey = "id";

    public static function checkByUser($user_id, $ChkValue) {
        return VipStore::where('user_id', $user_id)->where('ChkValue', $ChkValue)->where('created_at', '>', Carbon::now()->addHours(-24)->toDateTimeString())->first();
    }

}