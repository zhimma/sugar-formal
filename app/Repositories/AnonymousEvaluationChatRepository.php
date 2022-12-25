<?php

namespace App\Repositories;

use App\Models\AnonymousEvaluationChat;

class AnonymousEvaluationChatRepository
{
    /**
     * @var model
     */
    protected $model;
    /**
     * @param models
     */
    public function __construct(AnonymousEvaluationChat $model)
    {
        $this->model = $model;
    }
    
    public function createChat($data){
        return $this->model->create($data);
    }
    
    public function checkChat($evaluation_id){
        return $this->model->where(['evaluation_id'=>$evaluation_id,'status'=>1])->count()==1;
    }

    public function getChatIdByEvaluationId($evaluation_id){
        return $this->model->where(['evaluation_id'=>$evaluation_id,'status'=>1])->first()->id;
    }

    public function getEvaluationIdByChatId($chat_id){
        return $this->model->where(['id'=>$chat_id])->first()->evaluation_id;
    }

    public function getChatMembersById($id){
        return $this->model->select('members')->where(['id'=>$id,'status'=>1])->first();
    }

    public function getActiveChats($userid){
        
        return $this->model->select('id','evaluation_id')->where('status',1)->where('open_channel',1)->where('members','LIKE','%@'.$userid.'@%')->get();
    }

    public function chatToggler($chat_id, $open_channel){
        $this->model->where('id',$chat_id)->update(['open_channel'=>$open_channel]);
    }
}
?>