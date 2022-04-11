@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>進階資訊統計工具</h1>
        <br>
        <form id='form' method='get' action="{{route('users/informationStatistics')}}">
            <table class="table-bordered table-hover center-block text-center" style="width: 100%;" id="table">
                <tr>
                    <th class="text-center">時間區段(天)</th>
                    <th class="text-center">比例(%)</th>
                    <th class="text-center">性別</th>
                    <th class="text-center">是否包含封鎖帳戶使用者</th>
                    <th class="text-center">是否包含關閉帳戶使用者</th>
                </tr>
                <tr class="template">
                        <td>
                            <input type="text" name="days" value={{$form_condition['days']}}></input> 天
                        </td>
                        <td>
                            <input type="text" name="percentage" value={{$form_condition['percentage']}}></input> %
                        </td>
                        <td>
                            <input type="radio" name="sex" value=0 @if($form_condition['sex']==0) checked @endif> 全部 </input>
                            <input type="radio" name="sex" value=1 @if($form_condition['sex']==1) checked @endif> 男 </input>
                            <input type="radio" name="sex" value=2 @if($form_condition['sex']==2) checked @endif> 女 </input>
                        </td>
                        <td>
                            <input type="radio" name="include_banned_user" value=1 @if($form_condition['include_banned_user']==1) checked @endif> 是 </input>
                            <input type="radio" name="include_banned_user" value=0 @if($form_condition['include_banned_user']==0) checked @endif> 否 </input>
                        </td>
                        <td>
                            <input type="radio" name="include_closed_user" value=1 @if($form_condition['include_closed_user']==1) checked @endif> 是 </input>
                            <input type="radio" name="include_closed_user" value=0 @if($form_condition['include_closed_user']==0) checked @endif> 否 </input>
                        </td>
                </tr>
            <table>
            <br>
            <button type='submit'>查詢</button>
        </form>
        <br>
        <br>
        <table class='table table-bordered table-hover'>
            <tr>
                <td> 上線總人數 </td>
                <td> {{$statistics_data['login_member_count']}} 人 </td>
                <td> 付費VIP總人數 </td>
                <td> {{$statistics_data['all_pay_vip_count']}} 人 </td>
                <td> 被封鎖總人數 </td>
                <td> {{$statistics_data['all_be_blocked_count']}} 人 </td>
                <td> 封鎖他人總人數 </td>
                <td> {{$statistics_data['all_block_other_count']}} 人 </td>
                <td> 付出車馬費總人數 </td>
                <td> {{$statistics_data['all_pay_tip_count']}} 人 </td>
                <td> 接收車馬費總人數 </td>
                <td> {{$statistics_data['all_receive_tip_count']}} 人 </td>
            <tr>
        </table>
        <table class='table table-bordered table-hover'>
            <tr>
                <td> 統計項目 </td>
                <td> 符合人數 </td>
                <td> 佔上線總人數比例 </td>
                <td> 其他 </td>
            </tr>
            <tr>
                <td> 付費VIP統計 </td>
                <td> {{$statistics_data['pay_vip_count']}} </td>
                <td> {{$statistics_data['pay_vip_percentage']}} % </td>
                <td> 最高VIP月份數 : {{$statistics_data['max_pay_vip_month']}} </td>
            </tr>
            <tr>
                <td> 被其他使用者封鎖統計 </td>
                <td> {{$statistics_data['be_blocked_count']}} </td>
                <td> {{$statistics_data['be_blocked_percentage']}} % </td>
                <td> 最高被封鎖次數 : {{$statistics_data['max_be_blocked_count']}} </td>
            </tr>
            <tr>
                <td> 封鎖其他使用者統計 </td>
                <td> {{$statistics_data['block_other_count']}} </td>
                <td> {{$statistics_data['block_other_percentage']}} % </td>
                <td> 最高封鎖次數 : {{$statistics_data['max_block_other_count']}} </td>
            </tr>
            <tr>
                <td> 付出車馬費統計 </td>
                <td> {{$statistics_data['pay_tip_count']}} </td>
                <td> {{$statistics_data['pay_tip_percentage']}} % </td>
                <td> 最高付出車馬費次數 : {{$statistics_data['max_pay_tip_count']}} </td>
            </tr>
            <tr>
                <td> 接收車馬費統計 </td>
                <td> {{$statistics_data['receive_tip_count']}} </td>
                <td> {{$statistics_data['receive_tip_percentage']}} % </td>
                <td> 最高接收車馬費次數 : {{$statistics_data['max_receive_tip_count']}} </td>
            </tr>
        </table>
    </body>
@stop