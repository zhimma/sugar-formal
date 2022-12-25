<?php

namespace App\Repositories;

use App\Models\AnonymousEvaluationMessage;
use Carbon\Carbon;

class AnonymousEvaluationMessageRepository
{
    /**
     * @var model
     */
    protected $model;

    /**
     * @param models
     */
    public function __construct(AnonymousEvaluationMessage $model)
    {
        $this->model = $model;
    }
    
    public function create($data){
        $this->model->create($data);
    }

    public function revoke($data){
        $this->model->where('id',$data['id'])
                ->where('anonymous_evaluation_chat_id',$data['anonymous_evaluation_chat_id'])
                ->where('user_id',$data['user_id'])
                ->update(['unsend'=>1]);
    }

    public function deleteBetween($data) {
        $message = $this->model->where('anonymous_evaluation_chat_id', $data['chat_id'])->orderBy('created_at', 'desc')->first();

        if(!isset($message)){
            return false;
        }
        if($message->is_row_delete_1 == 0) {
            $this->deleteRowMessage($data, 0);
        }
        else if($message->is_row_delete_1 <> 0 && $message->is_row_delete_2 == 0) {
            $this->deleteRowMessage($data, 1);
        }
    }

    public static function deleteRowMessagesFromDB($msg_id) {
        return $this->model->where('id', $msg_id)->where('is_row_delete_1', '!=', 0)->where('is_row_delete_2', '!=', 0)->delete();
    }

    public  function deleteRowMessage($data, $step) {
        
        $message = $this->model->where('anonymous_evaluation_chat_id', $data['chat_id'])->get();
        for($i = 0 ; $i < $message->count() ; $i++) {
            if($step == 0) {
                $message[$i]->is_row_delete_1 = $data['user_id'];
                $message[$i]->updated_at = Carbon::now();
            }
            else if($step == 1) {
                $message[$i]->is_row_delete_2 = $data['user_id'];
                $message[$i]->updated_at = Carbon::now();
                $this->deleteRowMessagesFromDB($message[$i]->id);
            }
            $message[$i]->save();
        }
    }
    
    public function getMessage($data){
        return $this->model->withTrashed()->select('id','user_id','reply_id','read','unsend','content','pictures','deleted_at','created_at')->where('anonymous_evaluation_chat_id',$data['anonymous_evaluation_chat_id'])->where('id','>=',$data['id'])->orderBy('created_at','ASC')->get();
    }

    public function getMessageInfoById($id){
        return $this->model->where('id',$id)->first();
    }
    public function getMessageUserById($id){
        return $this->model->where('id',$id)->first()->user_id;
    }
    public function readMessage($id){
        $message =  $this->model->where('id',$id)->first();
        $message->read = 1;
        return $message->save();
    }
}
?>