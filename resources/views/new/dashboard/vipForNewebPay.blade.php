@extends('new.layouts.website')

@section('app-content')
    <style>
        .viplist ul li{
            margin: 0 auto !important;
            float: unset;
            width: 180px !important;
        }
        .vipcent{
            line-height: 35px;
            margin-top: unset;
            font-size: 16px !important;
        }
        .new_fa{
            width: 70%;
        }
    </style>
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="g_password">
                    <div class="g_pwicon">
                        <li><a href="{!! url('dashboard') !!}" class="g_pwicon_t "><span>基本資料</span></a></li>
                        <li><a href="{!! url('dashboard_img') !!}" class="g_pwicon_t2"><span>照片管理</span></a></li>
                        <li><a href="{!! url('/dashboard/account_manage') !!}" class="g_pwicon_t3 "><span>更改帳號</span></a></li>
                        <li><a href="{!! url('/dashboard/vipSelect') !!}" class="g_pwicon_t4 g_hicon4"><span>升級付費</span></a></li>
                    </div>
                    <div class="new_viphig">
                        <div class="n_sjvip"  id="vip">
                            <div class="part1">
                            <div class="viplist">
                                <ul>
                                    <li>
                                        <div class="vipcent viptop15">
                                            <div class="new_fa">
                                            <h3>舊會員專屬專案</h3>
                                            </div>
                                            <div class="new_fanext">
                                            <h3>888</h3>
                                            <h3>$NTD/每月</h3>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="gvip_input">
                                <span>
                                    @if(isset($expiry_time) && $expiry_time < \Carbon\Carbon::now())
                                        <form class="m-form m-form--fit" action="{{ route('upgradepay_ec') }}" method=post>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                        <button type="submit" class="gvipbut n_vip01" style="border-style: none;">購買</button>
                                        </form>
                                    @else
                                        <button type="submit" class="gvipbut n_vip01" style="border-style: none;">購買</button>
                                    @endif
                                </span>
                            </div>
                            <div class="vipline"><img src="/new/images/VIP_05.png"></div>
                            <div class="vipbongn">
                                {!!  $vip_text  !!}
                            </div>
                            <div class="n_vipbotf">本筆款項在信用卡帳單顯示為 信宏資產管理公司</div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>
        $(document).ready(function() {
            @if(Session::has('message'))
            c5("{{Session::get('message')}}");
            <?php session()->forget('message');?>
            @endif
        });

        function logFormData(form){
            let data = $(form).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route('upgradepayLog') }}',
                data: {
                    _token:"{{ csrf_token() }}",
                    data : data
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(xhr, status, error){
                    console.log(xhr);
                    console.log(error);
                },
                error: function(xhr, status, error){
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
            return true;
        }

        {{--alert({{$days}});--}}
        @if(isset($expiry_time) && $expiry_time < \Carbon\Carbon::now())

        @else
            c5('此為舊會員專屬優惠頁');
            $('.n_bllbut, .bl_gb, .announce_bg').on('click',function(){
                window.location.href = "/dashboard";
            });
        @endif

    </script>
@stop
