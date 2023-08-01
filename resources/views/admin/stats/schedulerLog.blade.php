@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>排程監控</h1>
        @if($data)
            <table>
                <tr>                    
                    @foreach ($data["headers"] as $header)
                        
                    @endforeach
                </tr>
                @foreach ($data["rows"] as $key => $value)
                    <tr>
                        <td>{{ $value['name'] }}</td>
                        <td>{{ $value['type'] }}</td>
                        <td>{{ $value['cron_expression'] }}</td>
                        <td>{{ $value['started_at'] }}</td>
                        <td>{{ $value['finished_at'] }}</td>
                        <td>{{ $value['failed_at'] }}</td>
                        <td>{{ $value['next_run'] }}</td>
                        <td>{{ $value['grace_time'] }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </body>
@stop