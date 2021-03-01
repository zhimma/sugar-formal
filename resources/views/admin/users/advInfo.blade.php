@include('partials.header')
@include('partials.message')
<body style="padding: 15px;">
<h1>
	{{ $user->name }}
	@if($user['vip'])
	    @if($user['vip']=='diamond_black')
	        <img src="/img/diamond_black.png" style="height: 2.5rem;width: 2.5rem;">
	    @else
	        @for($z = 0; $z < $user['vip']; $z++)
	            <img src="/img/diamond.png" style="height: 2.5rem;width: 2.5rem;">
	        @endfor
	    @endif
	@endif
	@for($i = 0; $i < $user['tipcount']; $i++)
	    👍
	@endfor
	@if(!is_null($user['isBlocked']))
	    @if(!is_null($user['isBlocked']['expire_date']))
	        @if(round((strtotime($user['isBlocked']['expire_date']) - getdate()[0])/3600/24)>0)
	            {{ round((strtotime($user['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天
	        @else
	            此會員登入後將自動解除封鎖
	        @endif
		@elseif(isset($user['isBlocked']['implicitly']))
			(隱性)
	    @else
	        (永久)
	    @endif
	@endif
	@if($user['isAdminWarned']==1 OR $userMeta->isWarned==1)
		<img src="/img/warned_red.png" style="height: 2.5rem;width: 2.5rem;">
	@endif
	@if($userMeta->isWarned==0 AND $user->WarnedScore() >= 10 AND $user['auth_status']==1)
		<img src="/img/warned_black.png" style="height: 2.5rem;width: 2.5rem;">
	@endif
	的所有資料
	<a href="edit/{{ $user->id }}" class='text-white btn btn-primary'>修改</a>
	@if($user['isBlocked'])
		<button type="button" id="unblock_user" class='text-white btn @if($user["isBlocked"]) btn-success @else btn-danger @endif' onclick="Release({{ $user['id'] }})" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}"> 解除封鎖 </button>
	@else 
		<a class="btn btn-danger ban-user" id="block_user" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}">封鎖會員</a>
		<a class="btn btn-danger ban-user" id="implicitly_block_user" href="#" data-toggle="modal" data-target="#implicitly_blockade" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}">隱性封鎖</a>
	@endif
	@if($user['isAdminWarned']==1)
		<button type="button" title="{{'於'.$user['adminWarned_createdAt'].'被警示，將於'.(isset($user['adminWarned_expireDate'])? $user['adminWarned_expireDate'] : '永久').'解除站方警示' }}" id="unwarned_user" class='text-white btn @if($user["isAdminWarned"]) btn-success @else btn-danger @endif' onclick="ReleaseWarnedUser({{ $user['id'] }})" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}"> 解除站方警示 </button>
	@else
		<a class="btn btn-danger warned-user" title="站方警示與自動封鎖的警示，只能經後台解除" id="warned_user" href="#" data-toggle="modal" data-target="#warned_modal" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}">站方警示</a>
	@endif
	@if($userMeta->isWarned==0)
		<button class="btn btn-info" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{$user['id']}},1)"
		@if($user->WarnedScore() >= 10 AND $user['auth_status']==1) disabled="disabled" style="background-color: #C0C0C0;border-color: #C0C0C0;" @endif>
			警示用戶({{$user->WarnedScore()}})
		</button>
	@else
		<button class="btn btn-danger" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="WarnedToggler({{$user['id']}},0)">
			取消警示用戶({{$user->WarnedScore()}})
		</button>
	@endif
	<a href="{{ route('users/switch/to', $user->id) }}" class="text-white btn btn-primary">切換成此會員前台</a>
	@if($user['isvip'])
		<button class="btn btn-info" onclick="VipAction({{($user['isvip'])?'1':'0' }},{{ $user['id'] }})"> 取消VIP </button>
		@if($user->engroup==1)
			@if($user->Recommended==1)
				<button class="btn btn-info" onclick="RecommendedToggler({{ $user['id'] }},'1')">給予優選</button>
			@else
				<button class="btn btn-danger ban-user" onclick="RecommendedToggler({{ $user['id'] }},'0')">取消優選</button>
			@endif
		@endif
	@else 
		<button class="btn btn-info" onclick="VipAction({{($user['isvip'])?'1':'0' }},{{ $user['id'] }})"> 升級VIP </button>
	@endif
	@if (Auth::user()->can('admin') || Auth::user()->can('juniorAdmin'))
		<a href="{{ route('AdminMessage', $user['id']) }}" target="_blank" class='btn btn-dark'>撰寫站長訊息</a>
	@elseif (Auth::user()->can('readonly'))
		<a href="{{ route('AdminMessage/readOnly', $user['id']) }}" target="_blank" class='btn btn-dark'>撰寫站長訊息</a>
	@endif

	<form method="POST" action="{{ route('genderToggler') }}" style="margin:0px;display:inline;">
		{!! csrf_field() !!}
		<input type="hidden" name='user_id' value="{{ $user->id }}">
		<input type="hidden" name='gender_now' value="{{ $user->engroup }}">
		<input type="hidden" name="page" value="advInfo" >
		<button type="submit" class="btn btn-warning">變更性別</button>
	</form>

	@if($user->engroup==2)
	<form method="POST" id="form_exchange_period" action="{{ route('changeExchangePeriod') }}" style="margin:0px;display:inline;">
		{!! csrf_field() !!}
		<select class="form-control" style="width:auto; display: inline;" name="exchange_period" id="exchange_period">
			@php
				$exchange_period_name = DB::table('exchange_period_name')->get();
			@endphp
			@foreach($exchange_period_name as $row)
			<option value="{{$row->id}}" @if($user->exchange_period==$row->id) selected @endif>{{$row->name}}</option>
			@endforeach
		</select>
		<input type="hidden" name="id" value="{{$user->id}}">
	</form>
	@endif
	

	@if(is_null($userMeta->activation_token))
		<b style="font-size:18px">已開通會員</b>
	@else
		<a href="{{ route('activateUser',$userMeta->activation_token) }}" class="btn btn-success"> 通過認證信 </a>
	@endif
