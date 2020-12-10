@extends('admin.main')
@section('app-content')
<style>
    .table > tbody > tr > td, .table > tbody > tr > th{
        vertical-align: middle;
    }
</style>
<body style="padding: 15px;">
<h1>{{ is_null($account) ? '' : $account->name }} 關閉帳號詳細原因</h1>
<br>
<span>目前統計：共有 {{ count($data) }} 筆關閉次數</span>
<table class='table table-bordered table-hover'>
	<tr>
        <td width="12%">關閉時間</td>
        <td width="12%">關閉原因</td>
        <td width="7%">檢舉此帳號</td>
        <td width="25%">檢舉證據</td>
        <td width="22%">說明</td>
        <td width="11%">介面設計不優的頁面</td>
        <td width="11%">載入速度太慢的頁面</td>
	</tr>
	@forelse($data as $account)
    <tr>
        <td>{{ $account->created_at }}</td>
        <td>
            @if($account->reasonType == '1')
                遇到騷擾/八大
            @elseif ($account->reasonType == '2')
                網站介面操作不滿意
            @elseif ($account->reasonType == '3')
                已找到長期穩定對象
            @elseif ($account->reasonType == '4')
                其他原因
            @endif
        </td>
        <td>
            @foreach(explode(',',$account->reported_id) as $reportId)
                <a href="/admin/users/advInfo/{{$reportId}}">{{$reportId}}</a><br>
            @endforeach
        </td>
        <td>
            @if($account->reasonType == '1')
                @if(is_array(json_decode($account->image)))
                    @foreach(json_decode($account->image) as $pic)
                    <a href="{{ $pic }}"><img src="{{ $pic }}" style="width: 150px; height: 100px"></a>
                    @endforeach
                @else
                    <a href="{{ $account->image }}"><img src="{{ $account->image }}" style="width: 150px; height: 100px"></a>
                @endif
            @endif
        </td>
        <td style="word-break:break-all;">
            {{ $account->reasonType == 2 ?  $account->remark1 : $account->content}}
        </td>
        @php
            $reasonContent = json_decode($account->content);
            $design = [];
            $slow = [];
            foreach ((array)$reasonContent as $key =>$value){
                $test1 = explode('-',$value);
                if($test1[0] == '介面設計不美觀'){
                    $design[] =$test1[1];
                }else if ($test1[0] == '載入速度太慢'){
                    $slow[] =$test1[1];
                }
            }
            $design = implode(' ,',$design);
            $slow = implode(' ,',$slow);
        @endphp
        <td>{{ $design }}</td>
        <td>{{ $slow }}</td>
    </tr>
    @empty
    <tr>
        找不到資料
    </tr>
    @endforelse
</table>
</body>
@stop