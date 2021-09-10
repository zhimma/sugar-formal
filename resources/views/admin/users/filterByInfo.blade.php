@extends('admin.main')
@section('app-content')
<style>
	.error_msg {
		color:red;
		display:inline-block;
	}
    body table tr th {text-align:center;}
	body table tr td  input.number_input {width:60px;}
	body table.table-data .engroup1,body table.table-data .engroup1 a {color:blue;}
	body table.table-data .engroup2, body table.table-data .engroup2 a {color:red;}
	body table.table-data tr td {white-space: nowrap;}
    body table.table-data tr td div span.num_value {text-align:right;width:52px;display:inline-block;}
    
	body table.table-data tr.isClosed {background-color:#C9C9C9}
	body table.table-data tr.isClosedByAdmin {background-color:#969696}
	body table.table-data tr.isWarned {background-color:#B0FFB1; !important;}
	body table.table-data tr.banned,table tr.implicitlyBanned {background-color:#FDFF8C; !important;}	
    body table.table-data .atttention_mark {background-color:#ACD6FF !important;}
 	body table.table-data tr.isClosed td:first {background-color:#C9C9C9}
	body table.table-data tr.isClosedByAdmin td:first {background-color:#969696}
	body table.table-data tr.isWarned  td:first {background-color:#B0FFB1; !important;}
	body table.table-data tr.banned  td:first,table tr.implicitlyBanned  td:first {background-color:#FDFF8C; !important;}
    
	body form tr td input + label  {margin-right:15px;text-transform:uppercase;}
	body form tr td input + input {margin-left:5px;}
	body h1 span {font-weight:normal;font-size:15px;}
	h1 a,h1 a:hover,h1 a:visited {text-decoration:none;color:black; !important;}
	body table tr  a.user {cursor:default;text-decoration: none;}
	body table tr  a.user:hover {cursor:default;text-decoration: none;}
    div.user_go_to_end_op_block span a img{width:25px;}
    div.user_go_to_end_op_block span{display:inline-block;width:50%;text-align:center;}
    body table tr td .type_note {width:calc(100% - 52px);display:inline-block;}
    body table tr td a.etc_node,body table tr td a.etc_node:hover {cursor:default;text-decoration: none;}
    body table tr td .etc_includer {display:inline-block;}
    body table tr td.column_of_contain_etc .type_note {width:calc(100% - 52px - 26px) !important;}
</style>
<script type="text/javascript">
    $(function() {
        $('.etc_node').each(function(){
            $(this).attr('title',$(this).attr('title').split(" ").join("").split("\n").join("").split(",").join("\n"));
        });
        
        var etc_cell = $('.etc_includer').parent().addClass('block_of_contain_etc').parent();
        var etc_cell_class = etc_cell.attr('class');
        var first_etc_cell_class = etc_cell_class.split(' ')[0];        
        etc_cell.addClass('cell_of_contain_etc');
        $('.'+first_etc_cell_class).addClass('column_of_contain_etc');
        $('.block_of_contain_etc').each(function(){
            var now_width = $(this).width();
            $(this).width((parseInt(now_width)+26).toString()+'px');
        });
        $('.block_of_atttention_mark').parent().addClass('atttention_mark');
    })
</script>
<body style="padding: 15px;">
    <h1><a href="{{request()->url()}}">發信、檢舉、封鎖異常查詢</a><span>{{$start_date ?? ''}} @if(isset($start_date) && isset($end_date)) ～ @endif {{$end_date ?? ''}}@if(isset($start_date) || isset($end_date)) 有登入記錄之使用者 @endif</span></h1>
            <h3 style="text-align: left;">搜尋</h3>
            <form action="{{request()->url()}}">
                <table class="table-hover table table-bordered" style="width: 80%;">
					<tr>
						<th>性別</th>
						<td>
							<input type="radio" name="en_group" value="1" @if(request()->en_group=='1') checked @endif> 男</input>
							<input type="radio" name="en_group" value="2" @if(request()->en_group=='2' || !request()->en_group) checked @endif> 女</input>
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
							<label>大於</label><input type="number" name="reportedGtNum" min="0"  value="{{request()->reportedGtNum ?? 0}}"  class="number_input" />
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
							<label>大於</label><input type="number" name="blockedGtNum" min="0" value="{{request()->blockedGtNum ?? 0}}" class="number_input" />
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
							<label>大於</label><input type="number" name="blockOtherGtNum" min="0"  value="{{request()->blockOtherGtNum ?? 0}}"  class="number_input"/>
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

                            <button class='text-white btn btn-primary' onclick="location.href='{{request()->url()}}';return false;">重置</button>
                        </td>						
                    </tr>
                </table>
            </form>
            
                共有 {{ $data->total() }} 筆資料
            @if(($data??null) && $data->total()>0)
			{!! $data->links('pagination::sg-pages') !!}	
                <table class="table-hover table table-bordered table-data">
                    <tr>
						<th nowrap>{!!str_repeat('&nbsp;',11)!!}Email{!!str_repeat('&nbsp;',10)!!}</th>
						<th nowrap>&nbsp;&nbsp;&nbsp;&nbsp;暱稱&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;一句話&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;關於我&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<th nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;約會模式&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th nowrap>七天</th>
                        <th nowrap>總計</th>
                        <th nowrap>封鎖</th>
                        <th nowrap>機型</th>
                        <th nowrap>照片</th>
                        <th nowrap>ip/cfp</th>
                        <th nowrap>上線位置</th>
						<th nowrap>被檢舉次數</th>					
                    </tr>
                    @foreach( $data as  $info)
					@php if(!isset($info->user)) $info->user = new \App\Models\User; @endphp
                    <tr 
					class="
					engroup{{$info->user->engroup ?? ''}}
					@if($info->user->banned ?? false) banned @endif
					@if($info->user->implicitlyBanned ?? false) implicitlyBanned @endif
					@if((isset($info->user->user_meta->isWarned) && $info->user->user_meta->isWarned) || ($info->user->aw_relation ?? false)) isWarned @endif
					@if(($info->user->accountStatus ?? '')===0) isClosed @endif
					@if(($info->user->account_status_admin ?? '')===0) isClosedByAdmin @endif
                    ">
						<td>
							<a href="{!!route('users/advInfo',$info->user_id)!!}"  target="_blank">{{ $info->user->email  ?? ''}}</a>
							@if(isset($info->user) && $info->user->engroup == 1 && $info->user->isVip()) 
							<i class="m-nav__link-icon fa fa-diamond"></i>						
							@endif
                            @if(!($info->ignore??false))
                            <div class="user_go_to_end_op_block">
                                <span>
                                    <a data-user_id="{{$info->user_id}}"  title="暫時置底2周" class="switch_go_to_end_tmp" href="{!!route('users/filterByInfoIgnore')!!}?op=1&level=14&user_id={{$info->user_id}}"><img src="{!!asset('new/images/go_to_end_tmp.png')!!}" /></a>
                                </span>
                                <span>
                                    <a data-user_id ="{{$info->user_id}}" title="永久置底" class="switch_go_to_end" href="{!!route('users/filterByInfoIgnore')!!}?op=1&level=9999&user_id={{$info->user_id}}"><img src="{!!asset('new/images/go_to_end.jpg')!!}" /></a>
                                </span>
                            </div>
                            @else
                            <div>
                             @if($info->ignore->level==14) ( {{\Carbon\Carbon::parse($info->ignore->created_at)->format('m/d H:i')}}起暫時置底兩周 ) @elseif($info->ignore->level==9999) ( 永久置底 ) @endif  
                            </div>
                            @endif
						</td>
						<td>
							<a href="#" class="user user_name" title="{{$info->user->name}}" onclick="return false;">{{ mb_strlen($info->user->name)>8?mb_substr($info->user->name,0,9).'...':$info->user->name }}</a>
						</td>				
						<td>
							<a href="#" class="user user_title" title="{{$info->user->title}}" onclick="return false;">{{ mb_strlen($info->user->title)>16?mb_substr($info->user->title,0,17).'...':$info->user->title }}</a>
						</td>
						<td>
							<a href="#" class="user user_meta_about" title="{{$info->user->user_meta->about}}" onclick="return false;">{{ mb_strlen($info->user->user_meta->about)>16?mb_substr($info->user->user_meta->about,0,17).'...':$info->user->user_meta->about }}</a>
						</td>
						<td>
							<a href="#" class="user user_meta_style" title="{{$info->user->user_meta->style}}" onclick="return false;">{{ mb_strlen($info->user->user_meta->style)>16?mb_substr($info->user->user_meta->style,0,17).'...':$info->user->user_meta->style }}</a>
						</td>
						<td class="@if($info->message_count_7>$info->visit_other_count_7) atttention_mark  @endif">
							<div><span class="type_note">發信:</span><span class="num_value">{{$info->message_count_7 ?? 0}}</span></div>
							<div><span class="type_note">瀏覽:</span><span class="num_value">{{$info->visit_other_count_7 ?? 0}}</span></div>
						</td>
						<td class="@if($info->message_count>$info->visit_other_count ) atttention_mark  @endif">
							<div><span class="type_note">發信:</span><span class="num_value">{{$info->message_count ?? 0}}</span></div>
							<div><span class="type_note">瀏覽:</span><span class="num_value">{{$info->visit_other_count ?? 0}}</span></div>
						</td>
						<td>
							<div><span class="type_note">封鎖:</span><span class="num_value">{{$info->blocked_other_count ?? 0}}</span></div>
							<div><span class="type_note">被封鎖:</span><span class="num_value">{{$info->be_blocked_other_count ?? 0}}</span></div>
						</td>	
						<td>
                        @forelse($info->sub()->where('cat','device')->get() as $sub)
                            <div class="@if($sub->type=='windows' || $sub->type=='linux'  || $sub->type=='android')block_of_atttention_mark @endif"><span class="type_note">{{$sub->type}}:</span><span class="num_value">{{$sub->count_num}}</span></div>
                        @empty
                            <div>無資料</div>
                        @endforelse
						</td> 
                        <td class="@if(($info->pic_name_notregular_count ?? 0)>0) atttention_mark @endif">
                        @if($info->pic_name_regular_count ?? 0)
 							<div><span class="type_note">標準:</span><span class="num_value">{{$info->pic_name_regular_count}}</span></div>
                        @endif
                        @if($info->pic_name_notregular_count ?? 0)

                            <div><span class="type_note">不標準:</span><span class="num_value">{{$info->pic_name_notregular_count}}</span></div>   
                        @endif
                        @if($info->pic_name_empty_count ?? 0)
                            <div><span class="type_note">未記錄:</span><span class="num_value">{{$info->pic_name_empty_count}}</span></div>                                                   
                        @endif
                        </td>
						<td>
							<div><span class="type_note">CFP:</span><span class="num_value">{{$info->differ_cfpid_count ?? 0}}</span></div>
							<div><span class="type_note">IP:</span><span class="num_value">{{$info->differ_ip_count ?? 0}}</span></div>
						</td>                        
                        <td class="cell_of_country">
                        @foreach($info->sub()->where('cat','country')->orderBy('type','desc')->get() as $cidx=>$sub)
                            @if($cidx==4) <span class="etc_includer"><a class="etc_node" title=" @elseif($cidx>4) {{$sub->type==''?'無紀錄':$sub->type}}:{{$sub->count_num ?? 0}}, @endif
                            @if($cidx<4)
                                <div><span class="type_note">{{$sub->type==''?'無紀錄':$sub->type}}:</span><span class="num_value">{{$sub->count_num ?? 0}}</span>
                                @if($cidx<3)</div>@endif
                            @endif
                        @endforeach
                        @if(($cidx??null) && $cidx>=4)
                            " onclick="return false;">...etc</a></span></div>
                        @endif                        
						</td> 
						<td >
							{{$info->be_reported_other_count ?? 0}}
						</td>                        
                    </tr>
                    @endforeach
                </table>
				
				{!! $data->links('pagination::sg-pages') !!}				
            @endif
</body>
@stop
