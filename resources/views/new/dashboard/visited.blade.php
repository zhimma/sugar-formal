
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
                    <ul>
                        @foreach ($visitors as $visitor)
                            <?php $histUser = \App\Models\User::findById($visitor->member_id);
                            ?>
                                @if(isset($histUser))
                                    <li @if($histUser->isVip()) class="hy_bg01" @endif>
                                        <div class="si_bg">
                                            <a href="/dashboard/viewuser/{{$histUser->id}}?time={{ \Carbon\Carbon::now()->timestamp }}">
                                            <div class="sjpic"><img src="@if($histUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{$histUser->meta_()->pic}} @endif" onerror="this.src='/img/male-avatar.png'"></div>
                                            <div class="sjleft">
                                                <div class="sjtable"><span>{{ $histUser->name }}<i class="cicd">●</i>{{ $histUser->meta_()->age() }}</span></div>
                                                <font>{{ $histUser->meta_()->city }}  {{ $histUser->meta_()->area }}</font>
                                            </div>
                                            </a>
                                            <div class="sjright">
                                                <h3>{{ $visitor->created_at }}</h3>
                                                <?php
                                                $counter = \App\Models\Visited::where('visited_id',$user->id)->where('member_id',$histUser->id)->count();
                                                ?>
                                                <h5>{{$counter}}<img src="/new/images/ncion_13.png"></h5>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                        @endforeach
                    </ul>
{{--                    <div class="fenye">--}}
{{--                        <a id="prePage" href="{{ $visitors->previousPageUrl() }}">上一頁</a>--}}
{{--                        <a id="nextPage" href="{{ $visitors->nextPageUrl() }}">下一頁</a>--}}
{{--                    </div>--}}

                </div>
            </div>

        </div>
    </div>
@stop