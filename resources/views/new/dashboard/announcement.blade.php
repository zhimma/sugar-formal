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
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>1</span></div>
                            <div class="n_iconfont">男女會員在約見前，禁止要求對方提供猥褻照片、文字。違者將直接永久封鎖帳號。</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>2</span></div>
                            <div class="n_iconfont">普通會員禁止註冊站務/站長/管理者等容易引起他人誤會之站務人員帳號。</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>3</span></div>
                            <div class="n_iconfont">站務人員帳號除了暱稱會有 站長/站務/管理者外，頭像也會有特別的底色與標籤說明。</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>4</span></div>
                            <div class="n_iconfont">優質糖爹是願意長期付費的VIP，或者常用車馬費邀請的男會員，讓女會員作為約會對象的參考。<font>「請按我<img src="/new/images/55_07.png">」</font></div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>5</span></div>
                            <div class="n_iconfont">會員訊息彼此來往請勿口出惡言，檢舉屬實將會有3~30天的封鎖時間。</div>
                        </li>

                        <?php //$num=1;?>
{{--                        @foreach($announcement as $row)--}}
{{--                        <li>--}}
{{--                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>--}}
{{--                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>{{$num}}</span></div>--}}
{{--                            <div class="n_iconfont">{{$row->content}}</div>--}}
{{--                        </li>--}}
                                <?php //$num++;?>
{{--                        @endforeach--}}
                    </ul>
                </div>
            </div>

        </div>
    </div>
@stop
