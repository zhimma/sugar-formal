@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="shou shou02 sh_line"><span>Email 驗證</span>
                    <font>Email confirmation</font>
                </div>
                <div class="email">
                    <div class="wxsy_title">站長的話</div>
                    <div class="wxsy_k">
                        <div class="wknr">
                            {{-- <p>驗證碼已經重新寄到你的email. <a style="color: red; font-weight: bold;">【{{ $user->email }}】</a></p> --}}
                            {{-- <a href="{!! url('contact') !!}" style="color: red; font-weight: bold;">如果沒收到認證信/認證失敗，請點此聯繫站長。</a> --}}
                            <p>由於寄信系統維護中，如需驗證請直接向站長聯繫</p>
                            <a href="{!! url('contact') !!}" style="color: red; font-weight: bold;">點此聯繫站長</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop