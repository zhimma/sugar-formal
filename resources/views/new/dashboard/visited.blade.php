
@extends('new.layouts.website')

@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="shou"><span>誰來看我</span>
                    <font>Who came to see me</font>
{{--                    <a onclick="cl()"><img src="/new/images/ncion_03.png"  class="whoicon"></a>--}}
                </div>
                <div class="sjlist">
                    @if(count($visitors)>0)
                    <ul>
                        @foreach ($visitors as $visitor)
                            <?php $histUser = $visitor;
                            if(!isset($histUser)){
                                continue;
                            }
                            $umeta = $histUser->user->meta;
                            if(isset($umeta->city) && !is_array($umeta->city)){
                                $umeta->city = explode(",",$umeta->city);
                                $umeta->area = explode(",",$umeta->area);
                            }
                            else{
                                $umeta->city = null;
                                $umeta->area = null;
                            }
                            ?>
                            @php
                                $isBlurAvatar = \App\Services\UserService::isBlurAvatar($histUser->user, $user);
                            @endphp
                                @if(isset($histUser))
                                    <li @if(($histUser->user->vip->first() && $histUser->user->vip->first()->active) || $histUser->user->isVVIP()) class="hy_bg01" @endif>
                                        <div class="si_bg">
                                            @if($histUser->user->isVVIP())
                                                <a href="/dashboard/viewuser_vvip/{{$histUser->user->id}}">
                                            @else
                                                <a href="/dashboard/viewuser/{{$histUser->user->id}}">
                                            @endif
                                            <div class="sjpic @if($isBlurAvatar) blur_img @endif"><img src="@if($histUser->user->meta->isAvatarHidden) {{ 'makesomeerror' }} @else {{$histUser->user->meta->pic}} @endif" @if ($histUser->user->engroup == 1) onerror="this.src='/new/images/male.png'" @else onerror="this.src='/new/images/female.png'" @endif></div>
                                            <div class="sjleft">
                                                <div class="sjtable"><span>{{ $histUser->user->name }}<i class="cicd">●</i>{{ $histUser->user->meta->age() }}</span></div>
                                                <font>
                                                    @if(!is_array($umeta->city))

                                                    @else
                                                        @foreach($umeta->city as $key => $cityval)
                                                            @if ($loop->first)
                                                                {{$umeta->city[$key]}} @if($histUser->user->meta->isHideArea == 0){{$umeta->area[$key]}}@endif
                                                            @else
                                                                {{$umeta->city[$key]}} @if($histUser->user->meta->isHideArea == 0){{$umeta->area[$key]}}@endif
                                                            @endif
                                                        @endforeach
                                                    @endif
{{--                                                    {{ $histUser->user->meta->city }}  {{ $histUser->user->meta->area }}--}}
                                                </font>
                                            </div>
                                            </a>
                                            <div class="sjright">
{{--                                                <h3>@if($visitor->is_hide_online==1 || $visitor->is_hide_online==2){{ substr($visitor->hide_online_time,0,16) }}@else{{ substr($visitor->latest_visited,0,16) }}@endif</h3>--}}
                                                <h3>{{ substr($visitor->latest_visited,0,16) }}</h3>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                        @endforeach
                    </ul>
                    @else
                        <div class="sjlist">
                            <div class="fengsicon"><img src="/new/images/fs_06.png" class="feng_img"><span>暫無資料</span></div>
                        </div>
                    @endif
{{--                    <div class="fenye">--}}
{{--                        <a id="prePage" href="{{ $visitors->previousPageUrl() }}">上一頁</a>--}}
{{--                        <a id="nextPage" href="{{ $visitors->nextPageUrl() }}">下一頁</a>--}}
{{--                    </div>--}}

                </div>
            </div>

        </div>
    </div>
<style>
    .blur_img {
        filter: blur(1px);
        -webkit-filter: blur(1px);
    }
</style>
@stop
@section('javascript')
<script>
    // 計算瀏覽時間
    var page_id = 'browse';
</script>
@stop