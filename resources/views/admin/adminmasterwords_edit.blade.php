@extends('admin.main')
@section('app-content')
    <style>
        .table > tbody > tr > td, .table > tbody > tr > th{
            vertical-align: middle;
        }
        h3{
            text-align: left;
        }
    </style>
    <body style="padding: 15px;">
    <h1>修改站長公告</h1>
    <form action="{{ route('admin/masterwords/save', $masterwords->id) }}" method="post">
        {!! csrf_field() !!}
        <table class="table-bordered table-hover center-block text-center" id="table">
            <tr>
                <th class="text-center">內容</th>
                <th class="text-center">性別</th>
                <th class="text-center">排序(預設為1)</th>
                <th class="text-center">建立時間</th>
                <th class="text-center">更新時間</th>
                <th class="text-center">操作</th>
            </tr>
            <tr class="template">
                <td>
                    <textarea name="content_word" id="" cols="80" rows="10">{{ $masterwords->content }}</textarea>
                </td>
                <td>
                    <select name="en_group" id="">
                        <option value="1" @if($masterwords->en_group == 1) selected @endif>男</option>
                        <option value="2" @if($masterwords->en_group == 2) selected @endif>女</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="sequence" min="1" value="{{ $masterwords->sequence }}">
                </td>
                <td class="created_at">{{ $masterwords->created_at }}</td>
                <td class="updated_at">{{ $masterwords->updated_at }}</td>
                <td>
                    <input type="hidden" name="id" value="{{ $masterwords->id }}">
                    <input type="submit" class='text-white btn btn-success' value="送出">
                    <input type="reset"  class='text-white btn btn-danger' value="復原">
                </td>
            </tr>
        </table>
    </form>
    <a href="{{ route('admin/masterwords') }}" class="text-white btn btn-primary">返回</a>
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
