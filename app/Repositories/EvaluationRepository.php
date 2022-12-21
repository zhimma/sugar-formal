<?php

namespace App\Repositories;

use App\Models\Evaluation;

class EvaluationRepository
{
    /**
     * @var model
     */
    protected $model;

    /**
     * @param models
     */
    public function __construct(Evaluation $model)
    {
        $this->model = $model;
    }
    
    public function checkAnonymous($id,$userid){
        return $this->model->where(["id"=>$id,'anonymous_content_status'=>1])->where(function($query) use ($userid){
            $query->where('to_id',$userid);
            $query->orwhere('from_id',$userid);
        })->count() == 1;
    }

    public function getEvaluationMembers($id){
        return $this->model->select('to_id','from_id')->where("id",$id)->first();
    }

    public function getEvaluationViolation($id){
        return $this->model->select('id','to_id','from_id')->whereNotNull('e.content_violation_processing')->get();
    }

}
?>