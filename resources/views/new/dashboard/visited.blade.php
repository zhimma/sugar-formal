
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
                            <?php $histUser = \App\Models\User::findById($visitor->member_id);
                            if(!isset($histUser)){
                                continue;
                            }
                            $umeta = $histUser->meta_();
                            if(isset($umeta->city)){
                                $umeta->city = explode(",",$umeta->city);
                                $umeta->area = explode(",",$umeta->area);
                            }
                            ?>
                                @if(isset($histUser))
                                    <li @if($histUser->isVip()) class="hy_bg01" @endif>
                                        <div class="si_bg">
                                            <a href="/dashboard/viewuser/{{$histUser->id}}?time={{ \Carbon\Carbon::now()->timestamp }}">
                                            <div class="sjpic"><img src="@if($histUser->meta_()->isAvatarHidden) {{ 'makesomeerror' }} @else {{$histUser->meta_()->pic}} @endif" @if ($histUser->engroup == 1) onerror="this.src='/new/images/male.png'" @else onerror="this.src='/new/images/female.png'" @endif></div>
                                            <div class="sjleft">
                                                <div class="sjtable"><span>{{ $histUser->name }}<i class="cicd">●</i>{{ $histUser->meta_()->age() }}</span></div>
                                                <font>
                                                    @if(!is_array($umeta->city))

                                                    @else
                                                        @foreach($umeta->city as $key => $cityval)
                                                            @if ($loop->first)
                                                                {{$umeta->city[$key]}} @if($histUser->meta_()->isHideArea == 0){{$umeta->area[$key]}}@endif
                                                            @else
                                                                {{$umeta->city[$key]}} @if($histUser->meta_()->isHideArea == 0){{$umeta->area[$key]}}@endif
                                                            @endif
                                                        @endforeach
                                                    @endif
{{--                                                    {{ $histUser->meta_()->city }}  {{ $histUser->meta_()->area }}--}}
                                                </font>
                                            </div>
                                            </a>
                                            <div class="sjright">
                                                <h3>{{ $visitor->created_at }}</h3>
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
@stop