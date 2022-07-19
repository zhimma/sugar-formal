@include('partials.header')
@yield("pre-javascript")
@include('partials.scripts')
@yield("javascript")
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
</style>
<body style="padding: 15px;">
@include('partials.errors')
@include('partials.message')
<h1>{{ $user->name }}的所有資料</h1>
<h4>基本資料</h4>
<form action="{{ route('users/save', $user->id) }}" id='user_data' method='POST'>
    {!! csrf_field() !!}
    <table class='table table-hover table-bordered'>
        <tr>
            <th>會員ID</th>
            <th>暱稱</th>
            <th>標題</th>
            <th>男/女</th>
            <th>Email</th>
            <th>建立時間</th>
            <th>更新時間</th>
            <th>上次登入</th>
        </tr>
        <tr>
            <td>{{ $user->id }}</td>
            <td><input type="text" value='{{ $user->name }}' name='name' class='form-control'></td>
            <td><input type="text" value='{{ $user->title }}' name='title' class='form-control'></td>
            <td>
                <select name="engroup" id="engroup" class='form-control'>
                    <option value="1" @if($user->engroup==1) selected @endif>男</option>
                    <option value="2" @if($user->engroup==2) selected @endif>女</option>
                </select>
            </td>
            <td><input type="text" value='{{ $user->email }}' name='email' class='form-control'></td>
            <td>{{ $user->created_at }}</td>
            <td>{{ $user->updated_at }}</td>
            <td>{{ $user->last_login }}</td>
        </tr>
    </table>
    <button type='submit' class='text-white btn btn-primary'>儲存</button>
    <br><br>
    <h4>詳細資料</h4>
    <table class='table table-hover table-bordered'>
        <tr>
            <th>會員ID</th>
            <td>{{ $userMeta->user_id }}</td>
            <th>手機</th>
            <td>{{ $userMeta->phone }}</td>
            <th>是否已啟動</th>
            <td>@if($userMeta->is_active == 1) 是 @else 否 @endif</td>
            <th rowspan='3'>照片</th>
            <td rowspan='3'>@if($userMeta->pic) <img src="{{$userMeta->pic}}" width='150px'> @else 無 @endif</td>
        </tr>
        <tr>
            <th>縣市</th>
            <td class="twzipcode"><div class="twzip" data-role="county" data-name="city" data-value="{{$userMeta->city}}"></div><div class="twzip" data-role="district" data-name="area" data-value="{{$userMeta->area}}"></div></td>
            <th>拒絕查詢的縣市</th>
            <td class="twzipcode"><div class="twzip" data-role="county" data-name="blockcity" data-value="{{$userMeta->blockcity}}"></div><div class="twzip" data-role="district" data-name="blockarea" data-value="{{$userMeta->blockarea}}"></div></td>
            <th>預算</th>
            <td>
                <select class="form-control" name="budget">
                    <option value="">請選擇</option>
                    <option value="基礎" @if($userMeta->budget == '基礎') selected @endif>基礎</option>
                    <option value="進階" @if($userMeta->budget == '進階') selected @endif>進階</option>
                    <option value="高級" @if($userMeta->budget == '高級') selected @endif>高級</option>
                    <option value="最高" @if($userMeta->budget == '最高') selected @endif>最高</option>
                    <option value="可商議" @if($userMeta->budget == '可商議') selected @endif>可商議</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>生日</th>
            <td class="form-inline">
                <select name="year" class="form-control"></select>年
                <select name="month" class="form-control">
                    <option value="1" @if($month == '01') selected @endif>1</option>
                    <option value="2" @if($month == '02') selected @endif>2</option>
                    <option value="3" @if($month == '03') selected @endif>3</option>
                    <option value="4" @if($month == '04') selected @endif>4</option>
                    <option value="5" @if($month == '05') selected @endif>5</option>
                    <option value="6" @if($month == '06') selected @endif>6</option>
                    <option value="7" @if($month == '07') selected @endif>7</option>
                    <option value="8" @if($month == '08') selected @endif>8</option>
                    <option value="9" @if($month == '09') selected @endif>9</option>
                    <option value="10" @if($month == '10') selected @endif>10</option>
                    <option value="11" @if($month == '11') selected @endif>11</option>
                    <option value="12" @if($month == '12') selected @endif>12</option>
                </select>月
                <select name="day" class="form-control"></select>日

                <!--<input type='text' class="form-control" id="m_datepicker_1" name="birthdate" readonly data-date-format='yyyy-mm-dd' placeholder="請選擇" value="{{ date('Y-m-d', strtotime($userMeta->birthdate)) }}" />-->
            </td>
            <th>身高</th>
            <td><input class="form-control m-input" name="height" type="number" id="input-height" value="{{$userMeta->height}}"></td>
            <th>職業</th>
            <td>
                <select class="form-control" name="occupation">
                    <option value="">請選擇</option>
                    <option value="學生" @if($userMeta->occupation == '學生') selected @endif>學生</option>
                    <option value="無業" @if($userMeta->occupation == '無業') selected @endif>無業</option>
                    <option value="人資" @if($userMeta->occupation == '人資') selected @endif>人資</option>
                    <option value="業務銷售" @if($userMeta->occupation == '業務銷售') selected @endif>業務銷售</option>
                    <option value="行銷企劃" @if($userMeta->occupation == '行銷企劃') selected @endif>行銷企劃</option>
                    <option value="行政助理" @if($userMeta->occupation == '行政助理') selected @endif>行政助理</option>
                    <option value="專案管理" @if($userMeta->occupation == '專案管理') selected @endif>專案管理</option>
                    <option value="餐飲類服務業" @if($userMeta->occupation == '餐飲類服務業') selected @endif>餐飲類服務業</option>
                    <option value="旅遊類服務業" @if($userMeta->occupation == '旅遊類服務業') selected @endif>旅遊類服務業</option>
                    <option value="美容美髮美甲芳療" @if($userMeta->occupation == '美容美髮美甲芳療') selected @endif>美容美髮美甲芳療</option>
                    <option value="操作員" @if($userMeta->occupation == '操作員') selected @endif>操作員</option>
                    <option value="文字工作者" @if($userMeta->occupation == '文字工作者') selected @endif>文字工作者</option>
                    <option value="學術研究" @if($userMeta->occupation == '學術研究') selected @endif>學術研究</option>
                    <option value="教育輔導" @if($userMeta->occupation == '教育輔導') selected @endif>教育輔導</option>
                    <option value="金融營業交易" @if($userMeta->occupation == '金融營業交易') selected @endif>金融營業交易</option>
                    <option value="財務會計" @if($userMeta->occupation == '財務會計') selected @endif>財務會計</option>
                    <option value="總機秘書櫃檯" @if($userMeta->occupation == '總機秘書櫃檯') selected @endif>總機秘書櫃檯</option>
                    <option value="法務記帳代書" @if($userMeta->occupation == '法務記帳代書') selected @endif>法務記帳代書</option>
                    <option value="資訊軟體" @if($userMeta->occupation == '資訊軟體') selected @endif>資訊軟體</option>
                    <option value="客服" @if($userMeta->occupation == '客服') selected @endif>客服</option>
                    <option value="貿易船務" @if($userMeta->occupation == '貿易船務') selected @endif>貿易船務</option>
                    <option value="交通運輸物流" @if($userMeta->occupation == '交通運輸物流') selected @endif>交通運輸物流</option>
                    <option value="倉管採購" @if($userMeta->occupation == '倉管採購') selected @endif>倉管採購</option>
                    <option value="設計美術" @if($userMeta->occupation == '設計美術') selected @endif>設計美術</option>
                    <option value="模特演員" @if($userMeta->occupation == '模特演員') selected @endif>模特演員</option>
                    <option value="傳播藝術" @if($userMeta->occupation == '傳播藝術') selected @endif>傳播藝術</option>
                    <!--  <option value="1" @if($userMeta->job == '1') selected @endif>其他(自填)</option> -->
                </select>
            </td>
        </tr>
        <tr>
            <th>體重</th>
            <td><input class="form-control m-input twzip" type="number" name="weight" id="input-weight" value="{{$userMeta->weight}}"></td>
            <th>罩杯</th>
            <td>
                <select class="form-control" name="cup">
                    <option value="">請選擇</option>
                    <option value="A" @if($userMeta->cup == 'A') selected @endif>A</option>
                    <option value="B" @if($userMeta->cup == 'B') selected @endif>B</option>
                    <option value="C" @if($userMeta->cup == 'C') selected @endif>C</option>
                    <option value="D" @if($userMeta->cup == 'D') selected @endif>D</option>
                    <option value="E" @if($userMeta->cup == 'E') selected @endif>E</option>
                    <option value="F" @if($userMeta->cup == 'F') selected @endif>F</option>
                </select>
            </td>
            <th>體型</th>
            <td>
                <select class="form-control" name="body">
                    <option value="">請選擇</option>
                    <option value="瘦" @if($userMeta->body == '瘦') selected @endif>瘦</option>
                    <option value="標準" @if($userMeta->body == '標準') selected @endif>標準</option>
                    <option value="微胖" @if($userMeta->body == '微胖') selected @endif>微胖</option>
                    <option value="胖" @if($userMeta->body == '胖') selected @endif>胖</option>
                </select>
            </td>
            <th>現況</th>
            <td>
                <select class="form-control" name="situation">
                    <option value="">請選擇</option>
                    <option value="學生" @if($userMeta->situation == '學生') selected @endif>學生</option>
                    <option value="待業" @if($userMeta->situation == '待業') selected @endif>待業</option>
                    <option value="休學" @if($userMeta->situation == '休學') selected @endif>休學</option>
                    <option value="打工" @if($userMeta->situation == '打工') selected @endif>打工</option>
                    <option value="上班族" @if($userMeta->situation == '上班族') selected @endif>上班族</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>關於我</th>
            <td colspan='3'><textarea class="form-control m-input" type="textarea" id="about" name="about" rows="3" maxlength="300">{{ $userMeta->about }}</textarea></td>
            <th>期待的約會模式</th>
            <td colspan='3'><textarea class="form-control m-input" type="textarea" name="style" rows="3" maxlength="300">{{ $userMeta->style }}</textarea></td>
        </tr>
        <tr>
            <th>教育</th>
            <td>
                <select class="form-control" name="education">
                    <option value="">請選擇</option>
                    <option value="國中" @if($userMeta->education == '國中') selected @endif>國中</option>
                    <option value="高中" @if($userMeta->education == '高中') selected @endif>高中</option>
                    <option value="大學" @if($userMeta->education == '大學') selected @endif>大學</option>
                    <option value="研究所" @if($userMeta->education == '研究所') selected @endif>研究所</option>
                </select>
            </td>
            <th>婚姻</th>
            <td>
                <select class="form-control" name="marriage">
                    <option value="">請選擇</option>
                    <option value="已婚" @if($userMeta->marriage == '已婚') selected @endif>已婚</option>
                    <option value="分居" @if($userMeta->marriage == '分居') selected @endif>分居</option>
                    <option value="單身" @if($userMeta->marriage == '單身') selected @endif>單身</option>
                    @if($user->engroup==2)
                    <option value="有男友" @if($userMeta->marriage == '有男友') selected @endif>有男友</option>
                    @else
                    <option value="有女友" @if($userMeta->marriage == '有女友') selected @endif>有女友</option>
                    @endif
                </select>
            </td>
            <th>喝酒</th>
            <td>
                <select class="form-control" name="drinking">
                    <option value="">請選擇</option>
                    <option value="不喝" @if($userMeta->drinking == '不喝') selected @endif>不喝</option>
                    <option value="偶爾喝" @if($userMeta->drinking == '偶爾喝') selected @endif>偶爾喝</option>
                    <option value="常喝" @if($userMeta->drinking == '常喝') selected @endif>常喝</option>
                </select>
            </td>
            <th>抽菸</th>
            <td>
                <select class="form-control" name="smoking">
                    <option value="">請選擇</option>
                    <option value="不抽" @if($userMeta->smoking == '不抽') selected @endif>不抽</option>
                    <option value="偶爾抽" @if($userMeta->smoking == '偶爾抽') selected @endif>偶爾抽</option>
                    <option value="常抽" @if($userMeta->smoking == '常抽') selected @endif>常抽</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>產業1</th>
            <td>
                <select class="form-control" name="domainType" id="domainType" onchange="setDomain(0);">
                    <option value="">請選擇</option>
                    <option value="資訊科技" @if($userMeta->domainType == '資訊科技') selected @endif>資訊科技</option>
                    <option value="傳產製造" @if($userMeta->domainType == '傳產製造') selected @endif>傳產製造</option>
                    <option value="工商服務" @if($userMeta->domainType == '工商服務') selected @endif>工商服務</option>
                    <option value="民生服務" @if($userMeta->domainType == '民生服務') selected @endif>民生服務</option>
                    <option value="文教傳播" @if($userMeta->domainType == '文教傳播') selected @endif>文教傳播</option>
                </select>
            </td>
            <th>產業2</th>
            <td>
                <select class="form-control" name="domain" id="domain">
                    @if(isset($userMeta->domain))
                    <option value="{{ $userMeta->domain }}" selected>{{ $userMeta->domain }}</option>
                    @else
                    <option value="" selected>請選擇</option>
                    @endif
                </select>
            </td>
            <th>封鎖的產業1</th>
            <td>{{ $userMeta->blockdomainType }}</td>
            <th>封鎖的產業2</th>
            <td>{{ $userMeta->blockdomain }}</td>
        </tr>
        <tr>
            <th>職業</th>
            <td>
                <select class="form-control" name="occupation">
                    <option value="">請選擇</option>
                    <option value="學生" @if($userMeta->occupation == '學生') selected @endif>學生</option>
                    <option value="無業" @if($userMeta->occupation == '無業') selected @endif>無業</option>
                    <option value="人資" @if($userMeta->occupation == '人資') selected @endif>人資</option>
                    <option value="業務銷售" @if($userMeta->occupation == '業務銷售') selected @endif>業務銷售</option>
                    <option value="行銷企劃" @if($userMeta->occupation == '行銷企劃') selected @endif>行銷企劃</option>
                    <option value="行政助理" @if($userMeta->occupation == '行政助理') selected @endif>行政助理</option>
                    <option value="專案管理" @if($userMeta->occupation == '專案管理') selected @endif>專案管理</option>
                    <option value="餐飲類服務業" @if($userMeta->occupation == '餐飲類服務業') selected @endif>餐飲類服務業</option>
                    <option value="旅遊類服務業" @if($userMeta->occupation == '旅遊類服務業') selected @endif>旅遊類服務業</option>
                    <option value="美容美髮美甲芳療" @if($userMeta->occupation == '美容美髮美甲芳療') selected @endif>美容美髮美甲芳療</option>
                    <option value="操作員" @if($userMeta->occupation == '操作員') selected @endif>操作員</option>
                    <option value="文字工作者" @if($userMeta->occupation == '文字工作者') selected @endif>文字工作者</option>
                    <option value="學術研究" @if($userMeta->occupation == '學術研究') selected @endif>學術研究</option>
                    <option value="教育輔導" @if($userMeta->occupation == '教育輔導') selected @endif>教育輔導</option>
                    <option value="金融營業交易" @if($userMeta->occupation == '金融營業交易') selected @endif>金融營業交易</option>
                    <option value="財務會計" @if($userMeta->occupation == '財務會計') selected @endif>財務會計</option>
                    <option value="總機秘書櫃檯" @if($userMeta->occupation == '總機秘書櫃檯') selected @endif>總機秘書櫃檯</option>
                    <option value="法務記帳代書" @if($userMeta->occupation == '法務記帳代書') selected @endif>法務記帳代書</option>
                    <option value="資訊軟體" @if($userMeta->occupation == '資訊軟體') selected @endif>資訊軟體</option>
                    <option value="客服" @if($userMeta->occupation == '客服') selected @endif>客服</option>
                    <option value="貿易船務" @if($userMeta->occupation == '貿易船務') selected @endif>貿易船務</option>
                    <option value="交通運輸物流" @if($userMeta->occupation == '交通運輸物流') selected @endif>交通運輸物流</option>
                    <option value="倉管採購" @if($userMeta->occupation == '倉管採購') selected @endif>倉管採購</option>
                    <option value="設計美術" @if($userMeta->occupation == '設計美術') selected @endif>設計美術</option>
                    <option value="模特演員" @if($userMeta->occupation == '模特演員') selected @endif>模特演員</option>
                    <option value="傳播藝術" @if($userMeta->occupation == '傳播藝術') selected @endif>傳播藝術</option>
                    <!--  <option value="1" @if($userMeta->job == '1') selected @endif>其他(自填)</option> -->
                </select>
            </td>
            <th>資產</th>
            <td><input class="form-control m-input" name="assets" value="{{$userMeta->assets}}"></td>
            <th>年收</th>
            <td>
                <select class="form-control" name="income">
                    <option value="">請選擇</option>
                    <option value="50萬以下" @if($userMeta->income == '50萬以下') selected @endif>50萬以下</option>
                    <option value="50~100萬" @if($userMeta->income == '50~100萬') selected @endif>50~100萬</option>
                    <option value="100-200萬" @if($userMeta->income == '100-200萬') selected @endif>100-200萬</option>
                    <option value="200-300萬" @if($userMeta->income == '200-300萬') selected @endif>200-300萬</option>
                    <option value="300萬以上" @if($userMeta->income == '300萬以上') selected @endif>300萬以上</option>
                </select>
            </td>
            <th>信息通知</th>
            <td>
                <select class="form-control" name="notifmessage">
                    <option value="收到即通知" @if($userMeta->notifmessage == '收到即通知') selected @endif>收到即通知</option>
                    <option value="每天通知一次" @if($userMeta->notifmessage == '每天通知一次') selected @endif>每天通知一次</option>
                    <option value="不通知" @if($userMeta->notifmessage == '不通知') selected @endif>不通知</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>隱藏地區</th>
            <td>
                <input type="hidden" name="isHideArea" value="0">
                <input type="checkbox" name="isHideArea" @if($userMeta->isHideArea == 1) checked @endif value="1">
            </td>
            <th>隱藏罩杯</th>
            <td>
                <input type="hidden" name="isHideCup" value="0">
                <input type="checkbox" name="isHideCup" @if($userMeta->isHideCup == 1) checked @endif value="1">
            </td>
            <th>隱藏體重</th>
            <td>
                <input type="hidden" name="isHideWeight" value="0">
                <input type="checkbox" name="isHideWeight" @if($userMeta->isHideWeight == 1) checked @endif value="1">
            </td>
            <th>隱藏職業</th>
            <td>
                <input type="hidden" name="isHideOccupation" value="0">
                <input type="checkbox" name="isHideOccupation" @if($userMeta->isHideOccupation == 1) checked @endif value="1">
            </td>
        </tr>
        <tr>
            @if($user->engroup==2)
            <th>包養關係</th>
            <td>
                @php
                    $exchange_period_name = DB::table('exchange_period_name')->get();
                @endphp
                <select class="form-control" name="exchange_period">
                    @foreach($exchange_period_name as $row)
                    <option value="{{$row->id}}" @if($user->exchange_period==$row->id) selected @endif>{{$row->name}}</option>
                    @endforeach
                </select>
            </td>
            @endif
            <th>收件夾顯示方式</th>
            <td>
                <select class="form-control" name="notifhistory">
                    <option value="收到即通知" @if($userMeta->notifhistory == '收到即通知') selected @endif>收到即通知</option>
                    <option value="每天通知一次" @if($userMeta->notifhistory == '每天通知一次') selected @endif>每天通知一次</option>
                    <option value="不通知" @if($userMeta->notifhistory == '不通知') selected @endif>不通知</option>
                    <option value="顯示普通會員信件" @if($userMeta->notifhistory == '顯示普通會員信件') selected @endif>顯示普通會員信件</option>
                    <option value="顯示VIP會員信件" @if($userMeta->notifhistory == '顯示VIP會員信件') selected @endif>顯示VIP會員信件</option>
                    <option value="顯示全部會員信件" @if($userMeta->notifhistory == '顯示全部會員信件') selected @endif>顯示全部會員信件</option>
                </select>
            </td>
            <th>建立時間</th>
            <td>{{ $userMeta->created_at }}</td>
            <th>更新時間</th>
            <td>{{ $userMeta->updated_at }}</td>
        </tr>
        <tr>
            <th>站長註解</th>
            <td colspan='3'><textarea class="form-control m-input" type="textarea" id="adminNote" name="adminNote" rows="3" maxlength="300">{{ $userMeta->adminNote }}</textarea></td>
        </tr>
    </table>
    <button type='submit' class='text-white btn btn-primary'>儲存</button>
