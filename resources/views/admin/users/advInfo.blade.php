@include('partials.header')
@include('partials.message')
<style>
	.hiddenRow {
		padding: 0 !important;
	}
	.popover{
		max-width: 600px; /* Max Width of the popover (depending on the container!) */
	}
    .gray {color:#C0C0C0;}
    #adv_auth_block_user.btn-secondary,#adv_auth_warned_user.btn-secondary {
        cursor: default;
        color: #fff;
        /*
        background-color: #6c757d;
        border-color: #6c757d;
        */
        background-color: #C0C0C0;
        border-color: #C0C0C0;   
        opacity: .65;        
        
    }    

    .loading {
        height: 180px;
        background-image: url({{asset('/new/images/loading.svg')}});
        background-repeat: no-repeat;
        background-size: 180px 180px;
        background-position: center;
        text-align: center;
    }
    
    .loading_text {
        position: relative;
        font-size: 8px;
        font-weight: bold;
        top: 80px;
        color: #f14a6c;
    } 

    #blockade .form-group {clear:both;}
    #autoban_pic_gather .autoban_pic_unit {float:left;margin:10px;}
    #autoban_pic_gather .autoban_pic_unit img {width:80px;min-width:80px;}
    #autoban_pic_gather input {display:none;}
    #autoban_pic_gather .autoban_pic_unit label {padding:0 10px 10px 10px;} 
    #autoban_pic_gather .autoban_pic_unit label span {display:block;text-align:center;font-size:4px;}
    #autoban_pic_gather .autoban_pic_unit input:checked+label {background:#1E90FF;}
</style>

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
	@if($user->accountStatus == 0 && !is_null($user->accountStatus_updateTime))
		{{ '關閉('. date('Ymd',strtotime($user->accountStatus_updateTime)).')' }}
	@endif
{{--	的所有資料--}}
	<form method="POST" action="/admin/users/accountStatus_admin" style="margin:0px;display:inline;">
		{!! csrf_field() !!}
		<input type="hidden" name='uid' value="{{ $user->id }}">
		<input type="hidden" name='account_status' value="{{ $user->account_status_admin == 0 ? 1 : 0 }}">
		@if($user->account_status_admin == 1)
			<button type="submit" class="btn btn-danger"> 站方關閉會員帳號 </button>
		@else
			<button type="submit" class="btn btn-success"> 站方開啟會員帳號 </button>
		@endif
	</form>

	<form method="POST" action="/admin/users/accountStatus_user" style="margin:0px;display:inline;">
		{!! csrf_field() !!}
		<input type="hidden" name='uid' value="{{ $user->id }}">
		<input type="hidden" name='account_status' value="{{ $user->accountStatus == 0 ? 1 : 0 }}">
		@if($user->accountStatus == 1)
			<button type="submit" class="btn btn-danger">解除使用者關閉帳號</button>
		@else
			<button type="submit" class="btn btn-success">開啟使用者關閉帳號</button>
		@endif
	</form>

	<a href="edit/{{ $user->id }}" class='text-white btn btn-primary'>修改</a>
	@if($user['isBlocked'])
		<button type="button" id="unblock_user" class='text-white btn @if($user["isBlocked"]) btn-success @else btn-danger @endif' onclick="Release({{ $user['id'] }})" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}"> 解除封鎖 </button>
	@else 
		<a class="btn btn-danger ban-user block_vip_pass" id="block_user" href="#" data-toggle="modal" data-target="#blockade" data-vip_pass="0" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}">封鎖會員</a>
		<a class="btn btn-danger ban-user" id="implicitly_block_user" href="#" data-toggle="modal" data-target="#implicitly_blockade" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}">隱性封鎖</a>
		<a class="btn btn-danger ban-user block_vip_pass" id="block_user" href="#" data-toggle="modal" data-target="#blockade" data-vip_pass="1" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}" @if($user['isvip']==1 && $user['isfreevip']==0)style="display: none;"@endif>付費封鎖</a>
        @if($user->engroup == 2)
        <a class="btn  @if($user->advance_auth_status==1) btn-secondary @else btn-danger @endif ban-user  block_advance_auth" id="adv_auth_block_user" href="#"  @if($user->advance_auth_status==1) onclick="return false;" @else data-toggle="modal"  data-target="#blockade"  data-vip_pass="0"  data-adv_auth="1"  data-name="{{ $user['name']}}" data-id="{{ $user['id'] }}"  @endif  <?php //echo $user->advance_auth_status==1?'disabled':'';//echo $banned_advance_auth_status==1?'disabled':'';?>> 驗證封鎖 </a>
        @endif
    @endif
	@if($user['isAdminWarned']==1)
		<button type="button" title="{{'於'.$user['adminWarned_createdAt'].'被警示，將於'.(isset($user['adminWarned_expireDate'])? $user['adminWarned_expireDate'] : '永久').'解除站方警示' }}" id="unwarned_user" class='text-white btn @if($user["isAdminWarned"]) btn-success @else btn-danger @endif' onclick="ReleaseWarnedUser({{ $user['id'] }})" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}"> 解除站方警示 </button>
	@else
		<a class="btn btn-danger warned-user warned_vip_pass" title="站方警示與自動封鎖的警示，只能經後台解除" id="warned_user" href="#" data-toggle="modal" data-target="#warned_modal" data-vip_pass="0" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}">站方警示</a>
		<a class="btn btn-danger warned-user warned_vip_pass" title="站方警示與自動封鎖的警示，只能經後台解除" id="warned_user" href="#" data-toggle="modal" data-target="#warned_modal" data-vip_pass="1" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}" @if($user['isvip']==1 && $user['isfreevip']==0)style="display: none;"@endif>付費警示</a>
        @if($user->engroup == 2)
        <a class="btn @if($user['advance_auth_status']==1 ) btn-secondary @else btn-danger @endif warned-user warned_adv_auth" title="站方警示與自動封鎖的警示，只能經後台解除" id="adv_auth_warned_user" href="#" @if($user['advance_auth_status']==1 ) onclick="return false;"  @else data-toggle="modal" data-target="#warned_modal" data-vip_pass="0" data-vip_pass="0" data-adv_auth="1"  data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}" @endif >驗證警示</a>
        @endif
    @endif
	@if($userMeta->isWarned==0)
		<button class="btn btn-info isWarned-user" title="自動計算檢舉分數達10分者警示，可經手機驗證解除警示(被檢舉總分)" onclick="@if($user->engroup == 2) return false; @endif WarnedToggler({{$user['id']}},1);"
            @if($user->engroup == 2) data-toggle="modal" data-target="#isWarned_blockade" data-id="{{ $user['id'] }}" data-name="{{ $user['name']}}" @endif
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
<!--隱藏-->
	{{-- @if($user['isHidden'])
		<button class="btn btn-info" onclick="HiddenAction({{($user['isHidden'])?'1':'0' }},{{ $user['id'] }})"> 取消隱藏 </button>
	@else 
		<button class="btn btn-info" onclick="HiddenAction({{($user['isHidden'])?'1':'0' }},{{ $user['id'] }})"> 升級隱藏 </button>
	@endif --}}