</h1>
<h4>基本資料</h4>
<table class='table table-hover table-bordered '>
	@php
		//帳號警示時間
		$warnedInfo = \App\Models\SimpleTables\warned_users::where('member_id',$user->id)->first();
        $warnedDay ='';
        if(!is_null($warnedInfo)){
            $warnedDay= date('Y-m-d', strtotime($warnedInfo->created_at));
            $datetime1 = new \DateTime($warnedInfo->expire_date);
            $datetime2 = new \DateTime($warnedInfo->created_at);
            $diffDays = is_null($warnedInfo->expire_date) ? '永久' : $datetime1->diff($datetime2)->days;
        }

        //VIP帳號：起始時間,付費方式,種類,現狀
        $vipInfo = \App\Models\Vip::findByIdWithDateDesc($user->id);

        if(!is_null($vipInfo)){
			$upgradeDay = date('Y-m-d', strtotime($vipInfo->created_at));
			$upgradeWay ='';
			if ($vipInfo->payment_method == 'CREDIT')
				$upgradeWay = '信用卡';
			else if ($vipInfo->payment_method == 'ATM')
				$upgradeWay = 'ATM';
			else if ($vipInfo->payment_method == 'CVS')
				$upgradeWay = '超商代碼';
			else if ($vipInfo->payment_method == 'BARCODE')
				$upgradeWay = '超商條碼';

			$upgradeKind ='';
			if ($vipInfo->payment == 'cc_quarterly_payment')
				$upgradeKind = '定期季繳';
			else if ($vipInfo->payment == 'cc_monthly_payment')
				$upgradeKind = '定期月繳';
			else if ($vipInfo->payment == 'one_quarter_payment')
				$upgradeKind = '單季支付';
			else if ($vipInfo->payment == 'one_month_payment')
				$upgradeKind = '單月支付';

			$vipLog = \App\Models\VipLog::where("member_id", $user->id)->orderBy('id', 'desc')->first();
			//現狀:只有持續中跟未持續兩種。已取消扣款或者一次付清都是未持續。
			if(in_array($vipInfo->payment, ['one_quarter_payment','one_month_payment']) || $vipInfo->active ==0)
				$nowStatus = '未持續';
			else if(str_contains($vipLog, 'cancel') || (str_contains($vipLog, 'Cancel') && !str_contains($vipLog, 'bypass')))
				$nowStatus = '未持續';
			else
				$nowStatus = '持續中';

			$showVipInfo_1 =  $upgradeDay .','.$upgradeWay .','.$upgradeKind .','. $nowStatus ;

			//計算總繳費月數
			//upgrage的log抓起始日，然後從取消的地方抓 expiry，可以算出一段的時間，
			//如果是早期沒記錄expiry的log，再從auto cancellation 抓取消時間,每段時間算出來以後再加起來
			$getLog = \Illuminate\Support\Facades\DB::table('member_vip_log')->where('member_id',$user->id)->orderBy('id')->get();
			$totalMonths = 0;
			$flag = 0; //1:upgrade ,2:cancel
			foreach ($getLog as $log){
			    $action = $log->member_name;

			    //取得vip開始日期
			    if(str_contains($action, 'upgrade')){
					$vipStartDate = $log->created_at;
					$flag = 1;
			    }

			    //取得vip過期日期
			    if($flag  && str_contains($action, 'expiry')){
			        $vipExpiryData = explode(': ',$action)[1];
			        $flag = 2;
			    }else if ($flag  && str_contains($action, 'auto cancellation')) {
					$vipExpiryData = $log->created_at;
			        $flag = 2;
			    }

			    //算這段vip時間
			    if($flag ==2){
					$Date_1 = date("Y-m-d", strtotime($vipStartDate));
					$Date_2 = date("Y-m-d", strtotime($vipExpiryData));
					$d1 = strtotime($Date_1);
					$d2 = strtotime($Date_2);
					$diffDays = round(($d2-$d1)/3600/24);

					$totalMonths += floor($diffDays/30);
					$flag = 0;
			    }

			    //有payment紀錄, 直接計入繳費月數
			    if(str_contains($action, 'cc_quarterly_payment') || str_contains($action, 'one_quarter_payment')){
			        $totalMonths += 3;
			        $flag = 0;
			    }
			    else if(str_contains($action, 'cc_monthly_payment') || str_contains($action, 'one_month_payment')){
			        $totalMonths += 1;
			        $flag = 0;
			    }
			}
			$showVipInfo_0 =  $upgradeDay .','.$totalMonths .','. $nowStatus ;

			if($nowStatus =='未持續')
			    $showVipInfo = $showVipInfo_0;
			else
				$showVipInfo = $showVipInfo_1;
        }else{
            $nowStatus = '';
            $showVipInfo =  '暫無資料' ;
        }
	@endphp
	<tr>
		<th>會員ID</th>
		<th>暱稱</th>
		<th>標題</th>
		<th>男/女</th>
		<th>Email</th>
		<th>建立時間</th>
		<th>更新時間</th>
		@if($nowStatus =='未持續')<th>VIP起始時間,總繳費月數,現狀</th> @else <th>VIP起始時間,付費方式,種類,現狀</th> @endif
		@if(!is_null($warnedInfo))<th>警示時間</th>@endif
		<th>上次登入</th>
		<th>上站次數</th>
	</tr>
	<tr>
		<td>{{ $user->id }}</td>
		<td>{{ $user->name }}</td>
		<td>{{ $user->title }}</td>
		<td>@if($user->engroup==1) 男 @else 女 @endif</td>
		<td>{{ $user->email }}</td>
		<td>{{ $user->created_at }}</td>
		<td>{{ $user->updated_at }}</td>
		<td>{{ $showVipInfo }}</td>
		@if(!is_null($warnedInfo))<td>{{ !is_null($warnedInfo) ? $warnedDay.'('.$diffDays.')' : ''}}</td>@endif
		<td>{{ $user->last_login }}</td>
		<td>{{ $user->login_times }}</td>
	</tr>
