@extends('dashboard')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h1>Password</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/user/password">
                {!! csrf_field() !!}

                <div class="raw-margin-top-24">
                    @input_maker_label('Old Password')
                    @input_maker_create('old_password', ['type' => 'password', 'placeholder' => 'Old Password'])
                </div>

                <div class="raw-margin-top-24">
                    @input_maker_label('New Password')
                    @input_maker_create('new_password', ['type' => 'password', 'placeholder' => 'New Password'])
                </div>

                <div class="raw-margin-top-24">
                    @input_maker_label('Confirm Password')
                    @input_maker_create('new_password_confirmation', ['type' => 'password', 'placeholder' => 'Confirm Password'])
                </div>

                <div class="raw-margin-top-24">
                    <a class="btn btn-default pull-left" href="{{ URL::previous() }}">取消</a>
                    <button class="btn btn-primary pull-right" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>

@stop
