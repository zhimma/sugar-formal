@extends('new.layouts.website')

@section('app-content')
        <style>
        .blur_img {
            filter: blur(3px);
            -webkit-filter: blur(3px);
        }
        
        .sjlist li .sjleft font span {margin-left:3%;float:unset;display:unset;} 
        </style>

		<div class="container matop70">
			<div class="row">
				<div class="col-sm-2 col-xs-2 col-md-2 dinone">
                @include('new.dashboard.panel')
				</div>
				<div class="col-sm-12 col-xs-12 col-md-10">
					<div class="shou"><span>略過</span>
						<font>Skip over</font>
						<a href="javascript:void(0);" class="aa_shou_but" style="{{$service->entrys->count()?'':'display:none;'}}" onclick="clearAllSearchDiscard();"><img src="{!!asset('new/images/zs_jsdel.png')!!}">全部解除</a>
					</div>
					<div class="sjlist">
                        <div id="no_data_block" class="sjlist" style="display:{{$service->entrys->count()?'none':'block'}}">
                            <div class="fengsicon"><img src="{!!asset('/new/images/pjicon01.png')!!}" class="feng_img" style="width: 120px;"><span>暫無資料</span></div>
                        </div>                       
						<ul>                     
                            @foreach($service->entrys as $entry)
                            <li class="{{$entry->ignore_user->isVip()?'hy_bg01':''}}">
								<div class="si_bg leftb5">
                                    <a href="/dashboard/viewuser/{{$entry->ignore_user->id}}?time={{ time()}}">
                                        <div class="sjpic"><img class="{{$service->isBlurAvatarByUser($entry->ignore_user)?'blur_img':''}}" src="{{$service->getShowPicByUser($entry->ignore_user)}}"  data-original="{{$service->getShowPicByUser($entry->ignore_user)}}" onerror="this.src='{{$service->getPicOnErrorByEngroup($entry->ignore_user->engroup)}}';"></div>
                                        <div class="sjleft">
                                            <div class="sjtable"><span>{{$entry->ignore_user->name}}<i class="cicd">●</i>{{$entry->ignore_user->age()}}</span></div>
                                            <font>{!!$service->getCityShowByUser($entry->ignore_user)!!}</font>
                                        </div>
                                    </a>
									<div class="sjright">
										<a href="javascript:void(0);" class="sjright_aa" onclick="confirmRemoveDiscard('{{$entry->ignore_user->id}}',$(this).parent().parent().parent());"><img src="{{asset('new/images/lg_01.png')}}">解除略過</a>
									</div>
								</div>
							</li>
                            @endforeach
						</ul>
						<div class="fenye">
                        {!! $service->entrys->appends(request()->input())->links('pagination::sg-pages2') !!}
						</div>

					</div>
				</div>

			</div>
		</div>

    @include('partials.image-zoomin')
@stop
@section('javascript')
<script src="{{asset('/new/js/pick_real_error.js')}}" type="text/javascript"></script>
<script>
   
	function cl() {
		 $(".blbg").show()
         $("#tab01").show()
    }
    function gmBtn1(){
        $(".blbg").hide()
        $(".bl_tab").hide()	
			
    }
    
    function confirmRemoveDiscard(id,qelt) {
        $(document).off('click','#tab08 .n_bbutton .n_left');

        $(document).on('click','#tab08 .n_bbutton .n_left',{ id: id, qelt:qelt},function(e){
            removeSearchDiscard(e.data.id,e.data.qelt);
            gmBtnNoReload();
        $(this).off('click');
        $(this).off('click');            
        });                     
        c8('確定要解除略過嗎？');        
    }
    
    function removeSearchDiscard(id,qelt) {
        if(id==null || id==undefined || id=='') return;
        var url = '';
        var type='';
        url = "{!!url('/dashboard/search_discard/del') !!}";
        type="get";
        qelt.hide();
        $(document).off('click','#tab08 .n_bbutton .n_left');   
        $.ajax({
          type: type,
          url: url,
          data:{ target:id},
          success:function(data) {
            if(!data || data=='0' || data==undefined || data==null || pick_real_error(data).length>0) {
                qelt.show();
            }
            else {
                if(qelt.parent().children().length<=1) {
                    $('.shou .aa_shou_but').hide();
                    $('#no_data_block').show();
                }                
                else qelt.remove();
                
                c5('解除成功');
            }
          },
          error:function() {
              qelt.show();
          }
        });
    } 

  

    function clearAllSearchDiscard() {
        $(document).off('click','#tab08 .n_bbutton .n_left');
        $(document).on('click','#tab08 .n_bbutton .n_left',function(){
            $(this).off('click');           
            var qelt = $('.sjlist ul');
            qelt.hide();
            var url = "{!!url('/dashboard/search_discard/del') !!}";
            var type="get";            
            $.ajax({
              type: type,
              url: url,
              success:function(data) {
                if(!data || data=='0' || data==undefined || data==null || pick_real_error(data).length>0) {
                    qelt.show();
                }
                else {
                    $('.shou .aa_shou_but').hide();
                    $('#no_data_block').show();
                    qelt.remove();
                }
              },
              error:function() {
                  qelt.show();
              }
            });  
            gmBtnNoReload();            
        });          
        c8('您確定要解除所有搜尋略過的設定嗎？');
    }
    
</script>

@stop