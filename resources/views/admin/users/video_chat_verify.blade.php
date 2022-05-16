@extends('admin.main')
@section('app-content')
    <head>
    </head>
    <body style="padding: 15px;">
        <h1>視訊驗證</h1>
        <br>
        <div class="row">
            <div id="app">
                <video-chat 
                    :allusers="{{ $users }}" 
                    :authUserId="{{ auth()->id() }}" 
                    turn_url="{{ env('TURN_SERVER_URL') }}"
                    turn_username="{{ env('TURN_SERVER_USERNAME') }}" 
                    turn_credential="{{ env('TURN_SERVER_CREDENTIAL') }}" 
                />
            </div>
        </div>
    </body>

    <script>
        new Vue({
            el:'#app'
        });
    </script>
@stop