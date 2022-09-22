<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class VvipMarginDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
    ];

    public function updateBalance($before, $after)
    {
        DB::transaction(function () use ($before, $after) {
            $this->balance = $after;
            $this->user()->first()->VvipMarginLog()->create([
                "user_id" => $this->user_id,
                "balance_before" => $before,
                "balance_after" => $after
            ]);
            $this->save();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
