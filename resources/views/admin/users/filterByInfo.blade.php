@extends('admin.main')
@section('app-content')
<style>
	.error_msg {
		color:red;
		display:inline-block;
	}
	body table tr td  input.number_input {width:60px;}
	body table.table-data .engroup1,body table.table-data .engroup1 a {color:blue;}
	body table.table-data .engroup2, body table.table-data .engroup2 a {color:red;}
	body table.table-data tr td {white-space: nowrap;}
	body table.table-data tr.isClosed {background-color:#C9C9C9}
	body table.table-data tr.isClosedByAdmin {background-color:#969696}
	body table.table-data tr.isWarned {background-color:#B0FFB1; !important;}
	body table.table-data tr.banned,table tr.implicitlyBanned {background-color:#FDFF8C; !important;}	
	body form tr td input + label  {margin-right:15px;text-transform:uppercase;}
	body form tr td input + input {margin-left:5px;}
</style>
<body style="padding: 15px;">
    <h1>發信、檢舉、封鎖異常查詢</h1>
            <h3 style="text-align: left;">搜尋</h3>
            <form id='message' method='POST'>
                {!! csrf_field() !!}
                <table class="table-hover table table-bordered" style="width: 80%;">
					<x-admin.latest-date-input/>
					<tr>
						<th>性別</th>
						<td>
							<input type="radio" name="en_group" value="1" @if(request()->en_group=='1') checked @endif> 男</input>
							<input type="radio" name="en_group" value="2" @if(request()->en_group=='2') checked @endif> 女</input>
							<div class="error_msg">{{$errors->first('en_group') ?? ''}}</div>
						</td>
					</tr>					
                    <tr>
                        <th>"過去7天發信次數"大於"過去7天瀏覽其他會員次數"</th>
                        <td>
                            <input type="radio" name="msg_gt_visit_7days" id="msg_gt_visit_7days_default" value="" @if(request()->msg_gt_visit_7days=='') checked @endif >
							<label for="msg_gt_visit_7days_default">不選擇</label>
							<input type="radio" name="msg_gt_visit_7days" id="msg_gt_visit_7days_and" value="and" @if(request()->msg_gt_visit_7days=='and') checked @endif >
							<label for="msg_gt_visit_7days_and">and</label>
							<input type="radio" name="msg_gt_visit_7days" id="msg_gt_visit_7days_or" value="or" @if(request()->msg_gt_visit_7days=='or') checked @endif >
							<label for="msg_gt_visit_7days_or">or</label>
							<div class="error_msg">{{$errors->first('info_filter') ?? ''}}</div>
						</td>
                    </tr>
                    <tr>
                        <th>"發信次數"大於"瀏覽其他會員次數"</th>
                        <td>
                            <input type="radio" name="msg_gt_visit" id="msg_gt_visit_default" value="" @if(request()->msg_gt_visit=='') checked @endif >
							<label for="msg_gt_visit_default">不選擇</label>
							<input type="radio" name="msg_gt_visit" id="msg_gt_visit_and" value="and" @if(request()->msg_gt_visit=='and') checked @endif >
							<label for="msg_gt_visit_and">and</label>
							<input type="radio" name="msg_gt_visit" id="msg_gt_visit_or" value="or" @if(request()->msg_gt_visit=='or') checked @endif >
							<label for="msg_gt_visit_or">or</label>
							<div class="error_msg">{{$errors->first('info_filter') ?? ''}}</div>
						</td>
                    </tr>
                    <tr>
                        <th>被檢舉次數</th>
                        <td>
							<label>大於</label><input type="number" name="reportedGtNum" value="{{request()->reportedGtNum ?? 0}}"  class="number_input" />
                            <input type="radio" name="reported_gt_num" id="reported_gt_num_default" value="" @if(request()->reported_gt_num=='') checked @endif >
							<label for="reported_gt_num_default">不選擇</label>
							<input type="radio" name="reported_gt_num" id="reported_gt_num_and" value="and" @if(request()->reported_gt_num=='and') checked @endif >
							<label for="reported_gt_num_and">and</label>
							<input type="radio" name="reported_gt_num" id="reported_gt_num_or" value="or" @if(request()->reported_gt_num=='or') checked @endif >
							<label for="reported_gt_num_or">or</label>
							<div class="error_msg">{{$errors->first('info_filter') ?? ''}}</div>
						</td>
                    </tr>
                    <tr>
                        <th>被封鎖次數</th>
                        <td>
							<label>大於</label><input type="number" name="blockedGtNum" value="{{request()->blockedGtNum ?? 0}}" class="number_input" />
                            <input type="radio" name="blocked_gt_num" id="blocked_gt_num_default" value="" @if(request()->blocked_gt_num=='') checked @endif >
							<label for="blocked_gt_num_default">不選擇</label>
							<input type="radio" name="blocked_gt_num" id="blocked_gt_num_and" value="and" @if(request()->blocked_gt_num=='and') checked @endif >
							<label for="blocked_gt_num_and">and</label>
							<input type="radio" name="blocked_gt_num" id="blocked_gt_num_or" value="or" @if(request()->blocked_gt_num=='or') checked @endif >
							<label for="blocked_gt_num_or">or</label>
							<div class="error_msg">{{$errors->first('info_filter') ?? ''}}</div>
						</td>
                    </tr>	
                    <tr>
                        <th>封鎖他人次數</th>
                        <td>
							<label>大於</label><input type="number" name="blockOtherGtNum" value="{{request()->blockOtherGtNum ?? 0}}"  class="number_input"/>
                            <input type="radio" name="block_other_gt_num" id="block_other_gt_num_default" value="" @if(request()->block_other_gt_num=='') checked @endif >
							<label for="block_other_gt_num_default">不選擇</label>
							<input type="radio" name="block_other_gt_num" id="block_other_gt_num_and" value="and" @if(request()->block_other_gt_num=='and') checked @endif >
							<label for="block_other_gt_num_and">and</label>
							<input type="radio" name="block_other_gt_num" id="block_other_gt_num_or" value="or" @if(request()->block_other_gt_num=='or') checked @endif >
							<label for="block_other_gt_num_or">or</label>
							<div class="error_msg">{{$errors->first('info_filter') ?? ''}}</div>
						</td>
                    </tr>						
                    <tr>
                        <td colspan="2">
                            <button class='text-white btn btn-primary submit'>送出</button>
                        </td>
                    </tr>
                </table>
            </form>
            @if(isset($data))
                共有 {{ count($data) }} 筆資料
                <table class="table-hover table table-bordered table-data">
                    <tr>
						<th nowrap>{!!str_repeat('&nbsp;',26)!!}Email{!!str_repeat('&nbsp;',24)!!}</th>
						<th nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;暱稱&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;一句話&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;關於我&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;約會模式&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th nowrap>最後上線時間</th>
                        <th nowrap>七天發信次數</th>
						<th nowrap>七天瀏覽會員次數</th>
						<th nowrap>發信次數</th>
						<th nowrap>瀏覽會員次數</th>
						<th nowrap>被檢舉次數</th>
						<th nowrap>封鎖他人次數</th>
						<th nowrap>被封鎖次數</th>						
                    </tr>
                    @foreach( $data as $user_id => $user)
                    <tr class="{{$user->tag_class}}">
						<td>
							<a href="{!!route('users/advInfo',$user->id)!!}"  target="_blank">{{ $user->email }}</a>
							@if($user->engroup == 1 && $user->isVip()) 
							<i class="m-nav__link-icon fa fa-diamond"></i>						
							@endif
						</td>
						<td>
							{{ mb_strlen($user->name)>8?mb_substr($user->name,0,9).'...':$user->name }}
						</td>				
						<td>
							{{ mb_strlen($user->title)>16?mb_substr($user->title,0,17).'...':$user->title }}
						</td>
						<td>
							{{ mb_strlen($user->user_meta->about)>16?mb_substr($user->user_meta->about,0,17).'...':$user->user_meta->about }}
						</td>
						<td>
							{{ mb_strlen($user->user_meta->style)>16?mb_substr($user->user_meta->style,0,17).'...':$user->user_meta->style }}
						</td>
						<td>
							{{isset($user->last_login)?date('m/d-H:i',strtotime($user->last_login)):''}}
						</td>
						<td>
							{{$user->advInfo['message_count_7'] ?? 0}}
						</td>
						<td>
							{{$user->advInfo['visit_other_count_7'] ?? 0}}
						</td>
						<td>
							{{$user->advInfo['message_count'] ?? 0}}
						</td>
						<td>
							{{$user->advInfo['visit_other_count'] ?? 0}}
						</td>
						<td>
							{{$user->advInfo['be_reported_other_count'] ?? 0}}
						</td>
						<td>
							{{$user->advInfo['blocked_other_count'] ?? 0}}
						</td>	
						<td>
							{{$user->advInfo['be_blocked_other_count'] ?? 0}}
						</td>						
                    </tr>
                    @endforeach
                </table>
            @endif
</body>
@stop
