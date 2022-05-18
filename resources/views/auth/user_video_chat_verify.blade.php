@extends('new.layouts.website')
@section('app-content')

    <div class="container matop70">
        <div class="row">
            <div id="app">
                <video-chat 
                    :allusers="{{ $users }}" 
                    :authUserId="{{ auth()->id() }}" 
                    user_permission = "normal"
                    turn_url="{{ env('TURN_SERVER_URL') }}"
                    turn_username="{{ env('TURN_SERVER_USERNAME') }}" 
                    turn_credential="{{ env('TURN_SERVER_CREDENTIAL') }}" 
                />
            </div>
        </div>
    </div>
    <script>
        new Vue({
            el:'#app'
        });
    </script>
@stop