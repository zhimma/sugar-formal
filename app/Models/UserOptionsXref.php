<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OptionOccupation;

class UserOptionsXref extends Model
{
    protected $table = 'user_options_xref';

    protected $fillable = [
        'option_id'
    ];

    public static function update_occupation($user_id, $option, $other_option_name)
    {
        $data = UserOptionsXref::firstOrNew(['user_id' => $user_id], ['option_type' => 1]);
        $data->user_id = $user_id;
        $data->option_type = 1;

        if($option)
        {
            if($option == 'other')
            {
                $option_data = OptionOccupation::firstOrNew(['option_name' => $other_option_name], ['is_custom' => 1]);
                $option_data->option_name = $other_option_name;
                $option_data->is_custom = 1;
                $option_data->save();

                
                $data->option_id = $option_data->id;
            }
            else
            {
                $data->option_id = $option;
            }
            $data->save();
        }
        else
        {
            $data->delete();
        }
        
    }

    public function occupation()
    {
        return $this->hasOne(OptionOccupation::class, 'id', 'option_id');
    }
}
