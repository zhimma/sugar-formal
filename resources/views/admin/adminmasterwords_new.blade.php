@extends('admin.main')
@section('app-content')
    <style>
        .table > tbody > tr > td, .table > tbody > tr > th{
            vertical-align: middle;
        }
        h3{
            text-align: left;
        }
        .cvvs_box {margin-top:5px;}  
    </style>
    <body style="padding: 15px;">
    <h1>新增站長的話</h1>
    <p>變數設定說明： line加入好友圖示 LINE_ICON</p>    
    <form action="{{ route('admin/masterwords/new') }}" method="post">
        {!! csrf_field() !!}
        <table class="table-bordered table-hover center-block text-center" id="table">
            <tr>
                <th class="text-center">內容</th>
                <th class="text-center">性別</th>
                <th class="text-center">排序(預設為1)</th>
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
                    <input type="number" name="sequence" min="1" value="1">
                </td>
                <td>
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
