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
                            <div class="n_iconfont">男女會員在約見前，禁止要求對方提供猥褻照片、文字。</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>2</span></div>
                            <div class="n_iconfont">普通會員禁止註冊站務/站長/管理者等容易引起他人誤會之站務管理人員字眼做為帳號。</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>3</span></div>
                            <div class="n_iconfont">會員彼此來往訊息請勿口出惡言。</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>4</span></div>
                            <div class="n_iconfont">禁止多重帳號/不同性別註冊。</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>5</span></div>
                            <div class="n_iconfont">禁止個人資料出現不雅文字/照片（站方主觀認定）</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>6</span></div>
                            <div class="n_iconfont">禁止使用虛假資料註冊</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>7</span></div>
                            <div class="n_iconfont">禁止非包養的任何商業行為！</div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>8</span></div>
                            <div class="n_iconfont"><span style="color: red;background: yellow;">違反以上規定將會有3~30天的封鎖時間。嚴重者直接永久封鎖帳號。</span></div>
                        </li>
                        <li>
                            <div class="n_icongg"><img src="/new/images/ic_03.png" class="n_icongg_img"></div>
                            <div class="n_iconb"><img src="/new/images/ic_07.png"><span>9</span></div>
                            <div class="n_iconfont"><span style="color: red;background: yellow;">禁止大量發送內含通訊軟體資訊的罐頭訊息！</span></div>
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
