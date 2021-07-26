<?php

namespace App\Models;

use App\Models\User;
use App\Models\CommonTextRead;
use App\Notifications\MessageEmail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminCommonText extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_common_text';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    public function common_text_read() {
        return $this->hasOne(CommonTextRead::class,'common_text_id');
    }

    public static function getCommonText($id){
        $tmp = AdminCommonText::select('content')->where('id', $id)->first();
        return $tmp->content;
    }

    public static function getCommonTextByAlias($alias){
        $tmp = AdminCommonText::select('content')->where('alias', $alias)->first();
        return $tmp->content;
    }
    
    public function getByAlias($alias){
        $tmp = AdminCommonText::where('alias', $alias)->first();
        return $tmp;
    }    
    
    public static function checkContent(Request $request) {
        return  AdminCommonText::where([['id', $request->id],['content', $request->content]])->first() !== null;
    }

    public static function checkContent2($id, $content) {
        return  AdminCommonText::where([['id', $id],['content', $content]])->first() !== null;
    }

    public static function saveCommonText(Request $request) {
        $a = AdminCommonText::select('*')->where('id', '=', $request->id)->first();
        $a->content = $request->content;
        $a->save();
        return true;
    }

}
