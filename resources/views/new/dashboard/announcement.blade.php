@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="shou"><span>站方公告</span>
                    <font>announcement</font>
                </div>
                <div class="n_gongg">
                    <ul>
                        <?php $num=1;?>
                        @foreach($announcement as $row)

                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>{{$num}}</span></div>
                            <div class="n_iconfont">{{$row->content}}</div>
                        </li>
                                <?php $num++;?>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
@stop
