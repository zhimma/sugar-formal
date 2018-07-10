@extends('layouts.website')

@section('app-content')

<div class="m-portlet__head">
<div class="m-portlet__head-caption">
    <div class="m-portlet__head-title">
        <h3 class="m-portlet__head-text">
       更改密碼 <small></small>
        </h3>
    </div>			
</div>
</div>
<div class="m-portlet__body">
<form method="POST" class="m-login__form m-form" action="/password/reset">
{!! csrf_field() !!}
<input type="hidden" name="token" value="{{ $token }}">

<div class="form-group m-form__group">
    <input class="form-control m-input" type="email" placeholder="帳號 (您的E-mail)" name="email" values="{{ old('email') }}" autocomplete="off">
</div>
<div class="form-group m-form__group">
        <input class="form-control m-input" type="password" placeholder="密碼" name="password">
                    </div>
                    <div class="form-group m-form__group">
                        <input class="form-control m-input m-login__form-input--last" type="password" placeholder="密碼確認" name="password_confirmation">
                    </div>
                    <div class="m-login__form-action">
                    <button type="submit" class="btn btn-danger m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">
                    更改密碼
                    </button>
                </div>
</form>
</div>

@stop