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
        <table class='table table-bordered table-hover'>
            <tr>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                </td>
            </tr>
        </table>
    </body>
@stop