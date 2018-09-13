@extends('layouts.master')

@section('app-content')
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 style="text-align:left;" class="m-portlet__head-text">
                    檢舉照片 - 請填寫理由
                </h3>
                <span style="text-align:right;" class="m-portlet__head-text">

                <a class="btn btn-danger m-btn m-btn--air m-btn--custom" href="history.back()"> 回去會員資料</a>
            </span>
            </div>
        </div>
    </div>
    <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('reportPicNext') }}">
        {!! csrf_field() !!}
        <input type="hidden" name="reporter_id" value="{{ $reporter_id }}">
        <input type="hidden" name="reported_pic_id" value="{{ $reported_pic_id }}">
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="col-lg-9">
                    <textarea class="form-control m-input" rows="4" id="content" required name="content" maxlength="500"></textarea>
                </div>
            </div>

            <div class="m-form__actions">
                <div class="row">
                    <div class="col-lg-9">
                        <button id="msgsnd" type="submit" class="btn btn-danger m-btn m-btn--air m-btn--custom">送出檢舉</button>&nbsp;&nbsp;
                        <button type="reset" class="btn btn-outline-danger m-btn m-btn--air m-btn--custom">取消</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


@stop

@section('javascript')


@stop
