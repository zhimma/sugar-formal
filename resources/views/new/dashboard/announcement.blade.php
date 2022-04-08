@extends('new.layouts.website')

@section('app-content')

    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">
                <div class="shou"><span>花園網站規</span>
                    <font>Announcement</font>
                </div>
                <div class="n_gongg">
                    <ul>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>1</span></div>
                            <div class="n_iconfont">威脅。</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>2</span></div>
                            <div class="n_iconfont">肉搜。</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>3</span></div>
                            <div class="n_iconfont">未見面前要求對方拍攝清涼照。</div>
                        </li>

                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>#</span></div>
                            <div class="n_iconfont"><span style="color: red;background: yellow;">以上違反者直接永鎖帳號，沒有任何解釋空間。之後所有新開帳號也一律封鎖。</span></div>
                        </li>

                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>4</span></div>
                            <div class="n_iconfont">多重帳號 (一個人只能開立一個帳號，禁止關閉後另開新號)</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>5</span></div>
                            <div class="n_iconfont">用詞不當/人身攻擊</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>6</span></div>
                            <div class="n_iconfont">涉嫌性交易的文字</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>7</span></div>
                            <div class="n_iconfont">個人基本資料虛假/錯誤性別</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>8</span></div>
                            <div class="n_iconfont">內含通訊資訊的罐頭訊息！</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>9</span></div>
                            <div class="n_iconfont">非包養的任何商業行為！</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>10</span></div>
                            <div class="n_iconfont">個人資料出現不雅文字/照片（站方主觀認定)</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>11</span></div>
                            <div class="n_iconfont">禁止向"長期為主"的女會員主動發送罐頭短約訊息。</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>#</span></div>
                            <div class="n_iconfont"><span style="color: red;background: yellow;">違反以上規定將會有3~30天的封鎖/警示時間。嚴重者永久封鎖帳號。</span></div>
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
    <script>
        $('a[data-toggle="picture"]').popover({
            animated: 'fade',
            placement: 'bottom',
            trigger: 'hover',
            html: true,
            content: function () { return '<img width="250" src="' + $(this).data('img') + '" />'; }
        });
        // $('a[data-toggle="admin_head"]').popover({
        //     animated: 'fade',
        //     placement: 'bottom',
        //     trigger: 'hover',
        //     html: true,
        //     content: function () { return '<img width="250" src="' + $(this).data('img') + '" />'; }
        // });
    </script>
@stop