</form>
<br>
<form method="POST" action="/dashboard/header/true" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <input type="hidden" name="userId" value="{{$user->id}}">
    <table class="table table-hover table-bordered">
        <tr>
            <td>
                <label class="col-form-label twzip" for="image">變更頭像照</label>
            </td>
            <td>
                <label class="custom-file">
                    <input required type="file" id="image" class="custom-file-input" name="image" onchange="$(this).parent().children().last().text($(this).val().split('\\').pop());">
                    <span class="custom-file-control"></span>
                </label>
            </td>
            <td>
                <button type="submit" class="btn btn-success">上傳</button>&nbsp;&nbsp;
            </td>
        </tr>
    </table>
</form>

<form method="POST" action="/dashboard/image/true" enctype="multipart/form-data">
    {!! csrf_field() !!}
	<input type="hidden" name="userId" value="{{$user->id}}">
    <table class="table table-hover table-bordered">
        <tr>
            <td><label class="col-form-label twzip" for="images">新增生活照</label></td>
            <td class="input_field_weap">
                <label class="custom-file">
                    <input type="file" id="images" class="custom-file-input" name="images[]" onchange="$(this).parent().children().last().text($(this).val().split('\\').pop());">
                    <span class="custom-file-control"></span>
                </label>
                <button type="button" id="add_image" class="" name="button">+</button>
            </td>
            <td>
                <button id="image-submit" type="submit" class="btn btn-success upload-submit">上傳</button>&nbsp;&nbsp;
            </td>
        </tr>
    </table>
