@extends('layouts.master')

@section('app-content')

    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    VIP
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
                                    升級VIP失敗
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <h3>VIP 升級失敗，請詳閱錯誤訊息</h3>
                    </div>
                </div>
            </div>
        </div>

    </div>


@stop

@section('javascript')

    <script>
        $(document).ready(function () {

        });
    </script>

@stop
