<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class Board extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'board';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'post'
    ];

    public static function all_()
    {
        return Board::all();
    }

    public static function findBoardById($uid) {
        return Board::where('member_id', $uid)->orderBy('created_at', 'desc')->first();
    }

    public static function post($member_id, $msg)
    {
        $board = new Board;
        $board->member_id = $member_id;
        $board->post = $msg;
        $board->save();
    }

    public static function canPost($uid) {
        $postTime = Board::findBoardById($uid);
        $now = Carbon::now();

        if(empty($postTime)) return true;

        $diff_seconds = $now->diffInSeconds($postTime->created_at);

        if($diff_seconds < Config::get('social.limit.board-days')) return false;
        return true;
    }

    public static function getPostSeconds($uid) {
        $postTime = Board::findBoardById($uid);

        if(isset($postTime)) return Config::get('social.limit.board-days') - Carbon::now()->diffInSeconds($postTime->created_at);
    }
}