<!---->
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
	<form method="POST" action="{{ route('isRealToggler') }}" style="margin:0px;display:inline;">
		{!! csrf_field() !!}
		<input type="hidden" name='user_id' value="{{ $user->id }}">
		<input type="hidden" name='is_real' value="{{ $user->is_real }}">
		<button type="submit" class="btn {{ $user->is_real? 'btn-warning' : 'btn-danger' }}">{{ $user->is_real ? '是本人' : '非本人' }}</button>
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

	@if(isset($posts_forum))
		@if($posts_forum->status==1)
			<botton onclick="forum_toggle({{$user->id}}, 0)" class="btn btn-success">討論區啟用中</botton>
		@elseif($posts_forum->status==0)
			<botton onclick="forum_toggle({{$user->id}}, 1)" class="btn btn-danger">討論區關閉中</botton>
		@endif
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
        //VIP起始時間,現狀,付費方式,種類
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
				$upgradeKind = '持續季繳';
			else if ($vipInfo->payment == 'cc_monthly_payment')
				$upgradeKind = '持續月繳';
			else if ($vipInfo->payment == 'one_quarter_payment')
				$upgradeKind = '季繳一季';
			else if ($vipInfo->payment == 'one_month_payment')
				$upgradeKind = '月繳一月';

			$vipLog = \App\Models\VipLog::where("member_id", $user->id)->orderBy('id', 'desc')->first();
			//現狀:只有持續中跟未持續兩種。已取消扣款或者一次付清都是未持續。
			if(in_array($vipInfo->payment, ['one_quarter_payment','one_month_payment']) || $vipInfo->active ==0)
				$nowStatus = '未持續';
			else if(str_contains($vipLog, 'cancel') || (str_contains($vipLog, 'Cancel') && !str_contains($vipLog, 'bypass')))
				$nowStatus = '未持續';
			else
				$nowStatus = '持續中';

			//VIP起始時間,現狀,付費方式,種類
			if(is_null($vipInfo->payment_method) && is_null($vipInfo->payment)){
			    $upgradeWay='手動升級';
			    $upgradeKind='手動升級';
			}
			if($vipInfo->free==1){
			    $upgradeWay='免費';
			    $upgradeKind='免費';
			}
			$getUserInfo=\App\Models\User::findById($user->id);//->isVip? '是':'否';
			$isVipStatus=$getUserInfo->isVip() ? '是':'否';
			$showVipInfo =  $upgradeDay .' / '. $isVipStatus .' / '. $upgradeWay .' / '. $upgradeKind;

			/*
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
			*/

        }else{
            $nowStatus = '';
            //還沒有成為過vip
            $showVipInfo =  '未曾加入 / 否 / 無 / 無';
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
{{--		@if($nowStatus =='未持續')<th>VIP起始時間,總繳費月數,現狀</th> @else <th>VIP起始時間,付費方式,種類,現狀</th> @endif--}}
		<th>VIP起始時間 / 現狀 / 付費方式 / 種類</th>
		@if(!is_null($warnedInfo))<th>警示時間</th>@endif
		<th>上次登入</th>
		<th>上站次數</th>
	</tr>
	<tr>
		<td>{{ $user->id }}</td>
		<td>{{ $user->name }}</td>
		<td>{{ $user->title }}</td>
		<td>@if($user->engroup==1) 男 @else 女 @endif</td>
		<td>
{{--			<a href="/admin/stats/vip_log/{{ $user->id }}" target="_blank">--}}
				{{ $user->email }}
{{--			</a>--}}
		</td>
		<td>{{ $user->created_at }}</td>
		<td>{{ $user->updated_at }}</td>
		<td><a href="{{ route('stats/vip_log', $user->id) }}" target="_blank">{{ $showVipInfo }}</a></td>
		@if(!is_null($warnedInfo))<td>{{ !is_null($warnedInfo) ? $warnedDay.'('.$diffDays.')' : ''}}</td>@endif
		<td>{{ $user->last_login }}</td>
		<td>{{ $user->login_times }}</td>
	</tr>
</table>
<h4>詳細資料</h4>
<table class='table table-hover table-bordered'>
	<tr>
		<th width="15%">照片 <br><a href="editPic_sendMsg/{{ $user->id }}" class='text-white btn btn-primary'>照片&發訊息</a></th>
		<td width="85%">
			<div style="display: inline-flex;">
			@if($userMeta->pic)
				<div style="width: 250px;">
					<img src="{{$userMeta->pic}}" style="width: 250px;height: 250px;object-fit: contain;">
					<span>照片原始檔名：{{$userMeta->pic_original_name}}</span>
				</div>
			@else
				無
			@endif

			<?php $pics = \App\Models\MemberPic::getSelf($user->id); ?>
			@forelse ($pics as $pic)
				<div  style="width: 250px; margin-left: 10px;">
					<input type="hidden" name="userId" value="{{$user->id}}">
					<input type="hidden" name="imgId" value="{{$pic->id}}">
					<img src="{{$pic->pic}}" style="width: 250px;height: 250px;object-fit: contain;">
					<span>照片原始檔名：{{$pic->original_name}}</span>
				</div>
			@empty
				此會員目前沒有生活照
			@endforelse
			</div>
		</td>
	</tr>
</table>
<table class='table table-hover table-bordered'>
	<tr>
		<form action="{{ route('users/save', $user->id) }}" method='POST'>
			{!! csrf_field() !!}
			<th>站長註解<div><button type="submit" class="text-white btn btn-primary">修改</button></div></th>
			<td><textarea class="form-control m-input" type="textarea" name="adminNote" rows="3" maxlength="300">{{ $userMeta->adminNote }}</textarea></td>
		</form>

		<th>手機驗證
			<div style="display: flex;">
				<form action="{{ route('phoneDelete') }}" method='POST'>
					{!! csrf_field() !!}
					<input type="hidden" name="user_id" value="{{ $userMeta->user_id }}">
					<button type="submit" class="text-white btn btn-danger delete_phone_submit" style="float: right;">刪除</button>
				</form>
				@if ($user->isPhoneAuth() == false)
					<form action="{{ route('phoneModify') }}" method='POST'>
						{!! csrf_field() !!}
						<input type="hidden" name="user_id" value="{{ $userMeta->user_id }}">
						<input type="hidden" name="phone" value="">
						<input type="hidden" name="pass" value="1">
						<button type="submit" class="text-white btn btn btn-success" style="float: right;">通過</button>
					</form>
				@else
					<form action="{{ route('phoneDelete') }}" method='POST'>
						{!! csrf_field() !!}
						<input type="hidden" name="user_id" value="{{ $userMeta->user_id }}">
						<button type="submit" class="text-white btn btn btn-success" style="float: right;">不通過</button>
					</form>
				@endif
			</div>
		</th>
		<td>
			<form action="{{ route('phoneModify') }}" method='POST'>
				{!! csrf_field() !!}
				<input type="hidden" name="user_id" value="{{ $userMeta->user_id }}">
				@php
					$showPhone = '暫無手機';
					$showPhoneDate = '';
					$phoneAuth = DB::table('short_message')->where('member_id', $user->id)->where('active',1)->first();
					if($user->isPhoneAuth()){
					    if(empty(trim($phoneAuth->mobile))){
							$showPhone = '已驗證,尚未填寫手機';
					        $showPhoneDate = $phoneAuth->createdate;
					        }
						else{
						    $showPhone = $phoneAuth->mobile;
						    $showPhoneDate = $phoneAuth->createdate;
						    }
					}
				@endphp
				<input class="form-control m-input phoneInput" type=text name="phone" value="{{ $showPhone }}" readonly="readonly" autocomplete="off">
				<div id="phoneKeyInAlert"></div>
				<div>@if($userMeta->isWarnedTime !='')警示用戶時間：{{ $userMeta->isWarnedTime }}@endif</div>
				<div>@if($showPhoneDate != '')手機驗證時間：{{ $showPhoneDate }}@endif</div>
				@if(!is_null($phoneAuth))
					<div>購買手機驗證卡號：{{ $phoneAuth->credit_card }}</div>
				@endif
				@if ($user->isPhoneAuth())
					<div class="text-white btn btn-primary test" onclick="showPhoneInput()">修改</div>
					<button type="submit" class="text-white btn btn-primary modify_phone_submit" style="display: none;">確認修改</button>
				@endif
			</form>
		</td>
	</tr>
	<tr>
		<th>會員ID</th>
		<td>{{ $userMeta->user_id }}</td>
		<th>手機</th>
		<td>{{ $userMeta->phone }}</td>
		<th>是否已啟動</th>
		<td>@if($userMeta->is_active == 1) 是 @else 否 @endif</td>
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
	</tr>
	<tr>
		<th>現況</th>
		<td>{{ $userMeta->situation }}</td>
		<th>關於我</th>
		<td>{{ $userMeta->about }}</td>
		<th>期待的約會模式</th>
		<td>{{ $userMeta->style }}</td>
	</tr>
</table>

@php
	//曾被警示
	$isEverWarned_log=array();
    if(isset($isEverWarned) && count($isEverWarned)>0){
        foreach($isEverWarned as $key =>$row){
            $isEverWarned_log[$key]['created_at']=$row->created_at;
            $isEverWarned_log[$key]['reason']=$row->reason;
            $isEverWarned_log[$key]['vip_pass']=$row->vip_pass;
            $isEverWarned_log[$key]['adv_auth']=$row->adv_auth;
            $isEverWarned_cancel=\App\Models\AdminActionLog::where('target_id', $user->id)->where('act','解除站方警示')->orderByDesc('created_at')->skip($key)->first();
			$isEverWarned_log[$key]['cancal_admin']=$isEverWarned_cancel? \App\Models\User::findById($isEverWarned_cancel->operator):'';
            $isEverWarned_log[$key]['cancal_time']=$isEverWarned_cancel?$isEverWarned_cancel->created_at:'';
        }
    }
    //曾被封鎖
    $isEverBanned_log=array();
    if(isset($isEverBanned) && count($isEverBanned)>0){
        foreach($isEverBanned as $key =>$row){
            $isEverBanned_log[$key]['created_at']=$row->created_at;
            $isEverBanned_log[$key]['reason']=$row->reason;
            $isEverBanned_log[$key]['expire_date']=$row->expire_date;
            $isEverBanned_log[$key]['vip_pass']=$row->vip_pass;
            $isEverBanned_log[$key]['adv_auth']=$row->adv_auth;
		    $isEverBanned_cancel=\App\Models\AdminActionLog::where('target_id', $user->id)->where('act','解除封鎖')->orderByDesc('created_at')->skip($key)->first();
			$isEverBanned_log[$key]['cancal_admin']=$isEverBanned_cancel? \App\Models\User::findById($isEverBanned_cancel->operator) :'';
            $isEverBanned_log[$key]['cancal_time']=$isEverBanned_cancel? $isEverBanned_cancel->created_at:'';
        }
    }
    //目前正被警示
    $isWarned_show=array();
    if(isset($isWarned) && count($isWarned)>0){
         foreach($isWarned as $row){
             $isWarned_show['created_at']=$row->created_at;
             $isWarned_show['reason']=$row->reason;
             $isWarned_show['expire_date']=$row->expire_date;
             $isWarned_show['vip_pass']=$row->vip_pass;
             $isWarned_show['adv_auth']=$row->adv_auth;
         }
		$isWarned_show['cancal_admin']='';
		$isWarned_show['cancal_time']='尚未解除';
    }else{
		$isWarned_cancel=\App\Models\AdminActionLog::where('target_id', $user->id)->where('act','解除站方警示')->orderByDesc('created_at')->first();
        $isWarned_show['cancal_admin']=$isWarned_cancel? \App\Models\User::findById($isWarned_cancel->operator) : '';
		$isWarned_show['cancal_time']=$isWarned_cancel? $isWarned_cancel->created_at : '';
    }
    //目前正被封鎖
    $isBanned_show=array();
    if(isset($isBanned) && count($isBanned)>0){
         foreach($isBanned as $row){
             $isBanned_show['created_at']=$row->created_at;
             $isBanned_show['reason']=$row->reason;
             $isBanned_show['expire_date']=$row->expire_date;
             $isBanned_show['vip_pass']=$row->vip_pass;
             $isBanned_show['adv_auth']=$row->adv_auth;
         }
         $isBanned_show['cancal_admin']='';
		 $isBanned_show['cancal_time']='尚未解除';
    }else{
		 $isBanned_cancel=\App\Models\AdminActionLog::where('target_id', $user->id)->where('act','解除封鎖')->orderByDesc('created_at')->first();
         $isBanned_show['cancal_admin']=$isBanned_cancel? \App\Models\User::findById($isBanned_cancel->operator) : '';
		 $isBanned_show['cancal_time']=$isBanned_cancel? $isBanned_cancel->created_at : '';
    }

@endphp
<br>
@if($isEverWarned_log || $isEverBanned_log || $isWarned_show || $isBanned_show)
	<h4>封鎖與警示紀錄</h4>
	<table class="table table-hover table-bordered" style="width: 60%;">
		<tr>
			<th width="20%"></th>
			@if(count($isBanned_show)>0 || count($isEverBanned_log)>0)
				<th width="20%">是否封鎖</th>
			@endif
			@if(count($isWarned_show)>0 || count($isEverWarned_log)>0 || $userMeta->isWarned==1)
				<th width="20%">是否警示</th>
			@endif
			@if(count($isEverBanned_log)>0)
				@if(!is_null(array_get($isEverBanned_log,'0')))
					<th id="showMore_banned" width="20%" @if(count($isEverBanned)>1) title="封鎖紀錄" @endif>過往封鎖紀錄</th>
				@endif
			@endif
			@if(count($isEverWarned_log)>0)
				@if(!is_null(array_get($isEverWarned_log,'0')))
					<th id="showMore_warned" width="20%" @if(count($isEverWarned)>1) title="警示紀錄" @endif>過往警示紀錄</th>
				@endif
			@endif
		</tr>
		<tr>
			<th >時間</th>
			@if(count($isBanned_show)>0 || count($isEverBanned_log)>0)
				<td>{{ array_get($isBanned_show,'created_at') }}</td>
			@endif
			@if($userMeta->isWarned==1)
				<td>{{ $userMeta->isWarnedTime }}</td>
			@elseif(count($isWarned_show)>0 || count($isEverWarned_log)>0)
				<td>{{ array_get($isWarned_show,'created_at') }}</td>
			@endif
			@if(count($isEverBanned_log)>0)
				<td>{{ array_get($isEverBanned_log,'0.created_at') }}</td>
			@endif
			@if(count($isEverWarned_log)>0)
				@if(!is_null(array_get($isEverWarned_log,'0')))
					<td>{{ array_get($isEverWarned_log,'0.created_at') }}</td>
				@endif
			@endif
		</tr>
		<tr>
			<th>後台解除封鎖時間</th>
			<td>@if(count($isBanned)>0){{ array_get($isBanned_show,'cancal_time') }}@endif</td>
			<td>@if(count($isWarned)>0){{ array_get($isWarned_show,'cancal_time') }}@endif</td>
			@if(count($isEverBanned_log)>0)
				<td>{{ array_get($isEverBanned_log,'0.cancal_time') }}</td>
			@endif
			@if(count($isEverWarned_log)>0)
				<td>{{ array_get($isEverWarned_log,'0.cancal_time') }}</td>
			@endif
		</tr>
		<tr>
			<th>原因</th>
			@if(count($isBanned_show)>0 || count($isEverBanned_log)>0)
				<td>{{ array_get($isBanned_show,'reason') }}</td>
			@endif
			@if(isset($isWarned) && count($isWarned)>0)
				@if($userMeta->isWarned==1)
					<td>檢舉警示</td>
				@elseif(count($isWarned_show)>0 || count($isEverWarned_log)>0)
					<td>{{ array_get($isWarned_show,'reason') }}</td>
				@endif
			@else
				<td></td>
			@endif
			@if(count($isEverBanned_log)>0)
				<td>{{ array_get($isEverBanned_log,'0.reason') }}</td>
			@endif
			@if(count($isEverWarned_log)>0)
				@if(!is_null(array_get($isEverWarned_log,'0')))
					<td>{{ array_get($isEverWarned_log,'0.reason') }}</td>
				@endif
			@endif
		</tr>
		<tr>
			<th>到期日</th>
			@if(count($isBanned_show)>0 || count($isEverBanned_log)>0)
				<td>{{ !is_null(array_get($isBanned_show,'created_at')) && !is_null(array_get($isBanned_show,'expire_date')) ? array_get($isBanned_show,'expire_date') : (count($isBanned)>0 ? '永久' : '') }}</td>
			@endif
			@if(isset($isWarned) && count($isWarned)>0)
				@if($userMeta->isWarned==1)
					<td>永久</td>
				@elseif(count($isWarned_show)>0 || count($isEverWarned_log)>0)
					<td>{{ !is_null(array_get($isWarned_show,'created_at')) && !is_null(array_get($isWarned_show,'expire_date')) ? array_get($isWarned_show,'expire_date') : (count($isWarned)>0 ? '永久' : '') }}</td>
				@endif
			@else
				<td></td>
			@endif
			@if(count($isEverBanned_log)>0)
				@if(!is_null(array_get($isEverBanned_log,'0')))
					<td>{{ !empty(array_get($isEverBanned_log,'0.expire_date')) ? array_get($isEverBanned_log,'0.expire_date') : '永久' }}</td>
				@endif
			@endif
			@if(count($isEverWarned_log)>0)
				@if(!is_null(array_get($isEverWarned_log,'0')))
					<td>{{ !empty(array_get($isEverWarned_log,'0.expire_date')) ? array_get($isEverWarned_log,'0.expire_date') : '永久' }}</td>
				@endif
			@endif
		</tr>
		<tr>
			<th>付費封鎖/驗證封鎖</th>
			@if(count($isBanned_show)>0 || count($isEverBanned_log)>0)
				<td>
                    {{ array_get($isBanned_show,'vip_pass')==1  ? '付費封鎖' : '' }}
                    {{ array_get($isBanned_show,'adv_auth')==1  ? '驗證封鎖' : '' }}
                </td>
			@endif
			@if(count($isWarned_show)>0 || count($isEverWarned_log)>0)
				<td>
                    {{ array_get($isWarned_show,'vip_pass')==1  ? '付費警示' : '' }}
                    {{ array_get($isWarned_show,'adv_auth')==1  ? '驗證警示' : '' }}
                </td>
			@endif
			@if(count($isEverBanned_log)>0)
				@if(!is_null(array_get($isEverBanned_log,'0')))
					<td>
                        {{ array_get($isEverBanned_log,'0.vip_pass') == 1 ? '付費封鎖' : '' }}
                        {{ array_get($isEverBanned_log,'0.adv_auth') == 1 ? '驗證封鎖' : '' }}
                    </td>
				@endif
			@endif
			@if(count($isEverWarned_log)>0)
				@if(!is_null(array_get($isEverWarned_log,'0')))
					<td>
                        {{ array_get($isEverWarned_log,'0.vip_pass') == 1 ? '付費警示' : '' }}
                        {{ array_get($isEverWarned_log,'0.adv_auth') == 1 ? '驗證警示' : '' }}
                    </td>
				@endif
			@endif
		</tr>
		<tr>
			<th>後台解除封鎖人員</th>
			@php
				$isBanned_admin=array_get($isBanned_show,'cancal_admin');
				$isWarned_admin=array_get($isWarned_show,'cancal_admin');
				$isEverBanned0_admin=array_get($isEverBanned_log,'0.cancal_admin');
				$isEverWarned0_admin=array_get($isEverWarned_log,'0.cancal_admin');
			@endphp
			@if(count($isBanned)>0 && $isBanned_admin)
				<td><a href="{{ route('users/advInfo', $isBanned_admin->id) }}" target='_blank' @if($isBanned_admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>{{ $isBanned_admin->name }}</a></td>
			@else
				<td></td>
			@endif
			@if(count($isWarned)>0 &&$isWarned_admin)
				<td><a href="{{ route('users/advInfo', $isWarned_admin->id) }}" target='_blank' @if($isWarned_admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>{{ $isWarned_admin->name }}</a></td>
			@else
				<td></td>
			@endif
			@if($isEverBanned0_admin)
				<td><a href="{{ route('users/advInfo', $isEverBanned0_admin->id) }}" target='_blank' @if($isEverBanned0_admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>{{ $isEverBanned0_admin->name }}</a></td>
			@endif
			@if($isEverWarned0_admin)
				<td><a href="{{ route('users/advInfo', $isEverWarned0_admin->id) }}" target='_blank' @if($isEverWarned0_admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>{{ $isEverWarned0_admin->name }}</a></td>
			@endif
		</tr>
	</table>

	<div id="showMore_banned_log" class="mouseOverPop" style="width: 80%;display: none;">
		<table class="table table-hover table-bordered">
			<tr>
				<th>時間</th>
				@if(count($isEverBanned_log)>0)
					@if(!is_null(array_get($isEverBanned_log,'1')))
						<td>{{ array_get($isEverBanned_log,'1.created_at') }}</td>
					@endif
					@if(!is_null(array_get($isEverBanned_log,'2')))
						<td>{{ array_get($isEverBanned_log,'2.created_at') }}</td>
					@endif
				@endif
			</tr>
			<tr>
				<th>後台解除封鎖時間</th>
				@if(count($isEverBanned_log)>0)
					@if(!is_null(array_get($isEverBanned_log,'1')))
						<td>{{ array_get($isEverBanned_log,'1.cancal_time') }}</td>
					@endif
					@if(!is_null(array_get($isEverBanned_log,'2')))
						<td>{{ array_get($isEverBanned_log,'2.cancal_time') }}</td>
					@endif
				@endif
			</tr>
			<tr>
				<th>原因</th>
				@if(count($isEverBanned_log)>0)
					@if(!is_null(array_get($isEverBanned_log,'1')))
						<td>{{ array_get($isEverBanned_log,'1.reason') }}</td>
					@endif
					@if(!is_null(array_get($isEverBanned_log,'2')))
						<td>{{ array_get($isEverBanned_log,'2.reason') }}</td>
					@endif
				@endif
			</tr>
			<tr>
				<th>到期日</th>
				@if(count($isEverBanned_log)>0)
					@if(!is_null(array_get($isEverBanned_log,'1')))
						<td>{{ !empty(array_get($isEverBanned_log,'1.expire_date')) ? array_get($isEverBanned_log,'1.expire_date') : '永久' }}</td>
					@endif
					@if(!is_null(array_get($isEverBanned_log,'2')))
						<td>{{ !empty(array_get($isEverBanned_log,'2.expire_date')) ? array_get($isEverBanned_log,'2.expire_date') : '永久' }}</td>
					@endif
				@endif
			</tr>
			<tr>
				<th>付費/驗證</th>
				@if(count($isEverBanned_log)>0)
					@if(!is_null(array_get($isEverBanned_log,'1')))
						<td>
                            {{ (array_get($isEverBanned_log,'1.vip_pass')==1) ? '付費封鎖' : '' }}
                            {{ (array_get($isEverBanned_log,'1.adv_auth')==1) ? '驗證封鎖' : '' }}
                        </td>
					@endif
					@if(!is_null(array_get($isEverBanned_log,'2')))
						<td>
                            {{ (array_get($isEverBanned_log,'2.vip_pass')==1) ? '付費封鎖' : '' }}
                            {{ (array_get($isEverBanned_log,'2.adv_auth')==1) ? '驗證封鎖' : '' }}
                        </td>
					@endif
				@endif
			</tr>
			<tr>
				<th>後台解除封鎖人員</th>
				@php
                    $isEverBanned1_admin=array_get($isEverBanned_log,'1.cancal_admin');
                    $isEverBanned2_admin=array_get($isEverBanned_log,'2.cancal_admin');
				@endphp
				@if(count($isEverBanned_log)>0)
					@if(!is_null(array_get($isEverBanned_log,'1')))
						@if($isEverBanned1_admin)
							<td><a href="{{ route('users/advInfo', $isEverBanned1_admin->id) }}" target='_blank' @if($isEverBanned1_admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>{{ $isEverBanned1_admin->name }}</a></td>
						@else
							<td></td>
						@endif
					@endif
					@if(!is_null(array_get($isEverBanned_log,'2')))
						@if($isEverBanned2_admin)
							<td><a href="{{ route('users/advInfo', $isEverBanned2_admin->id) }}" target='_blank' @if($isEverBanned2_admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>{{ $isEverBanned2_admin->name }}</a></td>
						@else
							<td></td>
						@endif
					@endif
				@endif
			</tr>
		</table>
	</div>
	<div id="showMore_warned_log" style="width: 80%;display: none;">
		<table class="table table-hover table-bordered">
			<tr>
				<th>時間</th>
				@if(count($isEverWarned_log)>0)
					@if(!is_null(array_get($isEverWarned_log,'1')))
						<td>{{ array_get($isEverWarned_log,'1.created_at') }}</td>
					@endif
					@if(!is_null(array_get($isEverWarned_log,'2')))
						<td>{{ array_get($isEverWarned_log,'2.created_at') }}</td>
					@endif
				@endif
			</tr>
			<tr>
				<th>後台解除封鎖時間</th>
				@if(count($isEverWarned_log)>0)
					@if(!is_null(array_get($isEverWarned_log,'1')))
						<td>{{ array_get($isEverWarned_log,'1.cancal_time') }}</td>
					@endif
					@if(!is_null(array_get($isEverWarned_log,'2')))
						<td>{{ array_get($isEverWarned_log,'2.cancal_time') }}</td>
					@endif
				@endif
			</tr>
			<tr>
				<th>原因</th>
				@if(count($isEverWarned_log)>0)
					@if(!is_null(array_get($isEverWarned_log,'1')))
						<td>{{ array_get($isEverWarned_log,'1.reason') }}</td>
					@endif
					@if(!is_null(array_get($isEverWarned_log,'2')))
						<td>{{ array_get($isEverWarned_log,'2.reason') }}</td>
					@endif
				@endif
			</tr>
			<tr>
				<th>到期日</th>
				@if(count($isEverWarned_log)>0)
					@if(!is_null(array_get($isEverWarned_log,'1')))
						<td>{{ !empty(array_get($isEverWarned_log,'1.expire_date')) ? array_get($isEverWarned_log,'1.expire_date') : '永久' }}</td>
					@endif
					@if(!is_null(array_get($isEverWarned_log,'2')))
						<td>{{ !empty(array_get($isEverWarned_log,'2.expire_date')) ? array_get($isEverWarned_log,'2.expire_date') : '永久' }}</td>
					@endif
				@endif
			</tr>
			<tr>
				<th>付費/驗證</th>
				@if(count($isEverWarned_log)>0)
					@if(!is_null(array_get($isEverWarned_log,'1')))
						<td>
                            {{ (array_get($isEverWarned_log,'1.vip_pass')==1) ? '付費警示' : '' }}
                            {{ (array_get($isEverWarned_log,'1.adv_auth')==1) ? '驗證警示' : '' }}
                        </td>
					@endif
					@if(!is_null(array_get($isEverWarned_log,'2')))
						<td>
                            {{ (array_get($isEverWarned_log,'2.vip_pass')==1) ? '付費警示' : '' }}
                            {{ (array_get($isEverWarned_log,'2.adv_auth')==1) ? '驗證警示' : '' }}
                        </td>
					@endif
				@endif
			</tr>
			<tr>
				<th>後台解除封鎖人員</th>
				@php
					$isEverWarned1_admin=array_get($isEverWarned_log,'1.cancal_admin');
                    $isEverWarned2_admin=array_get($isEverWarned_log,'2.cancal_admin');
				@endphp
				@if(count($isEverWarned_log)>0)
					@if(!is_null(array_get($isEverWarned_log,'1')))
						@if($isEverWarned1_admin)
							<td><a href="{{ route('users/advInfo', $isEverWarned1_admin->id) }}" target='_blank' @if($isEverWarned1_admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>{{ $isEverWarned1_admin->name }}</a></td>
						@else
							<td></td>
						@endif
					@endif
					@if(!is_null(array_get($isEverWarned_log,'2')))
						@if($isEverWarned2_admin)
							<td><a href="{{ route('users/advInfo', $isEverWarned2_admin->id) }}" target='_blank' @if($isEverWarned2_admin->engroup == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>{{ $isEverWarned2_admin->name }}</a></td>
						@else
							<td></td>
						@endif
					@endif
				@endif
			</tr>
		</table>
	</div>

@endif

{{--@if($user->engroup==1)
<h4>PR值</h4>
<table class="table table-hover table-bordered">
	<tr>
		<th width="30%">PR值</th>
		<th>PR值歷程</th>
		<th>資料時間</th>
	</tr>
	<tr>
		<td>{{$pr}}</td>
		<td>{{$pr_log}}</td>
		<td>{{$pr_created_at}}</td>
	</tr>
</table>
@endif--}}

@if(count($reportBySelf)>0)
<br>
<h4>檢舉紀錄(最新一筆)</h4>
<table class="table table-hover table-bordered">
	<tr>
		<th width="14%">暱稱</th>
		<th width="15%">帳號</th>
		<th width="12%">檢舉時間</th>
		<th width="5%">VIP</th>
		<th width="6%">檢舉類型</th>
		<th width="6%">會員認證</th>
		<th width="20%">檢舉理由</th>
		<th width="22%">上傳照片</th>
	</tr>

	<?php 
		$repo_id = 0;
		$count = 0;
	?>
	@foreach($reportBySelf as $row)
		@if($row['reporter_id'] == $repo_id)
			<?php $count = $count + 1; ?>
			<?php $r_count[$repo_id] = $count; ?>
		@else
			<?php $repo_id = $row['reporter_id']; ?>
			<?php $count = 1; ?>
			<?php $r_count[$repo_id] = $count; ?>
		@endif
	@endforeach

	<?php 
		$r_id = 0;
	?>
	@foreach($reportBySelf as $row)
		
		@if($row['reporter_id'] == $r_id)
			<tr class="tr_hide tr_hide_{{$r_id}}">
				<td></td>
				<td></td>
				<td>{{ $row['created_at'] }}</td>
				<td></td>
				<td>{{ $row['report_type'] }}</td>
				<td></td>
				<td>{{ $row['content'] }}</td>
				<td class="evaluation_zoomIn">
					@if(isset($row['pic']))
						@foreach($row['pic'] as $reportedPic)
							<li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
								<img src="{{ $reportedPic }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
							</li>
						@endforeach
					@endif
				</td>
			</tr>
		@else
			<?php $r_id = $row['reporter_id']; ?>
			<tr>
				<td>
					<a href="{{ route('admin/showMessagesBetween', [$user->id, $row['reporter_id']]) }}" target="_blank">{{$row['name']}}</a>
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
				</td>
				<td>
					<a href="{{ route('users/advInfo', $row['reporter_id']) }}" target='_blank'>
						{{ $row['email'] }}
					</a>
					@if($r_count[$r_id] > 1)
						(
						<a class="tr_more" r_id="{{$r_id}}">
							{{ $r_count[$r_id] }}
						</a>
						)
					@endif
				</td>
				<td>{{ $row['created_at'] }}</td>
				<td>@if($row['isvip']==1) VIP @else 非VIP @endif</td>
				<td>{{ $row['report_type'] }}</td>
				<td>@if($row['auth_status']==1) 已認證 @else N/A @endif</td>
				<td>{{ $row['content'] }}</td>
				<td class="evaluation_zoomIn">
					@if(isset($row['pic']))
						@foreach($row['pic'] as $reportedPic)
							<li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
								<img src="{{ $reportedPic }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
							</li>
						@endforeach
					@endif
				</td>
			</tr>
		@endif
	@endforeach

</table>
@endif

@if(count($report_all)>0)
<h4>被檢舉紀錄</h4>
<table class="table table-hover table-bordered">
	<tr>
		<th width="14%">暱稱</th>
		<th width="15%">帳號</th>
		<th width="12%">檢舉時間</th>
		<th width="5%">VIP</th>
		<th width="6%">檢舉類型</th>
		<th width="6%">會員認證</th>
		<th width="20%">檢舉理由</th>
		<th width="22%">上傳照片</th>
	</tr>
	@foreach($report_all as $row)
		<tr>
			<td @if(!is_null($row['isBlocked'])) style="color: #F00;" @endif>
				<a href="{{ route('admin/showMessagesBetween', [$user->id, $row['reporter_id']]) }}" target="_blank">{{ $row['name'] }}</a>
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
			<td>{{ $row['created_at'] }}</td>
			<td>@if($row['isvip']==1) VIP @endif</td>
			<td>{{ $row['report_type'] }}</td>
			<td>@if($row['auth_status']==1) 已認證 @else N/A @endif</td>
			<td>{{ $row['content'] }}</td>
			<td class="evaluation_zoomIn">
				@if(isset($row['pic']))
					@foreach($row['pic'] as $reportedPic)
						<li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
							<img src="{{ $reportedPic }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
						</li>
					@endforeach
				@endif
			</td>
		</tr>
	@endforeach
</table>
@endif

@if(count($out_evaluation_data_2)>0)
<h4>被評價紀錄</h4>
<table class="table table-hover table-bordered">
	<tr>
		<th width="14%">暱稱</th>
		<th width="13%">帳號</th>
		<th width="12%">評價時間</th>
		<th width="4%">VIP</th>
		<th width="4%">會員認證</th>
		<th width="17%">評價內容</th>
		<th width="11%">上傳照片</th>
		<th width="25%">動作</th>
	</tr>
	@foreach($out_evaluation_data_2 as $row)
		<tr>
			<td><a href="{{ route('admin/showMessagesBetween', [$user->id, $row['to_id']]) }}" target="_blank">{{ $row['to_name'] }}</a></td>
			<td><a href="{{ route('users/advInfo', $row['to_id']) }}" target='_blank'>{{ $row['to_email'] }}</a></td>
			<td>{{ $row['created_at'] }}</td>
			<td>@if($row['to_isvip']==1) VIP @endif</td>
			<td>@if($row['to_auth_status']==1) 已認證 @else N/A @endif</td>
			@if($row['is_check']==1)
				<td style="color: red;">***此評價目前由站方審核中***@if(!is_null($row['is_delete'])) <br><span style="color: red;">(該評價已刪除)</span> @endif</td>
			@else
				<td>@if(!is_null($row['is_delete'])) <span style="color: red;">(該評價已刪除)</span><br>@endif {{ $row['content'] }}</td>
			@endif
			<td class="evaluation_zoomIn">
				@if(!is_null($row['is_delete'])) <span style="color: red;">(該評價已刪除)</span> @endif
				@foreach($row['evaluation_pic'] as $evaluationPic)
					<li>
						<img src="{{ $evaluationPic->pic }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
					</li>
				@endforeach
			</td>
			<td>
				<form method="POST" action="{{ route('evaluationModifyContent', $row['id']) }}" style="margin:0px;display:inline;">
					{!! csrf_field() !!}
					<input type="hidden" name="id" value="{{$row['id']}}">
					<textarea class="form-control m-input content_{{$row['id']}}" type="textarea" name="evaluation_content" rows="3" maxlength="300" style="display: none;"></textarea>
					<div class="btn btn-primary modify_content_btn modify_content_btn_{{$row['id']}}" onclick="showTextArea({{ $row['id'] }})">修改評價內容</div>
					<button type="submit" class="text-white btn btn-primary modify_content_submit evaluation_content_btn_{{ $row['id'] }}" style="display: none;">確認修改</button>
				</form>
				<form method="POST" action="{{ route('evaluationAdminComment', $row['id']) }}" style="margin:0px;display:inline;">
					{!! csrf_field() !!}
					<input type="hidden" name="id" value="{{$row['id']}}">
					<textarea class="form-control m-input comment_{{$row['id']}}" type="textarea" name="admin_comment" rows="3" maxlength="300" style="display: none;"></textarea>
					<div class="btn btn-success admin_comment_btn_{{$row['id']}}" onclick="showAdminCommentText({{ $row['id'] }})">站方附註留言</div>
					<button type="submit" class="text-white btn btn-success admin_comment_submit evaluation_admin_comment_btn_{{ $row['id'] }}" style="display: none;">確認修改</button>
				</form>
				<form method="POST" action="{{ route('evaluationDelete') }}" style="margin:0px;display:inline;">
					{!! csrf_field() !!}
					<input type="hidden" name="id" value="{{$row['id']}}">
					<button type="submit" class="btn btn-danger evaluation_delete_submit">刪除評價</button>
				</form>
				<div class="btn {{ $row['is_check'] ? 'btn-success':'btn-danger' }} evaluation_check_submit{{$row['id']}}" onclick="evaluationCheck('{{$row["id"]}}','{{$row["to_id"]}}','{{$row["is_check"] ? 0 : 1}}')">{{ $row['is_check'] ? '結束審核':'審核評價內容' }}</div>
				<a href="{{ route('showEvaluationPic', [ $row['id'], $row["to_id"]]) }}" target="_blank" class="btn btn-warning">照片編輯</a>
			</td>
		</tr>
	@endforeach
</table>
@endif

@if(count($out_evaluation_data)>0)
<h4>評價紀錄</h4>
<table class="table table-hover table-bordered">
	<tr>
		<th width="14%">暱稱</th>
		<th width="13%">帳號</th>
		<th width="12%">評價時間</th>
		<th width="4%">VIP</th>
		<th width="4%">會員認證</th>
		<th width="17%">評價內容</th>
		<th width="11%">上傳照片</th>
		<th width="25%">動作</th>
	</tr>
	@foreach($out_evaluation_data as $row)
		<tr>
			<td><a href="{{ route('admin/showMessagesBetween', [$user->id, $row['to_id']]) }}" target="_blank">{{ $row['to_name'] }}</a></td>
			<td><a href="{{ route('users/advInfo', $row['to_id']) }}" target='_blank'>{{ $row['to_email'] }}</a></td>
			<td>{{ $row['created_at'] }}</td>
			<td>@if($row['to_isvip']==1) VIP @endif</td>
			<td>@if($row['to_auth_status']==1) 已認證 @else N/A @endif</td>
			@if($row['is_check']==1)
				<td style="color: red;">***此評價目前由站方審核中***@if(!is_null($row['is_delete'])) <br><span style="color: red;">(該評價已刪除)</span> @endif</td>
			@else
				<td>@if(!is_null($row['is_delete'])) <span style="color: red;">(該評價已刪除)</span><br>@endif {{ $row['content'] }}</td>
			@endif
			<td class="evaluation_zoomIn">
				@if(!is_null($row['is_delete'])) <span style="color: red;">(該評價已刪除)</span> @endif
				@foreach($row['evaluation_pic'] as $evaluationPic)
					<li>
						<img src="{{ $evaluationPic->pic }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
					</li>
				@endforeach
			</td>
			<td>
				<form method="POST" action="{{ route('evaluationModifyContent', $row['id']) }}" style="margin:0px;display:inline;">
					{!! csrf_field() !!}
					<input type="hidden" name="id" value="{{$row['id']}}">
					<textarea class="form-control m-input content_{{$row['id']}}" type="textarea" name="evaluation_content" rows="3" maxlength="300" style="display: none;"></textarea>
					<div class="btn btn-primary modify_content_btn modify_content_btn_{{$row['id']}}" onclick="showTextArea({{ $row['id'] }})">修改評價內容</div>
					<button type="submit" class="text-white btn btn-primary modify_content_submit evaluation_content_btn_{{ $row['id'] }}" style="display: none;">確認修改</button>
				</form>
				<form method="POST" action="{{ route('evaluationAdminComment', $row['id']) }}" style="margin:0px;display:inline;">
					{!! csrf_field() !!}
					<input type="hidden" name="id" value="{{$row['id']}}">
					<textarea class="form-control m-input comment_{{$row['id']}}" type="textarea" name="admin_comment" rows="3" maxlength="300" style="display: none;"></textarea>
					<div class="btn btn-success admin_comment_btn_{{$row['id']}}" onclick="showAdminCommentText({{ $row['id'] }})">站方附註留言</div>
					<button type="submit" class="text-white btn btn-success admin_comment_submit evaluation_admin_comment_btn_{{ $row['id'] }}" style="display: none;">確認修改</button>
				</form>
				<form method="POST" action="{{ route('evaluationDelete') }}" style="margin:0px;display:inline;">
					{!! csrf_field() !!}
					<input type="hidden" name="id" value="{{$row['id']}}">
					<button type="submit" class="btn btn-danger evaluation_delete_submit">刪除評價</button>
				</form>
				<div class="btn {{ $row['is_check'] ? 'btn-success':'btn-danger' }} evaluation_check_submit{{$row['id']}}" onclick="evaluationCheck('{{$row["id"]}}','{{$row["from_id"]}}','{{$row["is_check"] ? 0 : 1}}')">{{ $row['is_check'] ? '結束審核':'審核評價內容' }}</div>
				<a href="{{ route('showEvaluationPic', [ $row['id'], $row["from_id"]]) }}" target="_blank" class="btn btn-warning">照片編輯</a>
			</td>
		</tr>
	@endforeach
</table>
@endif

@php
	$userAdvInfo=\App\Models\User::userAdvInfo($user->id);
@endphp
<br>
{{--<span>每周平均上線次數： {{ array_get($userAdvInfo,'login_times_per_week',0) }}</span>--}}
{{--<span>收藏會員次數： {{ array_get($userAdvInfo,'fav_count',0) }}</span>--}}
{{--<span>發信次數： {{ array_get($userAdvInfo,'message_count',0) }}</span>--}}
{{--<span>過去7天發信次數： {{ array_get($userAdvInfo,'message_count_7',0) }}</span>--}}
{{--<span>過去7天罐頭訊息比例： {{ array_get($userAdvInfo,'message_percent_7',0) }}</span>--}}
{{--<span>瀏覽其他會員次數： {{ array_get($userAdvInfo,'visit_other_count',0) }}</span>--}}
{{--<span>過去7天瀏覽其他會員次數： {{ array_get($userAdvInfo,'visit_other_count_7',0) }}</span>--}}
{{--<span>封鎖多少會員： {{ array_get($userAdvInfo,'blocked_other_count',0) }}</span>--}}
{{--<span>被多少會員封鎖： {{ array_get($userAdvInfo,'be_blocked_other_count',0) }}</span>--}}

<h4>進階資料</h4>
<table class="table table-hover table-bordered" style="width: 70%;">
	<tr>
		<th width="25%">過去7天瀏覽其他會員次數： {{ array_get($userAdvInfo,'visit_other_count_7',0) }}</th>
		<th width="20%">瀏覽其他會員次數： {{ array_get($userAdvInfo,'visit_other_count',0) }}</th>
		<th width="20%">封鎖多少會員： {{ array_get($userAdvInfo,'blocked_other_count',0) }}</th>
		<th width="20%">過去7天罐頭訊息比例： {{ array_get($userAdvInfo,'message_percent_7',0) }}</th>
	</tr>
	<tr>
		<th>過去七天發訊人數： {{ array_get($userAdvInfo,'message_people_count_7',0) }}</th>
		<th>發訊人數： {{ array_get($userAdvInfo,'message_people_count',0) }}</th>
		<th>被多少會員封鎖： {{ array_get($userAdvInfo,'be_blocked_other_count',0) }}</th>
		<th>每周平均上線次數： {{ array_get($userAdvInfo,'login_times_per_week',0) }}</th>
	</tr>
	<tr>
		<th>過去七天發訊次數： {{ array_get($userAdvInfo,'message_count_7',0) }}</th>
		<th>發訊次數： {{ array_get($userAdvInfo,'message_count',0) }}</th>
		<th>收藏會員次數： {{ array_get($userAdvInfo,'fav_count',0) }}</th>
		<th></th>
	</tr>
	<tr>
		<th>過去七天回訊人數： {{ array_get($userAdvInfo,'message_reply_people_count_7',0) }}</th>
		<th>回訊人數： {{ array_get($userAdvInfo,'message_reply_people_count',0) }}</th>
		<th></th>
		<th></th>
	</tr>
	<tr>
		<th>過去七天回訊次數： {{ array_get($userAdvInfo,'message_reply_count_7',0) }}</th>
		<th>回訊次數： {{ array_get($userAdvInfo,'message_reply_count',0) }}</th>
		<th></th>
		<th></th>
	</tr>
	<tr>
		<th>過去七天未回人數： {{ array_get($userAdvInfo,'message_no_reply_count_7',0) }}</th>
		<th>總未回人數： {{ array_get($userAdvInfo,'message_no_reply_count',0) }}</th>
		<th></th>
		<th></th>
	</tr>
</table>

<h4>VIP歷程</h4>
<table class="table table-hover table-bordered" style="width: 70%;">
	<tr>
		<th width="15%">PR：{{ $user->engroup==1 ?$pr :''}}</th>
		<th width="20%">時間：{{ $user->engroup==1 ? $pr_created_at :''}}</th>
		<th>VIP歷程： {{ $user->engroup==1 && $pr_log ? $pr_log:'暫無紀錄'}}</th>
	</tr>
</table>

<br>
<h4>帳號登入紀錄</h4>
<div>
	<a id="ip10days" href="/admin/users/ip/不指定/?type=detail&period=10days" target="_blank" class="btn btn-success" style="margin-left: 10px;">10天</a>
	<a id="ip20days" href="/admin/users/ip/不指定/?type=detail&period=20days" target="_blank" class="btn btn-primary">20天</a>
	<a id="ip30days" href="/admin/users/ip/不指定/?type=detail&period=30days" target="_blank" class="btn btn-warning">30天</a>
</div>
<table id="table_userLogin_log" class="table table-hover table-bordered">
	@foreach($userLogin_log as $logInLog)
		<tr>
			<td>
				<span class="loginItem showRecord" id="showloginTime{{substr($logInLog->loginDate,0,7)}}" data-sectionName="loginTime{{substr($logInLog->loginDate,0,7)}}" data-ip="不指定">{{ substr($logInLog->loginDate,0,7) . ' ['. $logInLog->dataCount .']' }}</span>
				<table>
					@php
						$CFP_count=count(array_get($logInLog->CfpID,'CfpID_group',[]));
						$IP_count=count(array_get($logInLog->Ip,'Ip_group',[]));
					@endphp
					@if($CFP_count>0)
						@foreach(array_get($logInLog->CfpID,'CfpID_group',[]) as $gpKey =>$group)
							@if($gpKey<5)
								<td class="loginItem" id="showcfpID{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}" data-sectionName="cfpID{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}" style="margin-left: 20px;min-width: 100px;">{{ $group->cfp_id.'('.$group->dataCount .')' }}</td>
							@endif
						@endforeach
					@endif
					@for($i=0; $i< 5-$CFP_count; $i++)
						<th style="min-width: 100px"></th>
					@endfor
					@if($CFP_count>=6)
						<th style="min-width: 100px">...</th>
					@else
						<th style="min-width: 100px"></th>
					@endif

					@if($IP_count>0)
						@foreach(array_get($logInLog->Ip,'Ip_group',[]) as $gpKey =>$group)
							@if($gpKey<10)
								<td class="loginItem ipItem" id="showIp{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}" data-sectionName="Ip{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}" data-ip="{{ $group->ip }}" style="margin-left: 20px;min-width: 150px;">{{ $group->ip.'('.$group->dataCount .')' }}</td>
							@endif
						@endforeach
					@endif
					@for($i=0; $i<10- $IP_count; $i++)
						<th style="min-width: 150px"></th>
					@endfor
					@if($IP_count>=11)
						<th style="min-width: 150px">...</th>
					@else
						<th style="min-width: 150px"></th>
					@endif
				</table>
			</td>
		</tr>
		<tr class="showLog" id="loginTime{{substr($logInLog->loginDate,0,7)}}">
			<td>
					<table class="table table-bordered" style="display: block; max-height: 500px; overflow-x: scroll;">
						<thead>
						<tr class="info">
							<th>登入時間</th>
							<th>IP</th>
							<th>登入裝置</th>
							<th>User Agent</th>
							<th>cfp_id</th>
							<th>Country</th>
						</tr>
						</thead>
						<tbody>
						@foreach($logInLog->items as $key => $item)
							<tr>
								<?php
								// $sitem = explode("/i#", $item);
								if(preg_match("/(iPod|iPhone)/", $item->userAgent))
									$device = '手機';
								else if(preg_match("/iPad/", $item->userAgent))
									$device = '平板';
								else if(preg_match("/android/i", $item->userAgent))
									$device = '手機';
								else
									$device = '電腦';
								?>
								<td>{{$item->created_at}}</td>
								<td><a href="{{ route('getIpUsers', [$item->ip]) }}" target="_blank">{{$item->ip}}</a></td>
								<td>{{ $device }}</td>
								<td>{{ str_replace("Mozilla/5.0","", $item->userAgent) }}</td>
								<td>{{$item->cfp_id}}</td>
								<td>{{$item->country}}</td>
							</tr>
						@endforeach
						</tbody>
					</table>
			</td>
		</tr>
		@foreach(array_get($logInLog->Ip,'Ip_group_items',[]) as $gpKey =>$group_items)
			<tr class="showLog" id="Ip{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}">
				<td>
					<table class="table table-bordered" style="display: block; max-height: 500px; overflow-x: scroll;">
						<thead>
						<tr class="info">
							<th>登入時間</th>
							<th>IP</th>
							<th>登入裝置</th>
							<th>User Agent</th>
							<th>cfp_id</th>
							<th>Country</th>
						</tr>
						</thead>
						<tbody>
						@foreach($group_items as $key => $item)
							<tr>
								<?php
								// $sitem = explode("/i#", $item);
								if(preg_match("/(iPod|iPhone)/", $item->userAgent))
									$device = '手機';
								else if(preg_match("/iPad/", $item->userAgent))
									$device = '平板';
								else if(preg_match("/android/i", $item->userAgent))
									$device = '手機';
								else
									$device = '電腦';
								?>
								<td>{{$item->created_at}}</td>
								<td><a href="{{ route('getIpUsers', [$item->ip]) }}" target="_blank">{{$item->ip}}</a></td>
								<td>{{ $device }}</td>
								<td>{{ str_replace("Mozilla/5.0","", $item->userAgent) }}</td>
								<td>{{$item->cfp_id}}</td>
								<td>{{$item->country}}</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</td>
			</tr>
		@endforeach
		@foreach(array_get($logInLog->CfpID,'CfpID_group_items',[]) as $gpKey =>$group_items)
			<tr class="showLog" id="cfpID{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}">
				<td>
					<table class="table table-bordered" style="display: block; max-height: 500px; overflow-x: scroll;">
						<thead>
						<tr class="info">
							<th>登入時間</th>
							<th>IP</th>
							<th>登入裝置</th>
							<th>User Agent</th>
							<th>cfp_id</th>
							<th>Country</th>
						</tr>
						</thead>
						<tbody>
						@foreach($group_items as $key => $item)
							<tr>
								<?php
								// $sitem = explode("/i#", $item);
								if(preg_match("/(iPod|iPhone)/", $item->userAgent))
									$device = '手機';
								else if(preg_match("/iPad/", $item->userAgent))
									$device = '平板';
								else if(preg_match("/android/i", $item->userAgent))
									$device = '手機';
								else
									$device = '電腦';
								?>
								<td>{{$item->created_at}}</td>
								<td><a href="{{ route('getIpUsers', [$item->ip]) }}" target="_blank">{{$item->ip}}</a></td>
								<td>{{ $device }}</td>
								<td>{{ str_replace("Mozilla/5.0","", $item->userAgent) }}</td>
								<td>{{$item->cfp_id}}</td>
								<td>{{$item->country}}</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</td>
			</tr>
		@endforeach
	@endforeach
</table>

{{-- 
@if(isset($fingerprints))
<h4>指紋記錄</h4>
	<table class="table table-hover table-bordered">
		<tr>
			<td>Hash 值</td>
			<td>IP</td>
			<td>記錄時間</td>
		</tr>
		@foreach($fingerprints as $f)
			<tr>
				<td><a href="{{ route("showFingerprint", $f->fp) }}" target="_blank">{{ $f->fp }}</a></td> 
				<td>{{ $f->ip }}</td>
				<td>{{ $f->created_at }}</td>
			</tr>
		@endforeach
	</table>
@endif
--}}
{{--<h4>所有訊息</h4>--}}
{{--<table class="table table-hover table-bordered">--}}
{{--<form action="{{ route('users/message/modify') }}" method="post">--}}
{{--    {!! csrf_field() !!}--}}
{{--	<input type="hidden" name="delete" id="delete" value="1">--}}
{{--	<tr>--}}
{{--		<td>發送給</td>--}}
{{--		<td>內容</td>--}}
{{--		<td>發送時間</td>--}}
{{--		<td>回覆收訊者</td>--}}
{{--		<td>封鎖收訊者</td>--}}
{{--        <td style="text-align: center; vertical-align: middle"><button type="submit" class="btn btn-danger delete-btn">刪除選取</button></td>--}}
{{--	</tr>--}}
{{--	@forelse ($userMessage as $key => $message)--}}
{{--		@if(isset($to_ids[$message->to_id]['engroup'] ))--}}
{{--		<tr>--}}
{{--			<td>--}}
{{--				<a href="{{ route('admin/showMessagesBetween', [$user->id, $message->to_id]) }}" target="_blank">--}}
{{--					<p @if($to_ids[$message->to_id]['engroup'] == '2') style="color: #F00;" @else  style="color: #5867DD;"  @endif>--}}
{{--						{{ $to_ids[$message->to_id]['name'] }}--}}
{{--						@if($to_ids[$message->to_id]['vip'])--}}
{{--						    @if($to_ids[$message->to_id]['vip']=='diamond_black')--}}
{{--						        <img src="/img/diamond_black.png" style="height: 16px;width: 16px;">--}}
{{--						    @else--}}
{{--						        @for($z = 0; $z < $to_ids[$message->to_id]['vip']; $z++)--}}
{{--						            <img src="/img/diamond.png" style="height: 16px;width: 16px;">--}}
{{--						        @endfor--}}
{{--						    @endif--}}
{{--						@endif--}}
{{--						@for($i = 0; $i < $to_ids[$message->to_id]['tipcount']; $i++)--}}
{{--						    👍--}}
{{--						@endfor--}}
{{--						@if(!is_null($to_ids[$message->to_id]['isBlocked']))--}}
{{--						    @if(!is_null($to_ids[$message->to_id]['isBlocked']['expire_date']))--}}
{{--						        ({{ round((strtotime($to_ids[$message->to_id]['isBlocked']['expire_date']) - getdate()[0])/3600/24 ) }}天)--}}
{{--						    @else--}}
{{--						        (永久)--}}
{{--						    @endif--}}
{{--						@endif--}}
{{--					</p>--}}
{{--				</a>--}}
{{--			</td>--}}
{{--			<td>{{ $message->content }}</td>--}}
{{--			<td>{{ $message->created_at }}</td>--}}
{{--			<td>--}}
{{--				<a href="{{ route('AdminMessengerWithMessageId', [$message->to_id, $message->id]) }}" target="_blank" class='btn btn-dark'>撰寫</a>--}}
{{--			</td>--}}
{{--			<td>--}}
{{--				<a class="btn btn-danger ban-user{{ $key }}" href="#" data-toggle="modal" data-target="#blockade" data-id="{{ route('banUserWithDayAndMessage', [$message->to_id, $message->id]) }}" data-name="{{ $to_ids[$message->to_id]['name']}}">封鎖</a>--}}
{{--			</td>--}}
{{--            <td style="text-align: center; vertical-align: middle">--}}
{{--                <input type="checkbox" name="msg_id[]" value="{{ $message->id }}" class="form-control">--}}
{{--            </td>--}}
{{--		</tr>--}}
{{--		@else--}}
{{--			<tr>--}}
{{--				<td colspan="6">--}}
{{--					會員資料已刪除--}}
{{--				</td>--}}
{{--			</tr>--}}
{{--		@endif--}}
{{--    @empty--}}
{{--        沒有訊息--}}
{{--    @endforelse--}}
{{--</form>--}}
{{--</table>--}}
{{--{!! $userMessage->links() !!}--}}
<style>
	#m_log td {
		vertical-align: middle;
		padding-top:0px;
		padding-bottom: 0px;
	}
	#m_log p {
		line-height:unset;
		margin-bottom:0px;
	}
</style>
<h4>所有訊息</h4>
<table id="m_log" class="table table-hover table-bordered">
	<tr>
		<th width="5%"></th>
		<th width="10%">發送給</th>
		<th>最新內容</th>
		<th>上傳照片</th>
		<th width="15%">發送時間</th>
		<th width="8%">發送數 <br>本人/對方</th>
	</tr>
	@foreach($userMessage_log as $Log)
		<tr>
			@php
				$ref_user=\App\Models\User::findById($Log->ref_user_id);
				$ref_user_id=$Log->ref_user_id;
				$message_log=\App\Models\Message::withTrashed()
					->where([['message.to_id', $user->id],['message.from_id', $ref_user_id]])
					->orWhere([['message.from_id', $user->id],['message.to_id', $ref_user_id]])
					->orderBy('created_at')->first();

				$toCount_user_id=\App\Models\Message::withTrashed()->where('from_id',$user->id)->where('to_id',$ref_user_id)->get()->count();
				$toCount_ref_user_id=\App\Models\Message::withTrashed()->where('from_id',$ref_user_id)->where('to_id',$user->id)->get()->count();
			@endphp
			<td style="text-align: center;"><button data-toggle="collapse" data-target="#msgLog{{$ref_user_id}}" class="accordion-toggle btn btn-primary message_toggle">+</button></td>
			<td>@if(!empty($ref_user->name))<a href="{{ route('admin/showMessagesBetween', [$user->id, $ref_user_id]) }}" target="_blank">{{ $ref_user->name }}</a>@else 會員資料已刪除@endif</td>
			<td id="new{{$Log->to_id}}">{{($message_log->from_id==$user->id ? '(發)' :'(回)') .$message_log->content}}</td>
			<td class="evaluation_zoomIn">
				@php
					$messagePics=is_null($message_log->pic) ? [] : json_decode($message_log->pic,true);
				@endphp
				@if(isset($messagePics))
					@foreach( $messagePics as $messagePic)
						@if(isset($messagePic['file_path']))
							<li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
								<img src="{{ $messagePic['file_path'] }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
							</li>
						@else
							<li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
								無法找到圖片
							</li>
						@endif
					@endforeach
				@endif
			</td>
			<td id="new_time{{$ref_user_id}}">@if(!empty($ref_user->name)){{$message_log->created_at}}@else 會員資料已刪除@endif</td>
			<td>@if(!empty($ref_user->name)){{$toCount_user_id .'/'.$toCount_ref_user_id}}@else 會員資料已刪除@endif</td>
		</tr>
		<tr class="accordian-body collapse" id="msgLog{{$ref_user_id}}">
			<td class="hiddenRow" colspan="5">
				<table class="table table-bordered">
					<thead>
					<tr class="info">
						<th width="15%">暱稱</th>
						<th>內容</th>
						<th width="35%">上傳照片</th>
						<th width="10%">發送時間</th>
                        <th width="5%" nowrap>狀態</th>
                    </tr>
					</thead>
					<tbody>
					@foreach ($Log->items as $key => $item)
{{--						@if($key==0)--}}
{{--							<script>--}}
{{--								$('#new' + {{$Log->to_id}}).text('{{ $item->content }}');--}}
{{--								$('#new_time' + {{$Log->to_id}}).text('{{ $item->m_time }}');--}}
{{--							</script>--}}
{{--						@endif--}}
						<tr>
							<td>
								@php
									$from_id_user=\App\Models\User::findById($item->from_id);
								@endphp
								<a href="{{ route('admin/showMessagesBetween', [$user->id, $ref_user_id]) }}" target="_blank">
									<p style="margin-bottom:0px; @if($item->engroup == '2') color: #F00; @else color: #5867DD; @endif">{{$item->name }}
									@php
										$to_id_tipcount = \App\Models\Tip::TipCount_ChangeGood($item->to_id);
										$to_id_vip = \App\Models\Vip::vip_diamond($item->to_id);
									@endphp
									@if($to_id_vip)
										@if($to_id_vip=='diamond_black')
											<img src="/img/diamond_black.png" style="height: 16px;width: 16px;">
										@else
											@for($z = 0; $z < $to_id_vip; $z++)
												<img src="/img/diamond.png" style="height: 16px;width: 16px;">
											@endfor
										@endif
									@endif
									@for($i = 0; $i < $to_id_tipcount; $i++)
										👍
									@endfor
									@if(!is_null($item->banned_id))
										@if(!is_null($item->banned_expire_date))
											({{ round((strtotime($item->banned_expire_date) - getdate()[0])/3600/24 ) }}天)
										@else
											(永久)
										@endif
									@endif
									</p>
								</a>
							</td>
							<td><p style="word-break:break-all;">{{ $item->content }}</p></td>
							<td class="evaluation_zoomIn">
								@php
									$messagePics=is_null($item->pic) ? [] : json_decode($item->pic,true);
								@endphp
								@if(isset($messagePics))
									@foreach( $messagePics as $messagePic)
										@if(isset($messagePic['file_path']))
											<li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
												<img src="{{ $messagePic['file_path'] }}" style="max-width:130px;max-height:130px;margin-right: 5px;">
											</li>
										@else
											<li style="float:left;margin:2px 2px;list-style:none;display:block;white-space: nowrap;width: 135px;">
												無法找到圖片
											</li>
										@endif
									@endforeach
								@endif
							</td>
							<td>{{ $item->m_time }}</td>
                            <td nowrap>{{ $item->unsend?'已收回':'' }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</td>
		</tr>
		@endforeach
</table>
{!! $userMessage_log->links('pagination::sg-pages3') !!}

<h4>詳細資料</h4>
<table class='table table-hover table-bordered'>
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
</table>

<div id="pic_block">
<div class="loading"><span class="loading_text">loading</span></div>
</div>
</body>
<div class="modal fade" id="blockade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 60%;">
		<div class="modal-content" >
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">封鎖 </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/users/toggleUserBlock" method="POST" id="clickToggleUserBlock">
				{!! csrf_field() !!}
				<input type="hidden" value="" name="user_id" id="blockUserID">
				<input type="hidden" value="advInfo" name="page">
				<input type="hidden" name="vip_pass" value="">
                <input type="hidden" name="adv_auth" value="">
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
					    新增自動封鎖條件 @if($user->engroup==2) ( 驗證封鎖 ) @endif
						<div class="form-group">
							<label for="cfp_id">CFP_ID @if($user->engroup==2) ( 驗證封鎖 ) @endif</label>
							<select multiple class="form-control" id="cfp_id" name="cfp_id[]">
								@foreach( $cfp_id as $row)
								<option value="{{$row->cfp_id}}">{{$row->cfp_id}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label>照片 @if($user->engroup==2) ( 驗證封鎖 ) @endif</label>
                            <div id="autoban_pic_gather">
                            @foreach ( \App\Models\MemberPic::getSelfIDPhoto($user->id) as $pic)
                            @include('admin.users.advInfo_autoban_pic_tpl')
                            @endforeach
                            @foreach (collect([$user->meta]) as $pic)
                            @include('admin.users.advInfo_autoban_pic_tpl')
                            @endforeach
                            @foreach ($user->pic_orderByDecs as $pic)
                            @include('admin.users.advInfo_autoban_pic_tpl')
                            @endforeach
                            @foreach ($user->avatar_deleted as $pic)
                            @include('admin.users.advInfo_autoban_pic_tpl')
                            @endforeach                            
                            @foreach ($user->pic_onlyTrashed as $pic)
                            @include('admin.users.advInfo_autoban_pic_tpl')
                            @endforeach
                            </div>
						</div>                        
						<div class="form-group">
							<label for="ip">IP</label>
							<table id="table_userLogin_log" class="table table-hover table-bordered">
								@foreach($userLogin_log as $logInLog)
									<tr class="loginItem" id="showloginTimeIP{{substr($logInLog->loginDate,0,7)}}" data-sectionName="loginTimeIP{{substr($logInLog->loginDate,0,7)}}">
										<td>
											<span>{{ substr($logInLog->loginDate,0,7) . ' ['. count(array_get($logInLog->Ip,'Ip_group',[])) .']' }}</span>
										</td>
									</tr>
									<tr class="showLog" id="loginTimeIP{{substr($logInLog->loginDate,0,7)}}">
										<td>
											<select multiple class="form-control" name="ip[]">
												@foreach(array_get($logInLog->Ip,'Ip_group',[]) as $key => $item)
													<option value="{{$item->ip}}">{{ '['.$item->loginTime .']  ' .$item->ip }}</option>
												@endforeach
											</select>
											{{--<table class="table table-bordered" style="display: block; max-height: 500px; overflow-x: scroll;">
												<thead>
												<tr class="info">
													<th></th>
													<th>登入時間</th>
													<th>IP</th>
												</tr>
												</thead>
												<tbody>
												@foreach(array_get($logInLog->Ip,'Ip_group',[]) as $key => $item)
													<tr>
														<td> <input type="checkbox" value="{{$item->ip}}" name="ip[]"></td>
														<td>{{$item->loginTime}}</td>
														<td><a href="{{ route('getIpUsers', [$item->ip]) }}" target="_blank">{{$item->ip}}</a></td>
													</tr>
												@endforeach
												</tbody>
											</table>--}}
										</td>
									</tr>
								@endforeach
							</table>
							{{--<select multiple class="form-control" id="ip" name="ip[]" style="height:300px;">
								@foreach( $ip as $row)
									<option value="{{$row->ip}}">{{substr($row->last_tiime,0,10) .'  ['.$row->ip.']'}}</option>
								@endforeach
							</select>--}}
						</div>
						{{--<div class="form-group">
							<label for="user_agent">User Agent</label>
							<select multiple class="form-control" id="user_agent" name="userAgent[]">
								@foreach( $userAgent as $row)
									<option value="{{$row->userAgent}}" title="{{ str_replace("Mozilla/5.0","", $row->userAgent) }}">{{ str_replace("Mozilla/5.0","", $row->userAgent) }}</option>
								@endforeach
							</select>
						</div>--}}

{{--						<div class="form-group">--}}
{{--					    	<label for="ip">IP</label>--}}
{{--							<input type="checkbox" name="ip[]" id="ip" value="" class="form-check-input">Check me out--}}
{{--						</div>--}}
                        <hr>
                        新增自動封鎖關鍵字 ( @if($user->engroup==2) 驗證封鎖 @else 永久封鎖  @endif )
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
				<input type="hidden" name="vip_pass">
                <input type="hidden" name="adv_auth">
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
<div class="modal fade" id="isWarned_blockade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="isWarnedModalLabel">警示用戶</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/admin/users/isWarned_user" method="POST">
            	{!! csrf_field() !!}
				<input type="hidden" value="{{ $user['id'] }}" name="user_id">
                <!--
            	<input type="hidden" value="BannedInUserInfo" name="fp">
            	<input type="hidden" value="{{ url()->full() }}" name="page">
                -->
                <div class="modal-body">
                    <input type="radio" name="isWarnedType" value="" /> 手機驗證
                    <input type="radio" name="isWarnedType" value="adv_auth" @if($user->advance_auth_status==1) disabled  @endif /> <span class="@if($user->advance_auth_status==1) gray @endif">進階驗證</span>
                </div>
                <div class="modal-footer">
                	<button type="button" class='btn btn-outline-success ban-user' onclick="WarnedToggler({{$user['id']}},1);return false;"> 送出 </button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--隱藏 -->
<div>
	@if (Auth::user()->can('admin') || Auth::user()->can('juniorAdmin'))
		<form action="/admin/users/toggleHidden" method="POST" id="clickisHiddenAction">
			{{ csrf_field() }}
			<input type="hidden" value="" name="user_id" id="HiddenID">
			<input type="hidden" value="" name="isHidden" id="isHidden">
			<input type="hidden" value="advInfo" name="page">
		</form>
	@elseif (Auth::user()->can('readonly'))
		<form action="/users/toggleHidden/readOnly" method="POST" id="clickisHiddenAction">
			{{ csrf_field() }}
			<input type="hidden" value="" name="user_id" id="HiddenID">
			<input type="hidden" value="" name="isHidden" id="isHidden">
			<input type="hidden" value="back" name="page">
		</form>
	@endif
</div>
<!-- -->
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
<!--照片查看-->
<div class="big_img">
	<!-- 自定义分页器 -->
	<div class="swiper-num">
		<span class="active"></span>/
		<span class="total"></span>
	</div>
	<div class="swiper-container2">
		<div class="swiper-wrapper">
		</div>
	</div>
	<div class="swiper-pagination2"></div>
</div>
<script src="/js/vendors.bundle.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function(){

	//test
	$('.tr_hide').hide();
	$('.tr_more').click(function () {
        $(".tr_hide_" + $(this).attr("r_id")).toggle();
    });
	//test

	$('.message_toggle').on('click',function(e){
		$(this).text(function(i,old){
			return old=='+' ?  '-' : '+';
		});
	});

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
	$('.warned-user').click(function(){
		if (typeof $(this).data('id') !== 'undefined') {
			$("#warnedModalLabel").html('站方警示 '+ $(this).data('name'))
			$("#warnedUserID").val($(this).data('id'))
		}
	});
    
	$('.isWarned-user').click(function(){
		if (typeof $(this).data('id') !== 'undefined') {
			$("#isWarnedModalLabel").html('警示用戶 '+ $(this).data('name'))
			$("#warnedUserID").val($(this).data('id'))
		}
	});    

	$('.block_vip_pass').on('click', function () {
		let vipPass;
		vipPass = $(this).data('vip_pass');

		@if( $user['isvip']==1 && $user['isfreevip']==0 )
				vipPass = 0;
		@endif

		$("#clickToggleUserBlock input[name='vip_pass']").val(vipPass);

		if($(this).data('vip_pass')==1){
			$("#exampleModalLabel").append(' (付費封鎖)');
		}
	}) 
    
	$('.block_advance_auth').on('click', function () {
		let advAuth;
		advAuth = $(this).data('adv_auth');

		@if( $user['advance_auth_status']==1 )
				advAuth = 0;
		@endif

		$("#clickToggleUserBlock input[name='adv_auth']").val(advAuth);
        $("#clickToggleUserBlock input[name='vip_pass']").val(0);
		if($(this).data('adv_auth')==1){
			$("#exampleModalLabel").append(' (驗證封鎖)');
		}
	})     

	$('.warned_vip_pass').on('click', function () {
		let vipPass;
		vipPass = $(this).data('vip_pass');

		@if( $user['isvip']==1 && $user['isfreevip']==0 )
				vipPass = 0;
		@endif

		$("#clickToggleUserWarned input[name='vip_pass']").val(vipPass);
		if($(this).data('vip_pass')==1){
			$("#warnedModalLabel").append(' (付費警示)');
		}
	}) 
    
	$('.warned_adv_auth').on('click', function () {
		let advAuth;
		advAuth = $(this).data('adv_auth');

		@if( $user['advance_auth_status']==1  )
				advAuth = 0;
		@endif

		$("#clickToggleUserWarned input[name='adv_auth']").val(advAuth);
        $("#clickToggleUserWarned input[name='vip_pass']").val(0);
		if($(this).data('adv_auth')==1){
			$("#warnedModalLabel").append(' (驗證警示)');
		}
	})    

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

	$('.showLog').hide();
	$('.loginItem').click(function(){
		var sectionName =$(this).attr('data-sectionName');
		$('.showLog').hide();
		$('#'+sectionName).show();
	});

	$('.showRecord').click(function(){
		var getIP =$(this).attr('data-ip');
		var user_id='{{ $user->id }}';
		$('#ip10days').attr("href",'/admin/users/ip/' + getIP + '?user_id=' + user_id +'&period=10days');
		$('#ip20days').attr("href",'/admin/users/ip/' + getIP + '?user_id=' + user_id +'&period=20days');
		$('#ip30days').attr("href",'/admin/users/ip/' + getIP + '?user_id=' + user_id +'&period=30days');
	});

	$('.ipItem').click(function(){
		var getIP =$(this).attr('data-ip');
		var user_id='{{ $user->id }}';
		$('#ip10days').attr("href",'/admin/users/ip/' + getIP + '?period=10days');
		$('#ip20days').attr("href",'/admin/users/ip/' + getIP + '?period=20days');
		$('#ip30days').attr("href",'/admin/users/ip/' + getIP + '?period=30days');
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
function HiddenAction(isHidden, user_id){
	$("#isHidden").val(isHidden);
	$("#HiddenID").val(user_id);
	//console.log(isHidden, user_id); // 這裡有執行到
	// 要善用 F12 檢查 Javascript 有沒有正常運作
	$("#clickisHiddenAction").submit();
}
function RecommendedToggler(user_id,Recommended){
	$("#RecommendedUserID").val(user_id);
	$("#Recommended").val(Recommended);
	$("#toggleRecommendedUser").submit();
}

function WarnedToggler(user_id,isWarned){
	$.ajax({
		type: 'POST',
		url: "/admin/users/isWarned_user?{{csrf_token()}}={{now()->timestamp}}",
		data:{
			_token: '{{csrf_token()}}',
			id: user_id,
			status: isWarned,
            isWarnedType:$('#isWarned_blockade input[name=isWarnedType]:checked').val(),
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
			url: "/admin/users/unblock_user?{{csrf_token()}}={{now()->timestamp}}",
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
			url: "/admin/users/unwarned_user?{{csrf_token()}}={{now()->timestamp}}",
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

function showTextArea(id){
	$('.modify_content_btn_'+id).hide();
	$('.content_'+id).show();
	$('.evaluation_content_btn_'+id).show();
}
$('.modify_content_submit').on('click',function(e){

	if(!confirm('確定要修改該筆評價內容?')){
		e.preventDefault();
	}
});
function showAdminCommentText(id){
	$('.admin_comment_btn_'+id).hide();
	$('.comment_'+id).show();
	$('.evaluation_admin_comment_btn_'+id).show();
}
$('.admin_comment_submit').on('click',function(e){

	if(!confirm('確定要修改站方附註留言?')){
		e.preventDefault();
	}
});
$('.evaluation_delete_submit').on('click',function(e){
	if(!confirm('確定要刪除該筆評價?')){
		e.preventDefault();
	}
});
function evaluationCheck(eid,userid,is_check) {
	if ($(".evaluation_check_submit"+eid).text() == '結束審核')
		var showMsg = '確定要將該筆評價移除"審核中"狀態?';
	else
		var showMsg = '確定要將該筆評價變更為"審核中"?';

	if (confirm(showMsg)) {
		$.ajax({
			type: 'POST',
			url: "/admin/users/evaluation/check?{{csrf_token()}}={{now()->timestamp}}",
			data: {
				_token: '{{csrf_token()}}',
				id: eid,
				userid: userid,
				is_check: is_check,
			},
			dataType: "json",
			success: function (res) {
				var tempwindow=window.open('_blank');
				tempwindow.location=res.redirect_to ;
				location.reload();
			}
		});
	}
}

function showPhoneInput(){
	$('.modify_phone_submit').show();
	$("input[name='phone']").val('');
	$('.phoneInput').removeAttr('readonly');
	$('.test').hide();
}
$('.modify_phone_submit').on('click',function(e){

	if(!confirm('確定要修改手機?')){
		e.preventDefault();
	}
});
$('.delete_phone_submit').on('click',function(e){
	if(!confirm('確定要刪除手機?')){
		e.preventDefault();
	}
});

$("input[name='phone']").keyup(function(){
	$.ajax({
		type: 'POST',
		url: "/admin/users/phone/search?{{csrf_token()}}={{now()->timestamp}}",
		data: {
			_token: '{{csrf_token()}}',
			phone: $(this).val(),
		},
		dataType: "json",
		success: function (res) {
			console.log(res.hasData);
			if(res.hasData==1){
				//console.log(res.data.user_email);
				//console.log(res.data.user_info_page);
				//門號如果已經註冊過，顯示註冊的email並可以連結到基本資料頁面
				$('#phoneKeyInAlert').html('<span>該門號已經註冊過</span><br><span>帳號：<a href="'+ res.data.user_info_page +'" target="_blank">' + res.data.user_email + '</a></span>');
				$('#phoneKeyInAlert').show();
			}else{
				$('#phoneKeyInAlert').hide();
			}
		}
	});
});


</script>
<!--照片查看-->
<link type="text/css" rel="stylesheet" href="/new/css/app.css">
<link rel="stylesheet" type="text/css" href="/new/css/swiper2.min.css"/>
<script type="text/javascript" src="/new/js/swiper.min.js"></script>
<script>
	$(document).ready(function () {
		/*调起大图 S*/
		var mySwiper = new Swiper('.swiper-container2',{
			pagination : '.swiper-pagination2',
			paginationClickable:true,
			onInit: function(swiper){//Swiper初始化了
				// var total = swiper.bullets.length;
				var active =swiper.activeIndex;
				$(".swiper-num .active").text(active);
				// $(".swiper-num .total").text(total);
			},
			onSlideChangeEnd: function(swiper){
				var active =swiper.realIndex +1;
				$(".swiper-num .active").text(active);
			}
		});

		$(".evaluation_zoomIn li").on("click",
			function () {
				var imgBox = $(this).parent(".evaluation_zoomIn").find("li");
				var i = $(imgBox).index(this);
				$(".big_img .swiper-wrapper").html("")

				for (var j = 0, c = imgBox.length; j < c ; j++) {
					$(".big_img .swiper-wrapper").append('<div class="swiper-slide"><div class="cell"><img src="' + imgBox.eq(j).find("img").attr("src") + '" / ></div></div>');
				}
				mySwiper.updateSlidesSize();
				mySwiper.updatePagination();
				$(".big_img").css({
					"z-index": 1001,
					"opacity": "1"
				});
				//分页器
				var num = $(".swiper-pagination2 span").length;
				$(".swiper-num .total").text(num);
				// var active =$(".swiper-pagination2").index(".swiper-pagination-bullet-active");
				$(".swiper-num .active").text(i + 1);
				// console.log(active)

				mySwiper.slideTo(i, 0, false);
				return false;
			});
		$(".swiper-container2").click(function(){
			$(this).parent(".big_img").css({
				"z-index": "-1",
				"opacity": "0"
			});
		});

		@if(count($isEverBanned_log)>1)
			$('#showMore_banned').popover({
				animated: 'fade',
				placement: 'top',
				//trigger: 'click',
				trigger: 'hover',
				html: true,
				content: function () { return $('#showMore_banned_log').html(); }
			});
		@endif

		@if(count($isEverWarned_log)>1)
			$('#showMore_warned').popover({
				animated: 'fade',
				placement: 'top',
				//trigger: 'click',
				trigger: 'hover',
				html: true,
				content: function () { return $('#showMore_warned_log').html(); }
			});
		@endif
        
        $.ajax({
            type: 'GET',
            url: location.pathname+'?block=pic&{{csrf_token()}}={{now()->timestamp}}',
            success: function(res){
                $('#pic_block').html(res);
            }});        
        
       
	});
	/*调起大图 E*/

	function forum_toggle(uid, status) {
		$.post('{{ route('forum_toggle') }}', {
			uid: uid,
			status: status,
			_token: '{{ csrf_token() }}'
		}, function (data) {
			location.reload();
		});
	}
</script>
<!--照片查看end-->
</html>
