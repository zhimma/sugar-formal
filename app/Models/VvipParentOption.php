<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VvipParentOption extends Model
{
    use HasFactory;

    protected $optionsMapping;

    protected $currentOption;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->optionsMapping = [
            'ExtraCare' => new VvipSubOptionLifeCare,
        ];
        $this->currentOption = str_replace('App\Models\Vvip', '', get_class($this));
    }

    public function SubOptions()
    {
        return $this->hasManyThrough(
            get_class($this->optionsMapping[$this->currentOption]),
            VvipSubOptionXref::class,
            'parent_xref_id',
            'id',
            'id',
            'option_id'
        )->where('option_type', $this->optionsMapping[$this->currentOption]->type);
    }
}
