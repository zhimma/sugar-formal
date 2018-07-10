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
@include('partials.errors')
@include('partials.status')
    <form class="m-login__form m-form" method="POST" action="/password/email">
        {!! csrf_field() !!}

        <div class="form-group m-form__group">
            <input class="form-control m-input" type="email" placeholder="帳號 (您的E-mail)" name="email" values="{{ old('email') }}" autocomplete="off">
        </div>
        <div class="m-login__form-action">
            <button type="submit" class="btn btn-danger m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">
            更改密碼
            </button>
        </div>
    </form>
</div>

@stop
