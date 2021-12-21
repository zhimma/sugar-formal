@extends('admin.main')
@section('app-content')
    <style>
        .table > tbody > tr > td, .table > tbody > tr > th{
            vertical-align: middle;
        }
        h3{
            text-align: left;
        }
        
        .is_new_block {position:relative;top:20px;color:black;font-weight:normal;}
        .cvvs_box {margin-top:5px;}
        .ainput {width:80px;}
    </style>
    <body style="padding: 15px;">
    <h1>修改站長公告</h1>
    <p>變數設定說明： line加入好友圖示 LINE_ICON</p>   
    <form action="{{ route('admin/announcement/save', $announce->id) }}" method="post">
        {!! csrf_field() !!}
        <table class="table-bordered table-hover center-block text-center" id="table">
            <tr>
                <th class="text-center">內容</th>
                <th class="text-center">性別</th>
                <th class="text-center" nowrap>普通會員<br>/VIP</th>
                <th class="text-center" nowrap>排序(預設為1)</th>
                <th class="text-center">在第幾次login時才跳出此公告</th>
                <th class="text-center">建立時間</th>
                <th class="text-center">更新時間</th>
                <th class="text-center">操作</th>
            </tr>
            <tr class="template">
                <td>
                    <textarea name="content_word" id="" cols="80" rows="10">{{ $announce->content }}</textarea>
                </td>
                <td>
                    <select name="en_group" id="">
                        <option value="1" @if($announce->en_group == 1) selected @endif>男</option>
                        <option value="2" @if($announce->en_group == 2) selected @endif>女</option>
                    </select>
                </td>
                <td>
                    <select name="isVip" id="">
                        <option value="0" @if($announce->isVip == 0) selected @endif>普通會員</option>
                        <option value="1" @if($announce->isVip == 1) selected @endif>VIP</option>
                    </select>
                    <div class="is_new_block">
                        <input type="checkbox" name="is_new_7" value="1" @if($announce->is_new_7 == 1) checked @endif /> 未滿7天
                    </div>
                </td>
                <td>
                    <input type="number" class="ainput" name="sequence" min="1"  value="{{ $announce->sequence }}">
                </td>
                <td>
                    <input type="number" class="ainput" name="login_times_alert" min="1" value="{{ $announce->login_times_alert }}">
                </td>
                <td class="created_at">{{ $announce->created_at }}</td>
                <td class="updated_at">{{ $announce->updated_at }}</td>
                <td>
                    <input type="hidden" name="id" value="{{ $announce->id }}">
                    <input type="submit" class='text-white btn btn-success' value="送出">
                    <input type="reset"  class='text-white btn btn-danger' value="復原">
                    <div class="cvvs_box">
                        <input type="submit" name="convert_first" class='text-white btn btn-success' value="先轉換變數再送出" onclick="return confirm('轉換變數可將變數轉換成html，所以可以用html修改及調整高度、寬度...等外觀設定，但會失去簡潔的變數形式');">
                    </div>
                </td>
            </tr>
        </table>
    </form>
    <a href="{{ route('admin/announcement') }}" class="text-white btn btn-primary">返回</a>
    </body>
    <script>
        function setForm(td, type) {
            console.log(type);
            if(type === 'edit'){
                let type = td.getElementsByClassName('type')[0];
                type.value = "edit";
            }
            else if(type === 'delete'){
                let type = td.getElementsByClassName('type')[0];
                type.value = "delete";
            }
        }
        function submitForm() {
            var allInputs = myForm.getElementsByTagName('input');

            for (var i = 0; i < allInputs.length; i++) {
                var input = allInputs[i];

                if (input.name && !input.value) {
                    input.name = '';
                }
            }
            return true;
        }
    </script>
@stop
