<?php

namespace App\Repositories;

use App\Models\AnonymousEvaluationChatReport;

class AnonymousEvaluationChatReportRepository
{
    /**
     * @var model
     */
    protected $model;
    /**
     * @param models
     */
    public function __construct(AnonymousEvaluationChatReport $model)
    {
        $this->model = $model;
    }
    
    public function create($data){
        $this->model->create($data);
    }
}
?>