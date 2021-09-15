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
    </style>
    <body style="padding: 15px;">
    <h1>新增站長公告</h1>
    <form action="{{ route('admin/announcement/new') }}" method="post">
        {!! csrf_field() !!}
        <table class="table-bordered table-hover center-block text-center" id="table">
            <tr>
                <th class="text-center">內容</th>
                <th class="text-center">性別</th>
                <th class="text-center">普通會員/VIP</th>
                <th class="text-center">排序(預設為1)</th>
                <th class="text-center">在第幾次login時才跳出此公告</th>
                <th class="text-center">操作</th>
            </tr>
            <tr class="template">
                <td>
                    <textarea name="content_word" id="" cols="80" rows="10">公告內容</textarea>
                </td>
                <td>
                    <select name="en_group" id="">
                        <option value="1">男</option>
                        <option value="2">女</option>
                    </select>
                </td>
                <td>
                    <select name="isVip" id="">
                        <option value="0">普通會員</option>
                        <option value="1">VIP</option>
                    </select>
                    <div class="is_new_7_block">
                        <input type="checkbox" name="is_new_7" value="1"/> 新進
                    </div>                    
                </td>
                <td>
                    <input type="number" name="sequence" min="1" value="1">
                </td>
                <td>
                    <input type="number" name="login_times_alert" min="1" value="10">
                </td>
                <td>
                    <input type="submit" class='text-white btn btn-success' value="送出">
                    <input type="reset"  class='text-white btn btn-danger' value="復原">
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
