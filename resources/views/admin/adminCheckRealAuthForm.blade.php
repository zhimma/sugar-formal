@extends('admin.main')
@section('app-content')
    <style>

    .gjrz_nr{width: 94%; margin: 0 auto; display: table; border-radius: 10px; box-shadow: 0 5px 10px rgba(123,123,123,0.3); background: url(../../alert/images/rz_022.png) no-repeat TOP; 
    background-size: 100%;padding: 15px 0; margin-top:30px;}

    .gjrz_nr01{width: 94%; margin: 0 auto; display: table; border-radius: 10px; box-shadow: 0 5px 10px rgba(123,123,123,0.3); background: url(../../alert/images/rz_011.png) no-repeat TOP; 
    background-size: 100%;padding: 15px 0; margin-top:30px;}


    .gjr_b{width: 90%;margin: 0 auto; display: table; margin-top: 30px;}
    .gjr_b img{ height:50px; float: left;}
    .gjr_nr01{width: 95%; margin: 0 auto; display: table; background: rgba(255,255,255,0.6);box-shadow: 0 5px 10px rgba(184,184,184,0.5);border-radius: 10px; 
    margin-top: 10px; color: #666666; padding: 10px;}
    .gjr_nr01 h2{width: 96%; margin: 0 auto; display: table;}
    .gjr_nr02{width: 95%; margin: 0 auto; display: table;background: linear-gradient(to bottom,#fff3f4,#fff);box-shadow:0 7px 5px rgba(223,160,167,0.3);border-radius: 10px;}
    .gjr_nr02_h2{text-align:left;width: 95%; margin: 0 auto; display: table; color: #ee5472; font-size: 15px;}
    .gir_pa{ padding:15px 0 10px 0;}
    .gir_pa01{ padding:15px 0 15px 0;}
    .gir_top20{ margin-top: 20px;}
    .gir_top15{ margin-top: 15px;}


    .gir_border{ border-radius: 10px !important; border: #ffc2c9 1px solid !important; background-color: #fff;}

.shou{width:94%; height:40px; line-height:40px; margin:0 auto; border-bottom:#e44e71 1px solid; margin-top:0px; margin-bottom:20px;}
.shou span{ color:#fd5678; border-bottom:#fd5678 3px solid; font-size:20px; font-weight:bold; line-height:50px;}
.shou font{ color:#999999; margin-left:10px;font-size:16px;}
.shou_but{float:right; padding:0px 15px; background:#fd5678; height:24px; line-height:24px; color:#ffffff; text-align:center; border-radius:100px; margin-top:13px;}
.shou_but:hover{color:#ffffff;box-shadow:inset 0px 15px 10px -10px #f83964,inset 0px -10px 10px -20px #f83964;background:#fe92a8;}

.sjlist_heg{ min-height:445px !important;}
.sjlist_li{ min-height:528px; margin-bottom:150px;}    

.g_rznz{width: 95%; margin: 0 auto; display: table;background: #fff; border-radius: 10px;box-shadow: 0 4px 10px rgba(254,146,169,0.3); padding: 18px 0 20px 0;}
.g_rznz h2{text-align:left;font: inherit;width: 95%; margin: 0 auto; display: table; color: #666;}
.g_rinput{width: 100%; height: 40px; border: #ffc2c9 1px solid; border-radius: 10px; color: #333; padding:0 10px; outline: none;}
.g_rtext{width: 100%; height:75px; border: #ffc2c9 1px solid; border-radius: 10px; color: #333; padding:5px 10px; outline: none; line-height: 25px;}
.ga_or{ background:#fff8f9; color: #d2d2d2; border-radius: 100px; display:table; padding: 0 20px; margin:8px 0;}
.ga_or01{ background:#fff; color: #d2d2d2; border-radius: 100px; display:table; padding: 0 20px; margin:8px 0;}

    </style>
    <h1>站長審核 - 女會員認證 - 表單內容</h1>
    <div class="shou"><span>{{$apply_entry->real_auth_type->name??null}}</span>
        <font>{{$user->name??null}}&nbsp;&nbsp;&nbsp;&nbsp;  <a href="{{route('users/advInfo',['id'=>$user->id])}}" target="_blank">{{$user->email??null}}</a></font>
    </div>
    @foreach($entry_list->whereNull('parent_id') as $q_idx=>$question_entry)
    <div class="gjr_nr02 gir_top20 gir_pa01">
        <h2 class="gjr_nr02_h2">{{$q_idx+1}}:{{$question_entry->question}}{{$question_entry->required?'(必填)':''}}</h2>
        <div class="rzmatop_5">
        {!!$service->getUserReplyLayoutByQuEntry($question_entry)!!} 
        </div>

        @foreach($entry_list->where('parent_id',$question_entry->id) as $sub_q_idx=>$sub_question_entry)
        <div class="g_rznz matop15 rzmabot_20">
             <h2>{{$sub_question_entry->question}}</h2>
            {!!$service->getUserReplyLayoutByQuEntry($sub_question_entry)!!}
        </div>                        
        @endforeach
    </div>    
    @endforeach
@stop