</table>
<h4>詳細資料</h4>
<table class='table table-hover table-bordered'>	
	<tr>
		<th>會員ID</th>
		<td>{{ $userMeta->user_id }}</td>
		<th>手機</th>
		<td>{{ $userMeta->phone }}</td>
		<th>是否已啟動</th>
		<td>@if($userMeta->is_active == 1) 是 @else 否 @endif</td>
		<th rowspan='3'>照片 <br><a href="editPic_sendMsg/{{ $user->id }}" class='text-white btn btn-primary'>照片&發訊息</a></th>
		<td rowspan='3'>@if($userMeta->pic) <img src="{{$userMeta->pic}}" width='150px'> @else 無 @endif</td>
	</tr>
	<tr>
		<th>縣市</th>
		<td>@if($userMeta->city=='0') 無 @else {{ $userMeta->city }} {{ $userMeta->area }} @endif</td>
		<th>拒絕查詢的縣市</th>
		<td>@if($userMeta->blockcity=='0') 無 @else {{ $userMeta->blockcity }} {{ $userMeta->blockarea }} @endif</td>
		<th>預算</th>
		<td>{{ $userMeta->budget }}</td>
	</tr>
	<tr>
		<th>生日</th>
		<td>{{ date('Y-m-d', strtotime($userMeta->birthdate)) }}</td>
		<th>身高</th>
		<td>{{ $userMeta->height }}</td>
		<th>職業</th>
		<td>{{ $userMeta->occupation }}</td>
	</tr>
	<tr>
		<th>體重</th>
		<td>{{ $userMeta->weight }}</td>
		<th>罩杯</th>
		<td>{{ $userMeta->cup }}</td>
		<th>體型</th>
		<td>{{ $userMeta->body }}</td>
		<th>現況</th>
		<td>{{ $userMeta->situation }}</td>
	</tr>
	<tr>
		<th>關於我</th>
		<td colspan='3'>{{ $userMeta->about }}</td>
		<th>期待的約會模式</th>
		<td colspan='3'>{{ $userMeta->style }}</td>
	</tr>
	<tr>
		<th>教育</th>
		<td>{{ $userMeta->education }}</td>
		<th>婚姻</th>
		<td>{{ $userMeta->marriage }}</td>
		<th>喝酒</th>
		<td>{{ $userMeta->drinking }}</td>
		<th>抽菸</th>
		<td>{{ $userMeta->smoking }}</td>
	</tr>
	<tr>	
		<th>產業1</th>
		<td>{{ $userMeta->domainType }}</td>
		<th>封鎖的產業1</th>
		<td>{{ $userMeta->blockdomainType }}</td>
		<th>產業2</th>
		<td>{{ $userMeta->domain }}</td>
		<th>封鎖的產業2</th>
		<td>{{ $userMeta->blockdomain }}</td>
	</tr>
	<tr>
		<th>職業</th>
		<td>{{ $userMeta->job }}</td>
		<th>資產</th>
		<td>{{ $userMeta->domain }}</td>
		<th>年收</th>
		<td>{{ $userMeta->income }}</td>
		<th>信息通知</th>
		<td>{{ $userMeta->notifmessage }}</td>
	</tr>
	<tr>
		<th>隱藏地區</th>
		<td>@if($userMeta->isHideArea==1) 是 @else 否 @endif</td>
		<th>隱藏罩杯</th>
		<td>@if($userMeta->isHideCup==1) 是 @else 否 @endif</td>
		<th>隱藏體重</th>
		<td>@if($userMeta->isHideWeight==1) 是 @else 否 @endif</td>
		<th>隱藏職業</th>
		<td>@if($userMeta->isHideOccupation==1) 是 @else 否 @endif</td>	
	</tr>
	<tr>
		@if($user->engroup==2)
		<th>包養關係</th>
		<td>
			@php
				$exchange_period_name = DB::table('exchange_period_name')->where('id',$user->exchange_period)->first();
			@endphp
			{{$exchange_period_name->name}}
		</td>


		@endif
		<th>收件夾顯示方式</th>
		<td>{{ $userMeta->notifhistory }}</td>
		<th>建立時間</th>
		<td>{{ $userMeta->created_at }}</td>
		<th>更新時間</th>
		<td>{{ $userMeta->updated_at }}</td>
	</tr>
	<tr>
		<form action="{{ route('users/save', $user->id) }}" method='POST'>
			{!! csrf_field() !!}
			<th>站長註解<div><button type="submit" class="text-white btn btn-primary">修改</button></div></th>
			<td colspan='3'><textarea class="form-control m-input" type="textarea" name="adminNote" rows="3" maxlength="300">{{ $userMeta->adminNote }}</textarea></td>
		</form>
	</tr>
