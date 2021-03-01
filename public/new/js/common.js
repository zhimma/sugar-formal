	/**驗證Email**/
    function checkEmail(email) {
      var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      if(!regex.test(email)) {
          return false;
      }else{
          return true;
      }
    }

    /**驗證台灣手機號碼**/
    function checkPhone(phone) {
      if (!(/^09\d{8}$/).test(phone) && !(/^9\d{8}$/).test(phone)) {
        return false;
      } else {
        return true;
      }
    }
	function FormHandle(name,data){
		$.each(data,function(i,e){
			$('form[name='+name+'] [name='+i+']').val(e);
		});
	}

	function ResultHandle(result,close=true,target=null){
		if(result.status){
			GetList();
			alert(result.msg);
			if(close){
				if(target){
					$(target).modal('hide');	
				}else{
					$('.modal').modal('hide');		
				}
			}
		}else{
			if(typeof result.msg == 'object'){
				error	=	'';
				$.each(result.msg,function(i,message){
					error	+=	message+'\n';
				});	
			}else{
				error	=	result.msg;
			}
			alert(error);
		}
	}


	function ImgHandle(name,img){
        $(name).attr('src','/_upload/images/'+img);
    }

    function new_windows(url,w,h){



	  	winW = w;

	 	winH = h; 

	  	//視窗位置

	  	screenW=screen.width/2;

	  	screenH=screen.height/2; 

	  	//開新視窗

	  	window.open(url,'',config='location=no,toolbar=no,resizable=no,scrollbars=no,width='+winW+',height='+winH+',top='+(screen.availHeight/2-winH/2)+',left='+(screen.availWidth/2-winW/2)+'');

	}

	function ResultData2(result){
		if(result.status){
			if(result.msg){
				swal({
					title:result.msg,
					text:(result.text)?result.text:'',
					type:'success'
				}).then(() => {
	                if(result.redirect){
						location.href=result.redirect;
					}
	            });
			}
		}else{
			if(typeof result.msg == 'object'){
				error	=	'';
				$.each(result.msg,function(i,message){
					error	+=	message+'\n';
				});
			}else{
				error	=	result.msg;
			}
			swal({
				title:error,
				text:(result.text)?result.text:'',
				type:'error'
			});
		}
	}

	function ResultData(result){
		if(result.status){
			if(result.msg){
				c5(result.msg);
				if(result.redirect){
					$(document).on('click','.blbg',function(event) {
				    	location.href=result.redirect;
				    });
				}
			}
		}else{
			if(typeof result.msg == 'object'){
				error	=	'';
				$.each(result.msg,function(i,message){
					error	+=	message+'\n';
				});
			}else{
				error	=	result.msg;
			}
			if (typeof(result.showLink) != "undefined"){
				c7(error,result.showLink);
			}else{
				c2(error);
			}
			// swal({
			// 	title:error,
			// 	text:(result.text)?result.text:'',
			// 	type:'error'
			// });
		}
	}

	function cl(str) {
     	// $(".blbg").show();
      //   $("#tab01").show();
      //   $("#tab01 .bltext").text(str);
    }

    function c2(str) {
    	// c5(str)
		 $(".blbg").show();
         $("#tab02").show();
         $("#tab02 .gxbut").text(str);
    }

	function c3(str) {
		// c5(str)
		$(".announce_bg").show();
		$("#tab02").show();
		$("#tab02 .gxbut").text(str);
	}

    $(document).on('click','.blbg',function(event) {
    	$(".blbg").hide();
        $(".bl").hide();
		$(".gg_tab").hide();
		window.location.reload();
    });
    function c4(str) {
    	// c5(str)
		 $(".blbg").show();
         $("#tab04").show();
         $("#tab04 .bltext").text(str);
    }

	function c5(str) {
		$(".announce_bg").show();
		$("#tab05").show();
		$("#tab05 .bltext").text(str);
	}

	function c6(str) {	
		$(".blbg").show();	
		$("#tab06").show();	
		$("#tab06 .bltext").text(str);	
   }

	function c7(str, link) {
		$(".blbg").show();
		$("#tab07").show();
		$("#tab07 .msgtext").text(str);
		$("#tab07 .linktext").html(link);
	}

	function c8(str) {
		$(".announce_bg").show();
		$("#tab08").show();
		$("#tab08 .bltext").text(str);
	}

	function show_pop_message(str) {
		$(".announce_bg").show();
		$("#tabPopM").show();
		$("#tabPopM .bltext").text(str);
	}

	function show_message(str) {
		$(".blbg").show();
		$("#tab_message").show();
		$("#tab_message .gxbut").text(str);
		// c5(str);
	}

	function show_block() {
		$(".blbg").show();
		$("#tab_block").show();
	}

	function gmBtnNoReload(){
		$(".announce_bg").hide();
		$(".blbg").hide();
		$(".bl").hide();
		$(".gg_tab").hide();
	}
	function ccc(str) {
		$(".blbg").show();
		$("#tab_other").show();
		$("#tab_other .bltext").text(str);
	}

	function loading() {
		$(".blbg").show();
		$("#tab_loading").show();
		// $("#tab02 .gxbut").text(str);
	}

	function common_confirm(str,str2=null) {

		$(".blbg").show();
		$("#common_confirm").show();
		$("#common_confirm p").text(str);
		if(str2 != null) {
			var redStr = str2.fontcolor('red');
			$("#common_confirm p").append('\n'+redStr);
		}
	}