</form>

<form method="POST" action="/dashboard/image/true" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <input type="hidden" name="userId" value="{{$user->id}}">
    <table class="table table-hover table-bordered">
        <tr>
            <td><label class="col-form-label twzip" for="images">新增證件照</label></td>
            <td class="input_field_weap">
                <label class="custom-file">
                    <input type="hidden" name="picType" value="IDPhoto">
                    <input type="file" id="images" class="custom-file-input" name="images[]" onchange="$(this).parent().children().last().text($(this).val().split('\\').pop());">
                    <span class="custom-file-control"></span>
                </label>
                <button type="button" id="add_image" class="" name="button">+</button>
            </td>
            <td>
                <button id="image-submit" type="submit" class="btn btn-success upload-submit">上傳</button>&nbsp;&nbsp;
            </td>
        </tr>
    </table>
</form>

<h4>現有生活照</h4>
<?php $pics = \App\Models\MemberPic::getSelf($user->id); ?>
<table class="table table-hover table-bordered" style="width: 50%;">
    @forelse ($pics as $pic)
        <tr>
            <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/dashboard/imagedel/true">
                <td>
                    {!! csrf_field() !!}
                    <input type="hidden" name="userId" value="{{$user->id}}">
                    <input type="hidden" name="imgId" value="{{$pic->id}}">
                    <div style="width:400px">
                        <img src="{{$pic->pic}}" />
                    </div>
                </td>
                <td>
                    <button type="submit" class="btn btn-metal">刪除</button>
                </td>
            </form>
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
            <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="/dashboard/imagedel/true">
                <td>
                    {!! csrf_field() !!}
                    <input type="hidden" name="userId" value="{{$user->id}}">
                    <input type="hidden" name="imgId" value="{{$pic->id}}">
                    <div style="width:400px">
                        <img src="{{$pic->pic}}" />
                    </div>
                </td>
                <td>
                    <button type="submit" class="btn btn-metal">刪除</button>
                </td>
            </form>
        </tr>
    @empty
        此會員目前沒有證件照
    @endforelse
