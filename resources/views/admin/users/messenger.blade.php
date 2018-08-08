@include('partials.header')
@yield("pre-javascript")
@include('partials.scripts')
@yield("javascript")
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
</style>
<body style="padding: 15px;">
@include('partials.errors')
@include('partials.message')
@if (isset($errors))
    @if ($errors->count() > 0)
    @else
        @if(!isset($msgs))
            <h1>發送站長訊息給{{ $user->name }}</h1>
            <form action="{{ route('admin/send', $user->id) }}" id='message' method='POST'>
                {!! csrf_field() !!}
                <input type="hidden" value="{{ $admin->id }}" name="admin_id">
                <textarea name="msg" class="form-control" cols="80" rows="5"></textarea><br>
                <button type='submit' class='text-white btn btn-primary'>送出</button>
            </form>
            <table class="table table-bordered table-hover">
                <tr>
                    <td>預設選項</td>
                    <td>
                        <div id="defaults">
                            <button class="btn btn-success">暱稱</button>
                            <button class="btn btn-success">標題</button>
                            <button class="btn btn-success">身高</button>
                            <button class="btn btn-success">職業</button>
                            <button class="btn btn-success">體重</button>
                            <button class="btn btn-success">罩杯</button>
                            <button class="btn btn-success">體型</button>
                            <button class="btn btn-success">標準</button>
                            <button class="btn btn-success">現況</button>
                            <button class="btn btn-success">關於我</button>
                            <button class="btn btn-success">期待的約會模式</button>
                            <button class="btn btn-success">教育</button>
                            <button class="btn btn-success">婚姻</button>
                            <button class="btn btn-success">喝酒</button>
                            <button class="btn btn-success">抽菸</button>
                            <button class="btn btn-success">產業1</button>
                            <button class="btn btn-success">封鎖的產業1</button>
                            <button class="btn btn-success">產業2</button>
                            <button class="btn btn-success">封鎖的產業2</button>
                            <button class="btn btn-success">職業</button>
                            <button class="btn btn-success">資產</button>
                            <button class="btn btn-success">年收</button>
                        </div>
                    </td>
                </tr>
            </table>
        @else
            <form action="{{ route('admin/send/multiple') }}" id='message' method='POST'>
                {!! csrf_field() !!}
                <input type="hidden" value="{{ $admin->id }}" name="admin_id">
                @foreach( $msgs as $msg )
                    <h3 style="text-align: left">發送給{{ $msg['name'] }}</h3>
                    <input type="hidden" value="{{ $msg['from_id'] }}" name="to[]">
                    <textarea name="msg[]" class="form-control" cols="80" rows="5">{{ $msg['name'] }}{{ $template['head'] }}{{ $msg['post_time'] }}{{ $template['body'] }}</textarea><br>
                @endforeach
                <button type='submit' class='text-white btn btn-primary'>送出</button>
            </form>
        @endif
    @endif
@endif
</body>
@if(!isset($msgs))
    <script>
        $(document).ready(
            $
        );
    </script>
@endif