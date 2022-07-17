@extends('admin.main')
@section('app-content')

    <head>
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
            crossorigin="anonymous"></script>
    </head>

    <body style="padding: 15px;">
        <h1>Feature Flags</h1>
        <button type="button" class="btn btn-primary"><a href="/admin/global/feature_flags/create" style="color:white">create feature flags</a></button>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">鍵值 / FEATURE KEY</th>
                        <th scope="col">狀態 / STATUS</th>
                        <th scope="col">啟動時間 / ACTIVE TIME</th>
                        <th scope="col">優先級 / PRIORITY</th>
                        <th scope="col">最後更新時間 / LAST UPDATED TIME</th>
                        <th scope="col">操作 / MANIPULATION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($features as $key => $row)
                        <tr class="row-feature">
                            <input type="hidden" class="feature_id" name="feature_id" value="{{ $row['id'] }}" />
                            <td class="feature">{{ $row['feature'] }}</td>
                            <td><input type="checkbox" {{$row['active_at'] != null ? 'checked':''}} class="feature-toggle"></td>
                            <td>{{ $row['active_at']!=null ? date('Y-m-d H:i:s', strtotime($row['active_at'])): 'Active Off' }}</td>
                            <td>{{json_decode($row['description']) != null ? json_decode($row['description'])->priority : ''}}</td>
                            <td>{{ date('Y-m-d H:i:s', strtotime($row['updated_at'])) }}</td>
                            <td><button type="button" class="btn btn-success edit"><a href="/admin/global/feature_flags/edit/{{$row['id']}}" style="color:white">編輯</a></button><button type="button" class="btn btn-danger delete">刪除</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
              $(".feature-toggle").click(function(){  
                console.log('111');
                let feature_id = $(this).parent().parent().find(".feature_id").val();
                let status = $(this).is(":checked");
                $.ajax({
                    url: "/admin/global/feature_flags/update",
                    type: 'POST',
                    data: {
                        "feature_id": feature_id,
                        "status": status,
                        "_token":"{{csrf_token()}}"
                    },
                    success: function(data) {
                        
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                  
                    }
                });
              })

              $(".delete").click(function(){  
                let feature_id = $(this).parent().parent().find(".feature_id").val();
                if (confirm("確定刪除嗎") == true) {
                    $.ajax({
                        url: "/admin/global/feature_flags/delete",
                    
                        type: 'POST',
                        data: {
                            "feature_id": feature_id,
                            "_token":"{{csrf_token()}}"
                        },
                        success: function(data) {
                            let dataArr = JSON.parse(data);
                            alert(dataArr['message']);
                            window.location.reload();
                        }
                    });
                }else{
                    alert('刪除失敗');
                }
              })
            </script>
    </body>
@stop