</table>
</body>
<script>
var domainJson = ({
	'請選擇': ['請選擇'],
	'資訊科技': ['軟體網路','電信通訊','光電光學','半導體業','電腦週邊','電子相關'],
	'傳產製造': ['食品飲料','紡織相關','鞋類紡織','家具家飾','紙製製造','印刷相關','化學製造','石油製造','橡膠塑膠','非金屬製造','金屬製造','機械設備','電力機械','運輸工具','儀器醫材','育樂用品','其他製造','物流倉儲','營建土木','農林漁牧','礦業土石'],
	'工商服務': ['法律服務','會計服務','顧問研發','人力仲介','租賃業','汽車維修','徵信保全'],
	'民生服務': ['批發零售','金融機構','投資理財','保險業','電影業','旅遊休閒','美容美髮','醫療服務','環境衛生','住宿服務','餐飲服務'],
	'文教傳播': ['教育服務','印刷出版','藝文相關','廣播電視','廣告行銷','政治社福']
});

setDomain(1);

function setDomain(initial) {
	var domain = eval(domainJson);
	var type = $("#domainType").val();
	//console.log('type is ' + type);
	if(!initial) {
		$("#domain option").remove();
		$("#domain").append('<option value="">請選擇</option>');
	}
	for (var i in domain[type]) {
		//console.log(domain[type][i]);
		if(domain[type][i] != $("#domain option:selected").val()) {
			$("#domain").append('<option value="' + domain[type][i] + '">' + domain[type][i] + '</option>');
			$("#domain").selectpicker('refresh');
		}
	}
}
$('.twzipcode').twzipcode({
	'detect': true, 'css': ['form-control twzip', 'form-control twzip', 'zipcode']
});
jQuery(document).ready(function(){
	jQuery("#m_datepicker_1").datepicker(
		{
			todayHighlight: !0,
			orientation: "bottom left",
			templates: {
				leftArrow: '<i class="la la-angle-left"></i>',
				rightArrow: '<i class="la la-angle-right"></i>'
			}
		}
	);
});

