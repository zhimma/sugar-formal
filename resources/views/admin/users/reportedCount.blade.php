@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
    <h1>會員被檢舉次數</h1>
    <table class="table-hover table table-bordered">
        <tr>
            <td>會員</td>
            <td>檢舉者(檢舉次數)</td>
        </tr>
        @foreach( $users as $keys => $user)
        <tr>
            <td>
                @if( isset($vips[$keys]))
                        <i class="m-nav__link-icon fa fa-diamond"></i>
                @endif
               {{ $user['name'] }}({{ $keys }})
            </td>
            <td>
                @if(isset($msgs[$keys]))
                    @foreach($msgs[$keys] as $key => $message)
                    @if( isset($vips[$key]))
                        <i class="m-nav__link-icon fa fa-diamond"></i>
                    @endif
                    @if(isset($users[$key]))      
                        {{$users[$key]['name'] }} ({{ $message}})  
                    @endif         
                    @endforeach
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</body>
@stop