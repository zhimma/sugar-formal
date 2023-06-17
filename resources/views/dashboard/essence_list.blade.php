@extends('new.layouts.website')
@section('style')
    <link rel="stylesheet" href="/posts/css/style.css">
    <link rel="stylesheet" href="/posts/css/font/font_n/iconfont.css">
    <link rel="stylesheet" href="/posts/css/font/iconfont.css">
    <link rel="stylesheet" href="/posts/css/taolunqu/iconfont.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400|700" rel="stylesheet">
    <script src="/posts/js/jquery-2.1.1.min.js" type="text/javascript"></script>
    <script src="/posts/js/bootstrap.min.js"></script>

    <style>
        .hycov_down{
            width: 28px;
            height: 28px;
        }

        .wt_txb{ position: relative; }

        .ta_sz{ position: absolute; width:15px; height:15px; color: #fff; border-radius: 100px; display: flex; text-align: center; justify-content: center; align-items: center; right: 0; top:0px; background: #69b9ff; font-size: 12px;}
        .ta_sz_ten{ position: absolute; width:20px; height:20px; color: #fff; border-radius: 100px; display: flex; text-align: center; justify-content: center; align-items: center; right: 0; top:0px; background: #69b9ff; font-size: 12px;}
        .ta_sz_hundred{ position: absolute; width:25px; height:25px; color: #fff; border-radius: 100px; display: flex; text-align: center; justify-content: center; align-items: center; right: 0; top:0px; background: #69b9ff; font-size: 13px;}

        .hycov{ border-radius: 100px;}

    </style>
@endsection
@section('app-content')
    <div class="container matop70">
        <div class="row">
            <div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
            </div>
            <div class="col-sm-12 col-xs-12 col-md-10">

                <div class="shou" style="text-align: center; position: relative;">
                    <a href="{{ $user->engroup==1 ? '/dashboard/essence_enter_intro' : '/dashboard/essence_main' }}" class="toug_back btn_img" style=" position: absolute; left:-6px;">
                        <div class="btn_back"></div>
                    </a>
                    <div style="position: absolute; left:45px;">
                        <span>{{ $postType=='myself' ? '投稿紀錄' : '精華文章專區' }}</span>
                        <font>{{ $postType=='myself' ? 'Record' : 'Article' }}</font>
                    </div>
                    @if($user->id==1049)
                        <a class="toug_back btn_img01 userlogo2 xzgn" style="margin-right: 92px;">
                            <div class="btn_back" >排序功能<img src="/posts/images/jiant_a.png"></div>
                        </a>
                        <div class="fabiao showslide2" style="margin-top: 40px;margin-right: 100px;">
                            <a href="/dashboard/essence_list?order_by=pending">待審核</a>
                            <a href="/dashboard/essence_list?order_by=updated_at">文章時間</a>
                        </div>
                    @endif
                    @if($user->engroup==1)
                        <a class="toug_back btn_img01 userlogo xzgn" >
                            <div class="btn_back" >功能選單<img src="/posts/images/jiant_a.png"></div>
                        </a>
                        <div class="fabiao showslide" style="margin-top: 40px;">
                            <a href="/dashboard/essence_list">精華文章</a>
                            <a href="/dashboard/essence_posts">我要投稿</a>
                            <a href="/dashboard/essence_list?postType=myself">投稿記錄</a>
                        </div>
                    @endif
                </div>
                <div class="fadeinboxs"></div>
                <div class="fadeinboxs2"></div>
                <script>
                    $('.userlogo').click(function(){
                        event.stopPropagation()
                        if($(this).hasClass('')){
                            $(this).removeClass('')
                            $('.fadeinboxs').fadeOut()
                            $('.showslide').fadeOut()
                        }else{
                            $(this).addClass('')
                            $('.fadeinboxs').fadeIn()
                            $('.showslide').fadeIn()
                        }
                    })
                    $('.userlogo2').click(function(){
                        event.stopPropagation()
                        if($(this).hasClass('')){
                            $(this).removeClass('')
                            $('.fadeinboxs2').fadeOut()
                            $('.showslide2').fadeOut()
                        }else{
                            $(this).addClass('')
                            $('.fadeinboxs2').fadeIn()
                            $('.showslide2').fadeIn()
                        }
                    })
                    $('body').click(function(){
                        $('.showslide').fadeOut()
                        $('.fadeinboxs').fadeOut()
                        $('.showslide2').fadeOut()
                        $('.fadeinboxs2').fadeOut()
                    })

                    //切换,第一个盒子和菜单默认显示

                </script>
                <!--  -->
                <style>
                    .ailefont{ position: absolute; left: 40px; z-index: -10;}
                    @media (max-width:450px){
                        .ailefont{ position: absolute; left:0px;}
                    }
                    :root {
                        --primary-light: #8abdff;
                        --primary: #6d5dfc;
                        --primary-dark: #5b0eeb;
                        --white: #FFFFFF;
                        --greyLight-1: #E4EBF5;
                        --greyLight-2: #c8d0e7;
                        --greyLight-3: #bec8e4;
                        --greyDark: #9baacf;
                    }

                </style>
                <div class="jinghua_tl">
                    <div class="jh_ulist">
                        <ul>
                            @if($user->engroup==2 && Request()->get('s')=='admin')
                                @php
                                    $admin_info=\App\Models\User::leftJoin('user_meta', 'users.id','=','user_meta.user_id')->where('users.id', 1049)->first();
                                @endphp
                                <a href="/dashboard/newer_manual">
                                    <li>
                                        <div class="jh_blue">
                                            <div class="jh_biaoq"><span><img src="/posts/images/jh_03.png">新手教學手冊</span></div>
                                            <div class="jh_one">
                                                <div class="jh_one_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="imgov"></div>
                                                <div class="jh_fontw">{{ $admin_info->name }}<span></span></div>
                                            </div>
                                            <div class="jh_two">
                                                <h2>新手必看！</h2>
                                                <h3 style="display: block;">歡迎新手光臨本站，這篇絕對要好好看過！這篇絕對要好好看過</h3>
                                            </div>
                                        </div>
                                    </li>
                                </a>
                                <a href="/dashboard/anti_fraud_manual">
                                    <li>
                                        <div class="jh_blue">
                                            <div class="jh_biaoq"><span><img src="/posts/images/jh_03.png">拒絕詐騙手冊</span></div>
                                            <div class="jh_one">
                                                <div class="jh_one_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="imgov"></div>
                                                <div class="jh_fontw">{{ $admin_info->name }}<span></span></div>
                                            </div>
                                            <div class="jh_two">
                                                <h2>拒絕詐騙手冊</h2>
                                                <h3 style="display: block;">正常找包養女孩，一定要注意的事情有哪些？</h3>
                                            </div>
                                        </div>
                                    </li>
                                </a>
                                <a href="/dashboard/essence_post_detail/1?article=law_protection_sample">
                                    <li>
                                        <div class="jh_hu04">
                                            <div class="jh_biaoq01 jh_biaoq04"><span><img src="/posts/images/jh_11.png">法律保護女性篇</span></div>
                                            <div class="jh_one">
                                                <div class="jh_one_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="imgov"></div>
                                                <div class="jh_fontw">{{ $admin_info->name }}<span>2023-05-30</span></div>
                                            </div>
                                            <div class="jh_two">
                                                <h2>窈窕淑女人見人愛怎麼追?君子的妙法寶就是要尊重!</h2>
                                                <h3>小王愛慕小美追求未果因而心生怨恨，在花園網發現與小美相似的女會員照片，將其截圖私下散布於工作群組，經起訴判刑小王加重毀謗罪，判處拘役1個月。</h3>
                                            </div>
                                        </div>
                                    </li>
                                </a>
                                <a href="/dashboard/essence_post_detail/1?article=law_protection_sample_2">
                                    <li>
                                        <div class="jh_hu04">
                                            <div class="jh_biaoq01 jh_biaoq04"><span><img src="/posts/images/jh_11.png">法律保護女性篇</span></div>
                                            <div class="jh_one">
                                                <div class="jh_one_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="imgov"></div>
                                                <div class="jh_fontw">{{ $admin_info->name }}<span>2023-06-17</span></div>
                                            </div>
                                            <div class="jh_two">
                                                <h2>紳士的禮儀來自好聚好散 風度轉身迎接下一位佳人</h2>
                                                <h3>小陳於花園網結識小晴後，因不滿小晴後來拒絕聯絡而多次騷擾小晴，小晴不堪其擾報警處理，經檢察官起訴後判決小陳嚇危害安全罪，判處拘役20日。</h3>
                                            </div>
                                        </div>
                                    </li>
                                </a>
                                <a href="/dashboard/essence_post_detail/1?article=law_protection_sample_3">
                                    <li>
                                        <div class="jh_hu04">
                                            <div class="jh_biaoq01 jh_biaoq04"><span><img src="/posts/images/jh_11.png">法律保護女性篇</span></div>
                                            <div class="jh_one">
                                                <div class="jh_one_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="imgov"></div>
                                                <div class="jh_fontw">{{ $admin_info->name }}<span>2023-06-17</span></div>
                                            </div>
                                            <div class="jh_two">
                                                <h2>誠實是建立長期關係的橋樑 編造的謊言終會被正義擊破</h2>
                                                <h3>大明在花園網編造虛假的收入條件，相約小莉出遊約會後未履行與小莉的約定且將她封鎖，小莉報警後經起訴判決詐欺罪成立，賠償12,500元判處拘役55日。</h3>
                                            </div>
                                        </div>
                                    </li>
                                </a>
                                <a href="/dashboard/essence_post_detail/1?article=law_protection_sample_4">
                                    <li>
                                        <div class="jh_hu04">
                                            <div class="jh_biaoq01 jh_biaoq04"><span><img src="/posts/images/jh_11.png">法律保護女性篇</span></div>
                                            <div class="jh_one">
                                                <div class="jh_one_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="imgov"></div>
                                                <div class="jh_fontw">{{ $admin_info->name }}<span>2023-06-17</span></div>
                                            </div>
                                            <div class="jh_two">
                                                <h2>截圖貼貼貼看我一秒變身?! 變身千面女郎小心誤觸法網</h2>
                                                <h3>小可日前於花園網註冊會員，卻盜用名人小玲的照片做為個人資料使用，尋求長期關係，經檢察官起訴後判決小可散布文字、圖畫誹謗罪，判處拘役2個月。</h3>
                                            </div>
                                        </div>
                                    </li>
                                </a>
                                <a href="/dashboard/essence_post_detail/1?article=law_protection_sample_5">
                                    <li>
                                        <div class="jh_hu04">
                                            <div class="jh_biaoq01 jh_biaoq04"><span><img src="/posts/images/jh_11.png">法律保護女性篇</span></div>
                                            <div class="jh_one">
                                                <div class="jh_one_img"><img src="@if(file_exists( public_path().$admin_info->pic ) && $admin_info->pic != ""){{$admin_info->pic}} @elseif($admin_info->engroup==2)/new/images/female.png @else/new/images/male.png @endif" class="imgov"></div>
                                                <div class="jh_fontw">{{ $admin_info->name }}<span>2023-06-17</span></div>
                                            </div>
                                            <div class="jh_two">
                                                <h2>神鬼交鋒真人版假名行騙 相交貴在誠信勿以身試法</h2>
                                                <h3>大勇與小愛在花園網結識，幾次出遊大勇皆請小愛墊付購買高價精品，且提供假名給店家供後續手機取貨，佯稱月底還款卻將小愛封鎖，經小愛報警檢察官起訴判決大勇詐欺取財罪以及偽造私文書罪，宣告沒收不法所得，判處拘役11個月。</h3>
                                            </div>
                                        </div>
                                    </li>
                                </a>
                            @endif
                            @if($posts_list->total()>0)
                                @foreach($posts_list as $detail)
                                    @php
                                        $uID=\App\Models\User::findById($detail->uid);
                                        $isBlurAvatar = \App\Services\UserService::isBlurAvatar($uID, $user);
                                    @endphp
                                    <a href="/dashboard/essence_post_detail/{{ $detail->pid }}">
                                        <li>
                                            <div class="{{ $detail->uid==1049 ? 'jh_blue' : ($detail->verify_status==2 ? 'jh_huise' : 'jh_huise hus_ad') }}">
                                                <div class="{{ $detail->uid==1049 ? 'jh_biaoq' : ($detail->verify_status==2 ? 'jh_biaoq01' : 'jh_biaoq01 jh_biaoq01_hs') }}"><span><img src="/posts/images/{{ $detail->engroup==1 ? 'jh_03.png' : 'jh_09.png' }}">{{ \App\Models\EssencePosts::CATEGORY[$detail->category] }}</span></div>
                                                <div class="jh_one">
                                                    <div class="jh_one_img @if($isBlurAvatar) blur_img @endif"><img src="@if(file_exists( public_path().$detail->umpic ) && $detail->umpic != ""){{$detail->umpic}} @elseif($detail->uengroup==2)/new/images/female.png @else/new/images/male.png @endif" class="imgov"></div>
                                                    <div class="jh_fontw">{{ $detail->name }}<span>{{ date('Y-m-d H:i',strtotime($detail->post_updated_at)) }}</span></div>
                                                </div>
                                                <div class="jh_two">
                                                    <h2>{{ $detail->title }}</h2>
                                                    <h3>{{ $detail->contents }}</h3>
                                                </div>
                                            </div>
                                        </li>
                                    </a>
                                @endforeach
                            @else
                                @if($user->engroup!==2)
                                <div class="sjlist">
                                    <div class="fengsicon"><img src="/posts/images/bianji.png" class="feng_img"><span>尚無資料</span></div>
                                </div>
                                @endif
                            @endif
                        </ul>
                    </div>
                    @if($posts_list->total()>10)
                        <div class="fenye ">
                            {{ $posts_list->appends(request()->input())->links('pagination::sg-pages2') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
    </script>
@endsection
