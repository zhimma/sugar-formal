<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogFreeVipPicAct extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    //protected $table = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = '';

    protected $guarded = ['id'];
    
    public static $reachRuleSysReacts = ['recovering','upgrade','remain','remain_init','auto_upgrade','auto_remain'];
    public static $notReachRuleSysReacts = ['reminding','avatar_ok','member_pic_ok','not_vip_not_ok'];
    public static $needFirstRemindSysReacts = ['reminding','upgrade','recovering','avatar_ok','member_pic_ok'];
    public static $replaceByFirstRemindSysReacts = ['reminding','avatar_ok','member_pic_ok'];
}