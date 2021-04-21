<?php

namespace App\Repositories;

use App\Models\Suspicious;

class SuspiciousRepository
{
    /**
     * @var model fingerprint
     *
     */
    protected $model;

    public function __construct(Suspicious $model)
    {
    	$this->model = $model;
    }

    public function insert($data)
    {
        $this->model = new Suspicious($data);
		$this->model->save($data);
    }

    /** 
     * Get all user
     *
     * @return array users
     */
    public function paginate()
    {
        return $this->model->orderBy('created_at', 'desc')->paginate(15);
    }

    /** 
     * Get all user
     *
     * @return array users
     */
    public function wherePaginate($text)
    {
        // return $this->model->where('account_text', $text)->orderBy('created_at', 'desc')->paginate(15);
        return $this->model->where(function($query) use ($text) {
            $query->where('account_text', 'like', '%' . $text . '%')
                    ->orWhere('name', 'like', '%' . $text . '%');
        })->orderBy('created_at', 'desc')->paginate(15);
    }
}

?>