@extends('layouts.master')
@section('app-content')

<div class="m-portlet__head">
    <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
            <h3 class="m-portlet__head-text">
                升級 VIP
            </h3>
        </div>
    </div>
</div>

<div class="m-portlet__body">
    <div class="row">
        <div class="col-lg-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                網站專屬 Vip
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <h3>價格: 888 $NTD / 每月</h3><br>
                    <form class="m-form m-form--fit" action="{{ route('creditPayment') }}" method=post>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                        <input type="hidden" name="userId" value="{{$user->id}}">
                        <div class="m-form__actions">
                        <div class="row">
                            <div class="col-9">
                                <button type="submit" class="btn btn-danger m-btn m-btn--air m-btn--custom">購買</button>&nbsp;&nbsp;
                                <img src="/img/cclogos.jpg" style="width: 50%; margin-bottom: 0">
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
    @if(!$user->isVip() && $user->engroup == 2)
        <div class="row">
            <div class="col-lg-6">
                <div class="m-portlet m-portlet--mobile">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    方案二
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <h3>說明</h3>
                        <h5>上傳大頭貼 + 三張生活照，即可試用幾天VIP</h5>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-9">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@stop
@section('javascript')
<script>
    $(document).ready(function(){

    });
</script>
@stop

