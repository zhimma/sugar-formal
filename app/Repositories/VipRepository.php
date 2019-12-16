<?php

namespace App\Repositories;

use App\Models\Vip;

class VipRepository
{
    /**
     * @var model
     */
    protected $vip;

    /**
     * @param models
     */
    public function __construct(Vip $vip)
    {
        $this->vip = $vip;
    }
    
    public function isVip($id){
        return $this->vip->where('member_id', $id)->first() ? true : false;
    }
}
?>