</table>
@if($user->engroup==1)
<h4>PR值</h4>
<table class="table table-hover table-bordered">
	<tr>
		<th width="30%">PR值</th>
		<th>PR值歷程</th>
	</tr>
	<tr>
		<td>@if(isset($pr) && $pr != false){{$pr}}@else不列計@endif</td>
		<td>@if(isset($pr_log) && $pr != false){{$pr_log->pr_log}}@endif</td>
	</tr>
</table>
@endif

<h4>被檢舉紀錄</h4>
<table class="table table-hover table-bordered">
	<tr>
		<th>暱稱</th>
		<th>帳號</th>
		<th>是否計分</th>
		<th>檢舉時間</th>
		<th>VIP</th>
		<th>會員認證</th>
		<th>檢舉理由</th>
		<th>檢舉類型</th>
		<th>計分</th>
	</tr>
	@foreach($report_all as $row)
		<tr>
			<td @if(!is_null($row['isBlocked'])) style="color: #F00;" @endif>
				{{ $row['name'] }}
				@if($row['vip'])
				    @if($row['vip']=='diamond_black')
				        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
				    @else
				        @for($z = 0; $z < $row['vip']; $z++)
				            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
				        @endfor
				    @endif
				@endif
				@for($i = 0; $i < $row['tipcount']; $i++)
				    👍
				@endfor
				@php
					$rowuser = \App\Models\User::findById($row['reporter_id']);
				@endphp
				@if(isset($rowuser))
					{{ $rowuser->WarnedScore() }}
				@else
					無會員資料
				@endif
			</td>
			<td>
				<a href="{{ route('users/advInfo', $row['reporter_id']) }}" target='_blank'>
					{{ $row['email'] }}
				</a>
			</td>
			<td>
				<form action="/admin/users/reportedToggler" method="POST">
					{{ csrf_field() }}
					@if(isset($row['report_dbid']))
						<input type="hidden" value="{{ $row['report_dbid'] }}" name="report_dbid">
					@endif
					@if(isset($row['reported_id']))
						<input type="hidden" value="{{ $row['reported_id'] }}" name="reported_id">
					@endif
					@if(isset($row['reporter_id']))
						<input type="hidden" value="{{ $row['reporter_id'] }}" name="reporter_id">
					@endif
					<input type="hidden" value="{{ $row['report_table'] }}" name="report_table">
					<input type="hidden" value="{{ $row['cancel'] }}" name="cancel">
					<button type="submit" class='btn btn-outline-success ban-user'>
						@if($row['cancel']==0)
							不計算
						@elseif($row['cancel']==1)
							計算
						@endif
					</button>
				</form>
			</td>
			<td>{{ $row['created_at'] }}</td>
			<td>@if($row['isvip']==1) VIP @endif</td>
			<td>@if($row['auth_status']==1) 已認證 @else N/A @endif</td>
			<td>{{ $row['content'] }}</td>
			<td>{{ $row['report_type'] }}</td>
			<td>@if( ($row['engroup']==2 && $row['auth_status']==1) || ($row['engroup']==1 && $row['isvip']==1) ) 5 @else 3.5 @endif</td>
		</tr>
	@endforeach
