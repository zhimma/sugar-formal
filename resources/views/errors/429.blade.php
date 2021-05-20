@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <div class="wxsy" style="min-height: 0!important;">
                    <div class="wxsy_title">
                        連線過多
                    </div>
                    <div class="wxsy_k">
                        <div class="wknr">
                            <h5>您目前連線次數過多，請稍後重試</h5>
                            @if(isset($user))
                                {{ logger('429, user id: ' . $user->id . ', IP: ' . request()->ip()) }}
                            @else
                                {{ logger('429, IP: ' . request()->ip()) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