let wrapper         = $(".input_field_weap");
let add_button      = $("#add_image"); //Add button ID
$(add_button).click(function(e){ //on add input button click
    e.preventDefault();
        $(wrapper).append('<div><label class="custom-file"><input type="file" class="custom-file-input" name="images[]" onchange="$(this).parent().children().last().text($(this).val())"><span class="custom-file-control"></span></label><a href="#" class="remove_field">&nbsp;Remove</a></div>'); //add input box
});
$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
    e.preventDefault();
    $(this).parent('div').remove();
});

var ysel = document.getElementsByName("year")[0],
    msel = document.getElementsByName("month")[0],
    dsel = document.getElementsByName("day")[0],
    firstTime = 0;
for (var i = {{ date("Y") }}; i>=1930; i--){
    var opt = new Option();
    opt.value = opt.text = i;
    if(opt.value == {{ $year }}){
        opt.selected = true;
    }
    ysel.add(opt);
}
ysel.addEventListener("change",validate_date);
msel.addEventListener("change",validate_date);

function validate_date(){
    var y = +ysel.value, m = msel.value, d = dsel.value;
    if (m === "2") {
        var mlength = 28 + (!(y & 3) && ((y % 100) !== 0 || !(y & 15)));
    }
    else {
        var mlength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][m - 1];
    }
    dsel.length = 0;
    for(var i=1;i<=mlength;i++){
        var opt=new Option();
        opt.value = opt.text = i;
        if(i==d) {
            opt.selected=true;
        }
        if(opt.value == {{ $day }} && firstTime == 0){
            opt.selected = true;
            firstTime = 1;
        }
        dsel.add(opt);
    }
}
validate_date();
</script>