</table>
<h4>評價紀錄</h4>
<table class="table table-hover table-bordered">
	<tr>
		<th>暱稱</th>
		<th>帳號</th>
		<th>評價時間</th>
		<th>VIP</th>
		<th>會員認證</th>
		<th>星等分數</th>
		<th>評價內容</th>
	</tr>
	@foreach($out_evaluation_data as $row)
		<tr>
			<td>{{ $row['to_name'] }}</td>
			<td>{{ $row['to_email'] }}</td>
			<td>{{ $row['created_at'] }}</td>
			<td>@if($row['to_isvip']==1) VIP @endif</td>
			<td>@if($row['to_auth_status']==1) 已認證 @else N/A @endif</td>
			<td>{{ $row['rating'] }}</td>
			<td>{{ $row['content'] }}</td>
		</tr>
	@endforeach
</table>

<h4>帳號登入紀錄</h4>
<table id="table_userLogin_log" class="table table-hover table-bordered">
	<tr>
		<td>登入時間</td>
	</tr>
	@foreach($userLogin_log as $logInLog)
		<tr>
			<td>
				<a>{{ substr($logInLog->loginDate ,0 ,10) . ' ['. $logInLog->dataCount .']' }}  </a>
				<ul class="hidden">
					<li>
						<table class="table table-hover table-bordered">
							<tr>
								<th>登入時間</th>
								<th>IP</th>
								<th>登入裝置</th>
							</tr>
							@php
							//$loginDates = explode(",&p,", $logInLog->loginDates);
							//$userAgents = explode(",&p,", $logInLog->userAgents);
							//$ips = explode(",&p,", $logInLog->ips);
							@endphp
							@php
							//$items = explode("/sojs/", $logInLog->items);
							//print_r($items);
							@endphp
							@foreach($logInLog->items as $key => $item)
							<tr>
								<?php
									// $sitem = explode("/i#", $item);
					                if(isset($sitem[1]) && preg_match("/(iPod|iPhone)/", $item->userAgent))
					                    $device = '手機';
					                else if(isset($sitem[1]) && preg_match("/iPad/", $item->userAgent))
					                    $device = '平板';
					                else if(isset($sitem[1]) && preg_match("/android/i", $item->userAgent))
					                    $device = '手機';
					                else
					                    $device = '電腦';
					            ?>
								<td>{{$item->created_at}}</td>
								<td>{{$item->ip}}</td>
					            <td>
					                {{ $device }}
					            </td>
							</tr>
							@endforeach
						</table>
					</li>
				</ul>
			</td>			
		</tr>
	@endforeach
</table>
@if(isset($fingerprints))
<h4>指紋記錄</h4>
	<table class="table table-hover table-bordered">
		<tr>
			<!-- <td>Hash 值</td> -->
			<td>IP</td>
			<td>記錄時間</td>
		</tr>
		@foreach($fingerprints as $f)
			<tr>
				{{-- <td><a href="{{ route("showFingerprint", $f->fp) }}" target="_blank">{{ $f->fp }}</a></td> --}}
				<td>{{ $f->ip }}</td>
				<td>{{ $f->created_at }}</td>
			</tr>
		@endforeach
	</table>
