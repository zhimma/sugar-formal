@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>資料夾管理</h1>
        <br>
        <form action="{{ route('admin_item_folder_create') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input name="folder_name">
            <button type="submit" class="btn btn-primary">新增資料夾</button>
        </form>
        <table class='table table-bordered table-hover'>
            <tr>
                <th>資料夾</th>
                <th>連結</th>
                <th>操作</th>
            </tr>
            @foreach($folders as $folder)
                <tr>
                    <td>{{$folder->folder_name}}</td>
                    <td>
                        <form id="update_folder_{{$folder->id}}" action="{{ route('admin_item_folder_update') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="folder_id" value="{{$folder->id}}">
                            @foreach($admin_items as $item)
                                <input type="checkbox" name="items[]" value="{{$item->id}}" @if($folder->items->whereIn('item_id', $item->id)->first() ?? false) checked @endif>
                                <label>{{$item->title}}</label>
                                <br>
                            @endforeach
                        </form>
                    </td>
                    <td>
                        <button class="update_folder btn btn-primary">更新</button>
                        <br>
                        <br>
                        <form action="{{ route('admin_item_folder_delete') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="folder_id" value="{{$folder->id}}">
                            <button type="submit" class="btn btn-primary">刪除資料夾</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </body>
    <script>
        $('.update_folder').click(function() {
            $(this).parent('td').prev('td').children('form').first().submit();
        });
    </script>
@stop