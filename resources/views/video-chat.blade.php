@extends('new.layouts.website')
@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            
            <div id="app">
                <video-chat :allusers="{{ $users }}" :authUserId="{{ auth()->id() }}" turn_url="{{ env('TURN_SERVER_URL') }}"
                    turn_username="{{ env('TURN_SERVER_USERNAME') }}" turn_credential="{{ env('TURN_SERVER_CREDENTIAL') }}" />
            </div>
        <div>
    <div>
    <script>
        var data = {
            message:'Hello World!'
        }
        new Vue({
            el:'#app',
            data:data
        });
    </script>
@stop