@endif
<h4>所有訊息</h4>
<table class="table table-hover table-bordered">
<form action="{{ route('users/message/modify') }}" method="post">
    {!! csrf_field() !!}
	<input type="hidden" name="delete" id="delete" value="1">
	<tr>
		<td>發送給</td>
		<td>內容</td>
		<td>發送時間</td>
		<td>回覆收訊者</td>
		<td>封鎖收訊者</td>
        <td style="text-align: center; vertical-align: middle"><button type="submit" class="btn btn-danger delete-btn">刪除選取</button></td>
	</tr>
	@forelse ($userMessage as $key => $message)
		@if(isset($to_ids[$message->to_id]['engroup'] ))
		<tr>
			<td>
				<a href="{{ route('admin/showMessagesBetween', [$user->id, $message->to_id]) }}" target="_blank">
					<p @if($to_ids[$message->to_id]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>
						{{ $to_ids[$message->to_id]['name'] }}
						@if($to_ids[$message->to_id]['vip'])
						    @if($to_ids[$message->to_id]['vip']=='diamond_black')
						        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
						    @else
						        @for($z = 0; $z < $to_ids[$message->to_id]['vip']; $z++)
						            <img src="/img/diamond.png" style="height: 16px;width: 16px;">
						        @endfor
						    @endif
						@endif
						@for($i = 0; $i < $to_ids[$message->to_id]['tipcount']; $i++)
						    👍
						@endfor
						@if(!is_null($to_ids[$message->to_id]['isBlocked']))
						    @if(!is_null($to_ids[$message->to_id]['isBlocked']['expire_date']))
						        ({{ round((strtotime($to_ids[$message->to_id]['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天)
						    @else
						        (永久)
						    @endif
						@endif
					</p>
				</a>
			</td>
			<td>{{ $message->content }}</td>
			<td>{{ $message->created_at }}</td>
			<td>
				<a href="{{ route('AdminMessengerWithMessageId', [$message->to_id, $message->id]) }}" target="_blank" class='btn btn-dark'>撰寫</a>
			</td>
			<td>
				<a class="btn btn-danger ban-user{{ $key }}" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ route('banUserWithDayAndMessage', [$message->to_id, $message->id]) }}" data-name="{{ $to_ids[$message->to_id]['name']}}">封鎖</a>
			</td>
            <td style="text-align: center; vertical-align: middle">
                <input type="checkbox" name="msg_id[]" value="{{ $message->id }}" class="form-control">
            </td>
		</tr>
		@else
			<tr>
				<td colspan="6">
					會員資料已刪除
				</td>
			</tr>
		@endif
    @empty
        沒有訊息
    @endforelse
</form>
</table>
{!! $userMessage->links() !!}
<h4>現有生活照</h4>
<?php $pics = \App\Models\MemberPic::getSelf($user->id); ?>
<table class="table table-hover table-bordered" style="width: 50%;">
	@forelse ($pics as $pic)
		<tr>
			<td>
				<input type="hidden" name="userId" value="{{$user->id}}">
				<input type="hidden" name="imgId" value="{{$pic->id}}">
				<div style="width:400px">
					<img src="{{$pic->pic}}" />
				</div>
			</td>
		</tr>
	@empty
		此會員目前沒有生活照
	@endforelse
</table>
<h4>現有證件照</h4>
<?php $pics = \App\Models\MemberPic::getSelfIDPhoto($user->id); ?>
<table class="table table-hover table-bordered" style="width: 50%;">
	@forelse ($pics as $pic)
		<tr>
			<td>
				<input type="hidden" name="userId" value="{{$user->id}}">
				<input type="hidden" name="imgId" value="{{$pic->id}}">
				<div style="width:400px">
					<img src="{{$pic->pic}}" />
				</div>
			</td>
		</tr>
	@empty
		此會員目前沒有證件照
	@endforelse
</table>
</body>
<div class="modal fade" id="blockade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">封鎖</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/users/toggleUserBlock" method="POST" id="clickToggleUserBlock">
				{!! csrf_field() !!}
				<input type="hidden" value="" name="user_id" id="blockUserID">
				<input type="hidden" value="advInfo" name="page">
                <div class="modal-body">
                        封鎖時間
                        <select name="days" class="days">
                            <option value="3">三天</option>
                            <option value="7">七天</option>
                            <option value="15">十五天</option>
                            <option value="30">三十天</option>
                            <option value="X" selected>永久</option>
                        </select>
                        <hr>
                        封鎖原因
                        @foreach($banReason as $a)
                            <a class="text-white btn btn-success banReason">{{ $a->content }}</a>
                        @endforeach
                        <br><br>
                        <textarea class="form-control m-reason" name="reason" id="msg" rows="4" maxlength="200">廣告</textarea>
                        <label style="margin:10px 0px;">
                            <input type="checkbox" name="addreason" style="vertical-align:middle;width:20px;height:20px;"/>
                            <sapn style="vertical-align:middle;">加入常用封鎖原因</sapn>
                        </label>
                        <hr>
                        新增自動封鎖關鍵字(永久封鎖)
                        <input placeholder="1.請輸入封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='1.請輸入封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                        <input placeholder="2.請輸入封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='2.請輸入封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                        <input placeholder="3.請輸入封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='3.請輸入封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                </div>
                <div class="modal-footer">
                	<button type="submit" class='btn btn-outline-success ban-user'> 送出 </button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="warned_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="warnedModalLabel">站方警示</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/users/toggleUserWarned" method="POST" id="clickToggleUserWarned">
				{!! csrf_field() !!}
				<input type="hidden" value="" name="user_id" id="warnedUserID">
				<input type="hidden" value="advInfo" name="page">
				<div class="modal-body">
					 警示時間
					<select name="days" class="days">
						<option value="3">三天</option>
						<option value="7">七天</option>
						<option value="15">十五天</option>
						<option value="30">三十天</option>
						<option value="X" selected>永久</option>
					</select>
                   <hr>
					警示原因
					@foreach($warned_banReason as $a)
						<a class="text-white btn btn-success banReason">{{ $a->content }}</a>
					@endforeach
					<textarea class="form-control m-reason" name="reason" id="msg" rows="4" maxlength="200">廣告</textarea>
					<label style="margin:10px 0px;">
						<input type="checkbox" name="addreason" style="vertical-align:middle;width:20px;height:20px;"/>
						<sapn style="vertical-align:middle;">加入常用原因</sapn>
					</label>
					<hr>
					新增自動封鎖關鍵字(警示)
					<input placeholder="1.請輸入警示關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='1.請輸入警示關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
					<input placeholder="2.請輸入警示關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='2.請輸入警示關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
					<input placeholder="3.請輸入警示關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='3.請輸入警示關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
				</div>
				<div class="modal-footer">
					<button type="submit" class='btn btn-outline-success ban-user'> 送出 </button>
					<button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="implicitly_blockade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="implicitly_blockade">隱性封鎖</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('banningUserImplicitly') }}" method="POST">
            	{!! csrf_field() !!}
				<input type="hidden" value="{{ $user['id'] }}" name="user_id">
            	<input type="hidden" value="BannedInUserInfo" name="fp">
            	<input type="hidden" value="{{ url()->full() }}" name="page">
                <div class="modal-body">
                        隱性封鎖原因
                        @foreach($implicitly_banReason as $a)
                            <a class="text-white btn btn-success banReason">{{ $a->content }}</a>
                        @endforeach
                        <br><br>
                        <textarea class="form-control m-reason" name="reason" id="msg" rows="4" maxlength="200">廣告</textarea>
                        <label style="margin:10px 0px;">
                            <input type="checkbox" name="addreason" style="vertical-align:middle;width:20px;height:20px;"/>
                            <sapn style="vertical-align:middle;">加入常用隱性封鎖原因</sapn>
                        </label>
                        <hr>
                        新增自動封鎖關鍵字(隱性封鎖)
                        <input placeholder="1.請輸入隱性封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='1.請輸入隱性封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                        <input placeholder="2.請輸入隱性封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='2.請輸入隱性封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                        <input placeholder="3.請輸入隱性封鎖關鍵字" onfocus="this.placeholder=''" onblur="this.placeholder='3.請輸入隱性封鎖關鍵字'" class="form-control" type="text" name="addautoban[]" rows="1">
                </div>
                <div class="modal-footer">
                	<button type="submit" class='btn btn-outline-success ban-user'> 送出 </button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div>
	@if (Auth::user()->can('admin') || Auth::user()->can('juniorAdmin'))
		<form action="/admin/users/VIPToggler" method="POST" id="clickVipAction">
			{{ csrf_field() }}
			<input type="hidden" value="" name="user_id" id="vipID">
			<input type="hidden" value="" name="isVip" id="isVip">
			<input type="hidden" value="advInfo" name="page">
		</form>
	@elseif (Auth::user()->can('readonly'))
		<form action="/users/VIPToggler/readOnly" method="POST" id="clickVipAction">
			{{ csrf_field() }}
			<input type="hidden" value="" name="user_id" id="vipID">
			<input type="hidden" value="" name="isVip" id="isVip">
			<input type="hidden" value="back" name="page">
		</form>
	@endif
</div>
<div>
	<form action="/admin/users/RecommendedToggler" method="POST" id="toggleRecommendedUser">
		{{ csrf_field() }}
		<input type="hidden" value="" name="user_id" id="RecommendedUserID">
		<input type="hidden" value="" name="Recommended" id="Recommended">
		<input type="hidden" value="advInfo" name="page">
	</form>
</div>
<script src="/js/vendors.bundle.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function(){
    $('.delete-btn').on('click',function(e){
        if(!confirm('確定要刪除選取的訊息?')){
            e.preventDefault();
        }
	});

	$('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
		if (typeof $(this).data('id') !== 'undefined') {
			$("#exampleModalLabel").html('封鎖 '+ $(this).data('name'))
			$("#blockUserID").val($(this).data('id'))
		}
	});
	$('#warned_user').click(function(){
		if (typeof $(this).data('id') !== 'undefined') {
			$("#warnedModalLabel").html('站方警示 '+ $(this).data('name'))
			$("#warnedUserID").val($(this).data('id'))
		}
	});

	// $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
	// 	var data_id = '';
	// 	if (typeof $(this).data('id') !== 'undefined') {
	// 		data_id = $(this).data('id');
	// 		$("#exampleModalLabel").html('封鎖 '+ $(this).data('name'))
	// 	}
	// 	$("#send_blockade").attr('href', data_id);
	// });

	$(".banReason").each( function(){
	    $(this).bind("click" , function(){
	        var id = $("a").index(this);
	        var clickval = $("a").eq(id).text();
	        $('.m-reason').val(clickval);
	    });
	});

	$('.advertising').on('click', function(e) {
		$('.m-reason').val('廣告');
	});
	$('.improper-behavior').on('click', function(e) {
		$('.m-reason').val('非徵求包養行為');
	});
	$('.improper-words').on('click', function(e) {
		$('.m-reason').val('用詞不當');
	});
	$('.improper-photo').on('click', function(e) {
		$('.m-reason').val('照片不當');
	});
	$('#table_userLogin_log .hidden').hide();
	$('#table_userLogin_log td').click(function(){
		if($(this).find('.hidden').is(":visible")){
			$(this).find('.hidden').hide();
		}else{
			$(this).find('.hidden').show()
		}
	});
});
function Release(id) {
	$("#blockUserID").val(id);
}

function ReleaseWarnedUser(id) {
	$("#warnedUserID").val(id);
}

function VipAction(isVip, user_id){
	$("#isVip").val(isVip);
	$("#vipID").val(user_id);
	$("#clickVipAction").submit();
}
function RecommendedToggler(user_id,Recommended){
	$("#RecommendedUserID").val(user_id);
	$("#Recommended").val(Recommended);
	$("#toggleRecommendedUser").submit();
}

function WarnedToggler(user_id,isWarned){
	$.ajax({
		type: 'POST',
		url: "/admin/users/isWarned_user",
		data:{
			_token: '{{csrf_token()}}',
			id: user_id,
			status: isWarned,
		},
		dataType:"json",
		success: function(res){
			// alert('解除封鎖成功');
			location.reload();
		}});
}

function setDays(button){
    
    let reason = $(".m-reason").val();
    let days = $(".days").val();
    button.attr('href', button.attr('href') + '/' + days + '&' + reason);
    // if open href in a new windows and continue ban user by message
    // need reset the href from data-id
    window.location.href = button.attr('href');
}
function changeFormContent(form_id , key) {
    let href = $(".ban-user" + key).data('id');
    $("#" + form_id + " button[type='submit']").attr({
        'type': 'button',
        'href': href,
        'onClick' : 'setDays($(this))'
    });    
}

$("#unblock_user").click(function(){
	var data = $(this).data();
	if(confirm('確定解除封鎖此會員?')){
		$.ajax({
			type: 'POST',
			url: "/admin/users/unblock_user",
			data:{
				_token: '{{csrf_token()}}',
				data: data,
			},
			dataType:"json",
			success: function(res){
				alert('解除封鎖成功');
				location.reload();
			}});
	}
	else{
		return false;
	}
});

$("#unwarned_user").click(function(){
	var data = $(this).data();
	if(confirm('確定解除此會員站方警示?')){
		$.ajax({
			type: 'POST',
			url: "/admin/users/unwarned_user",
			data:{
				_token: '{{csrf_token()}}',
				data: data,
			},
			dataType:"json",
			success: function(res){
				alert('已解除站方警示');
				location.reload();
			}});
	}
	else{
		return false;
	}
});

$( "#exchange_period" ).change(function() {

	$('#form_exchange_period').submit();
	{{--$.ajax({--}}
	{{--	type: 'POST',--}}
	{{--	url: "/admin/users/changeExchangePeriod",--}}
	{{--	data:{--}}
	{{--		_token: '{{csrf_token()}}',--}}
	{{--		user_id: '{{$user->id}}',--}}
	{{--		exchange_period: $("#exchange_period").val(),--}}
	{{--	},--}}
	{{--	dataType:"json",--}}
	{{--	success: function(res){--}}
	{{--		location.reload();--}}
	{{--}});--}}

});


</script>
</html>