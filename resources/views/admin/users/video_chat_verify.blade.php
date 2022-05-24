@extends('admin.main')
@section('app-content')
    <head>
    </head>
    
    <body style="padding: 15px;">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <h1>視訊驗證</h1>
        <button id="video_chat_switch_on" class="btn" style="background-color: #e7e7e7; color: black; cursor: default; ">ON</button>
        <button id="video_chat_switch_off" class="btn" style="background-color: #f44336; color: white; cursor: not-allowed;">OFF</button>
        <br>
        <br>
        <div class="row">
            <div id="app">
                <video-chat 
                    :allusers="{{ $users }}" 
                    :authUserId="{{ auth()->id() }}" 
                    user_permission = "admin"
                    turn_url="{{ env('TURN_SERVER_URL') }}"
                    turn_username="{{ env('TURN_SERVER_USERNAME') }}" 
                    turn_credential="{{ env('TURN_SERVER_CREDENTIAL') }}" 
                />
            </div>
        </div>
    </body>

    <script>
        var vm = new Vue({
                
            });
        $('#video_chat_switch_on').on('click',function(){
            $(this).css({
                'background-color': '#4CAF50',
                'color': 'white',
                'cursor': 'not-allowed'
            });
            $('#video_chat_switch_off').css({
                'background-color': '#e7e7e7',
                'color': 'black',
                'cursor': 'default'
            });
            vm.$mount("#app");
        });
        
        $('#video_chat_switch_off').on('click',function(){
            window.location.reload();
        });

        
    </script>
@stop