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
	
	function closeAndReload(event) {
        window.location.reload();
    	$(".blbg").hide();
        $(".bl").hide();
		$(".gg_tab").hide();
		
    }	

    $(document).on('click','.blbg',closeAndReload);

	
    function c4(str) {
    	// c5(str)
		 $(".blbg").show();
         $("#tab04").show();
         $("#tab04 .bltext").text(str);
    }
	
    function c_no_more(str) {
		 $(".blbg").show();
         $("#tab_no_more").show();
         $("#tab_no_more .bltext").text(str);
    }	

	function c5(str) {
		$("#announce_bg").show();
		$("#tab05").show();
		$("#tab05 .bltext").text(str);
	}

	function c5html(str) {
		$("#announce_bg").show();
		$("#tab05").show();
		$("#tab05 .bltext").html(str);
	}

	function c5html_redirect(str, url) {
		$("#announce_bg").show();
		$("#tab05_redirect").show();
		$("#tab05_redirect .bltext").html(str);
		$("#c5_redirect_certain_btn").attr("href", url)
	}

	function c6(str) {	
		$(".blbg").show();	
		$("#tab06").show();	
		$("#tab06 .bltext").text(str);	
   }

	function c7(str, link) {
		$(".announce_bg").show();
		$("#tab07").show();
		$("#tab07 .bltext").text(str);
		$("#tab07 .linktext").html(link);
	}

	function c8(str) {
		$(".announce_bg").show();
		$("#tab08").show();
		$("#tab08 .bltext").text(str);
	}

	function c9(str) {
    	//popup長訊息用
		$(".announce_bg").show();
		$("#tab09").show();
		$("#tab09 .bltext").text(str);
		$('body').css("overflow","hidden");
	}

	function show_pop_message(str) {
		$(".blbg").show();
		$("#tabPopM").show();
		$("#tabPopM .bltext").text(str);
	}

	function show_message(str) {
		$(".blbg").show();
		$("#tab_message").show();
		$("#tab_message .gxbut").text(str);
		// c5(str);
	}
	
	function show_canMessageAlert(str) {
		$(".announce_bg").show();
		$("#canMessageAlert").show();
		$("#canMessageAlert .bltext").text(str);	
	}

	function show_onlyForVipPleaseUpgrade() {
		$(".announce_bg").show();
		$("#onlyForVipPleaseUpgrade").show();
	}

	function show_line_notify_set_alert() {
		$(".announce_bg").show();
		$('#line_notify_set_failure').show();
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
		$('body').css("overflow","auto");
	}

	function c5_gmBtnNoReload(){
		$("#announce_bg").hide();
		$("#tab05").hide();
	}

	function ccc(str) {
		$(".blbg").show();
		$("#tab_other").show();
		$("#tab_other .bltext").text(str);
	}

	function popSus(str) {
		$(".blbg").show();
		$("#popSus").show();
	}

	function popSusNew() {
		$(".blbg").show();
		$("#popSusNew").show();
	}

	function popEvaluation() {
		$(".blbg").show();
		$("#popEvaluation").show();
	}

	function loading() {
		$(".announce_bg").show();
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

