@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="wxsy" style="min-height: 0!important;">
                    <div class="wxsy_title">
                        連線頻率過高
                    </div>
                    <div class="wxsy_k">
                        <div class="wknr">
                            @if(isset($user))
                                <h5>您目前連線次數過多，系統已將您強制登出；請勿於短時間內大量連線，並請稍後再重新登入</h5>
                                {{ logger('429, user id: ' . $user->id . ', IP: ' . request()->ip()) }}
                                @php
                                    \DB::table('log_too_many_requests')->insert(
                                        ['user_id' => $user->id,
                                        'ip' => request()->ip(),
                                        'requests' => 50,
                                        'mins' => 1,
                                        "created_at" =>  \Carbon\Carbon::now(),
                                        "updated_at" => \Carbon\Carbon::now(),]);
                                    \App\Models\SetAutoBan::logout_warned($user->id);
                                    \Session::flush();
                                    \Session::forget('announceClose');
                                    \Auth::logout();
                                @endphp
                            @else
                                <h5>您目前連線次數過多，請稍後重試</h5>
                                {{ logger('429, IP: ' . request()->ip()) }}
                                @php
                                    \DB::table('log_too_many_requests')->insert(
                                        ['ip' => request()->ip(),
                                        'requests' => 50,
                                        'mins' => 1,
                                        "created_at" =>  \Carbon\Carbon::now(),
                                        "updated_at" => \Carbon\Carbon::now(),]);
                                @endphp
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

