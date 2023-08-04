@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>排程監控</h1>
        @if($data)
            <form method="post">
                @csrf
                <table class="table table-bordered table-hover center-block">
                    <tr>
                        @foreach ($data["headers"] as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                    @foreach ($data["rows"] as $key => $value)
                        <tr>
                            <td>{{ $value['name'] }}</td>
                            <td><textarea name="remark.{{ $value["id"] }}" cols="30" rows="5">{{ $value["remark"] }}</textarea></td>
                            <td>{{ $value['type'] }}</td>
                            <td>{{ $value['cron_expression'] }}</td>
                            <td>{{ $value['started_at'] }}</td>
                            <td class="text-success">{{ $value['finished_at'] }}</td>
                            <td class="text-danger bolder">{{ $value['failed_at'] }}</td>
                            <td>{{ $value['next_run'] }}</td>
                            <td>{{ $value['grace_time'] }}</td>
                        </tr>
                    @endforeach
                </table>
                <button type="submit" class="btn btn-primary">更新</button>
            </form>
        @else
            <p>無資料</p>
        @endif
    </body>
@stop