rbupld_image_handling_numSet={};
rbupld_image_handled_numSet={};
rbupld_not_support_file_numSet={};
rbupld_add_not_support_file_numSet={};
rbupld_uploader_index = 0;
rbupld_container_initial_visible={};
resize_before_upload_fileReaderSet = {};
function resize_before_upload(uploader,checkWidth
                                ,checkHeight
                                ,outer_selector=''
                                ,returnDataType='text'
                                ,pop_type='show_pop_message'
                                ) {
    uploader.each(function( elt_index,curUploaderElt ) {       
        var 
        imgNewHeight = checkHeight, 
        imgNewWidth =checkWidth, 
        file, fileReader, dataUrl=[];

        var cur_uploader_api = $.fileuploader.getInstance($(curUploaderElt));
        var blobElt = []; 
        var blobEltOfIos = [];
        var listInputElt = cur_uploader_api.getListInputEl();

        cur_uploader_api.rbupld_uploader_index = rbupld_uploader_index++;
        var index = cur_uploader_api.rbupld_uploader_index;
        rbupld_container_initial_visible[index] = cur_uploader_api.getParentEl().css('display');
        cur_uploader_api.setOption('afterSelect',function(listEl,parentEl, newInputEl, inputEl){
            if(resize_before_upload_fileReaderSet[index]==undefined)  resize_before_upload_fileReaderSet[index]={};
            rbupld_not_support_file_numSet[index] =0;
            rbupld_add_not_support_file_numSet[index]=0;
            var cur_uploader_option = cur_uploader_api.getOptions();
            var fileSelected = cur_uploader_api.getChoosedFiles();

            if(fileSelected.length>0) {
                loading();
            }
            else return false;            
            if(cur_uploader_option.beforeResize!=undefined){
                cur_uploader_option.beforeResize(listEl,parentEl, newInputEl, inputEl);
            }

            rbupld_image_handling_numSet[index]=0;
            rbupld_image_handled_numSet[index]=0;    
            
            org_announce_bg_onclick_value = $(".announce_bg").attr('onclick');
            $(".announce_bg").attr('onclick','');
            
            var nowEltName = cur_uploader_api.getInputEl().attr('name');
            var hasHeicType = false;
            var hasHeifType = false;


             
            var needless_resize = true;
            var exist_selected_file_num = 0;

            for(i=0;i<fileSelected.length;i++) {
                
                if(resize_before_upload_fileReaderSet[index][fileSelected[i].name]!=undefined) {
                    exist_selected_file_num++;
                    if(resize_before_upload_fileReaderSet[index][fileSelected[i].name].not_support_file!=undefined 
                    && resize_before_upload_fileReaderSet[index][fileSelected[i].name].not_support_file==1)  {
                        rbupld_not_support_file_numSet[index]++;
                    }
                    
                   
                    continue;
                }
                else needless_resize = false;

                let curFileEntry = fileSelected[i];
                resize_before_upload_fileReaderSet[index][curFileEntry.name]  = new FileReader();

                resize_before_upload_fileReaderSet[index][curFileEntry.name].onload = function(evt) {
                    dataUrl[curFileEntry.name] = evt.target.result;
                    
                    file_name_splits = curFileEntry.name.split('.');
                    file_ext_name = file_name_splits.pop();                    
                    
                    if (curFileEntry && curFileEntry.type.indexOf("image") === 0
                        && file_ext_name.toLowerCase()!='heif'
                        && file_ext_name.toLowerCase()!='heic'
                    ) 
                    {
                        rbupld_image_handling_numSet[index]++;
                        create_img_to_resize(checkWidth,checkHeight,dataUrl[curFileEntry.name] ,blobElt,nowEltName,curFileEntry.name,curFileEntry.type,cur_uploader_option,listEl,parentEl, newInputEl, inputEl,exist_selected_file_num,index) ;                      
                    }
                    else {
                        if(file_ext_name.toLowerCase()=='heic' || file_ext_name.toLowerCase()=='heif' ) {
                            rbupld_image_handling_numSet[index]++;
                            fetch(dataUrl[curFileEntry.name] )
                              .then((res) => res.blob())
                              .then((blob) => heic2any({
                                blob,
                                toType:"image/jpeg",
                                quality: 1
                              }))
                              .then((blob) => {
                                 create_img_to_resize(checkWidth,checkHeight,URL.createObjectURL(blob),blobElt,nowEltName,curFileEntry.name,blob.type,cur_uploader_option,listEl,parentEl, newInputEl, inputEl,exist_selected_file_num,index) ;

                              })
                              .catch((e) => {
                                console.log(e);
                                rbupld_image_handled_numSet[index]++;
                                resize_pic_loading_close(cur_uploader_option,listEl,parentEl, newInputEl, inputEl);                          
                              });                    
                        }
                        else {
                            rbupld_not_support_file_numSet[index]++;
                            rbupld_add_not_support_file_numSet[index]++;
                            evt.target.not_support_file=1;

                            if(rbupld_not_support_file_numSet[index]==fileSelected.length) {
                                var not_support_msg = '不支援的檔案格式，可能造成上傳失敗，請檢查所選取的檔案';
                                alert(not_support_msg);    
                                resize_pic_loading_close(cur_uploader_option,listEl,parentEl, newInputEl, inputEl); 
                               
                            }
                            else if((exist_selected_file_num+rbupld_add_not_support_file_numSet[index]+rbupld_image_handled_numSet[index])==fileSelected.length) {
                                alert('所選取的檔案中，有'+rbupld_not_support_file_numSet[index]+'個檔案將不會被上傳，因檔案格式不被支援。');
                                resize_pic_loading_close(cur_uploader_option,listEl,parentEl, newInputEl, inputEl); 
                            }
                            
                        }
                    }                    
                }
                resize_before_upload_fileReaderSet[index][curFileEntry.name].readAsDataURL(curFileEntry.file);
                


            }  
            
            if(needless_resize ||  ((exist_selected_file_num+rbupld_not_support_file_numSet[index])==fileSelected.length)) {
                resize_pic_loading_close(cur_uploader_option,listEl,parentEl, newInputEl, inputEl);               
            }
           
        });  

        var form_showed_container = null;
         if(outer_selector!='' && outer_selector!=undefined  && outer_selector!=null)
            form_showed_container = cur_uploader_api.getParentEl().closest(outer_selector);
        else outer_selector='body';
        var curUploaderFormElt = cur_uploader_api.getParentEl().closest('form');
        curUploaderFormElt.on('reset',function(){
            var uploader_input_elt = $(this).find('.fileuploader-thumbnails-input');
            if(uploader_input_elt.length==1 && uploader_input_elt.css('display')=='none') {
                uploader_input_elt.show();
            } 
        });
        curUploaderFormElt.on('submit',function(evt){

            var cur_uploader_option = cur_uploader_api.getOptions();
            loading();
            if(cur_uploader_option.beforeSubmit!=undefined){
                cur_uploader_option.beforeSubmit(evt,cur_uploader_api);
            }            
            
            var nowElt = $(evt.target);
            var nowFormElt = nowElt;
            var realUploadingFiles = cur_uploader_api.getChoosedFiles();

            var realUploadFileArr = [];
            for(var k in realUploadingFiles) {
               realUploadFileArr.push(realUploadingFiles[k].name);
            }

            fdata = new FormData();
            var fdata_value_count=0;
            $.each(nowFormElt.serializeArray(), function( index, value ) {
                fdata.append(value.name, value.value);
                fdata_value_count++;
            });
            
            realBlobIndex = 0;
            var fileElt = nowFormElt.find('input[type=file]');

            if(cur_uploader_api.getChoosedFiles().length==0 || cur_uploader_api.getChoosedFiles().length>rbupld_not_support_file_numSet[index]) 
            {
                for(f=0;f<fileElt.length;f++) {
                    var curFileElt = fileElt.eq(f);
                    fileEltFiles = curFileElt.prop('files');
                    fileEltName = curFileElt.prop('name');
                    fileEltNameSplit = fileEltName.split('[');
                    if(fileEltNameSplit[fileEltNameSplit.length-1]==']') {
                        fileEltNameSplit.splice(fileEltNameSplit.length-1,1);
                        postFileName = fileEltNameSplit.join('[');
                        postFileName_s = postFileName+'_s[]';
                        postFileName+='[]';
                    }
                    else {
                        postFileName=fileEltName;
                        postFileName_s=fileEltName+'_s';
                    }
                    for(i=0;i<fileEltFiles.length;i++) {
                        fileInputed = fileEltFiles[i];
                        filenamesplit = fileInputed.name.split('.');
                        file_ext_name = filenamesplit[filenamesplit.length-1];
                        filenamesplit.splice(filenamesplit.length-1,1);
                        filename_s = filenamesplit.join('.')+'_s.'+file_ext_name; 

                        if(realUploadFileArr.indexOf(fileInputed.name)>=0) {

                            if(blobElt[fileEltName]!=undefined && blobElt[fileEltName][fileInputed.name]!=undefined) {
                                var cur_filename_splits = fileInputed.name.split('.');
                                var cur_ext_filename = cur_filename_splits.pop(); 

                                if(cur_ext_filename.toLowerCase()=='heic' || cur_ext_filename.toLowerCase()=='heif') {
                                     var cur_new_file_name = cur_filename_splits.join('.')+'.jpg';
                                    fdata.append(postFileName, blobElt[fileEltName][fileInputed.name],cur_new_file_name);
                                    var new_list_item_val = listInputElt.val().replace(fileInputed.name,cur_new_file_name);
                                    listInputElt.val(new_list_item_val);
                                    fdata.set(listInputElt.attr('name'),new_list_item_val);                              
                                }
                                else  fdata.append(postFileName, blobElt[fileEltName][fileInputed.name],fileInputed.name);
                            }

                        }
                    }
                }

                $.ajax({
                    type: nowFormElt.attr('method'),
                    url: nowFormElt.attr('action'),
                    data: fdata,
                    dataType:returnDataType,
                    contentType: false, 
                    processData: false, 
                    rbupld_not_support_file_num:rbupld_not_support_file_numSet[index],
                    mimeType: nowFormElt.attr('enctype'),        
                    error: function(xhr, status, error){
                        $(document).on('click','.blbg,.announce_bg',closeAndReload); 
                        pop_content = '上傳失敗：'+error+'('+xhr.status+')';
                        if(error=='Unauthorized' && xhr.status==401) {
                            pop_content = '上傳失敗：您已登出或基於帳號安全由系統自動登出，請重新登入。';
                            pop_type='show_pop_message';
                        }
                        if(pop_type=='show_pop_message') {
                            pop_container_selector = '';

                            if(pop_content!='') show_pop_message(pop_content);
                        }
                        else if(pop_type=='c5')  {
                            
                          
                            if(pop_content!='') c5(pop_content);
                        }
                        if(form_showed_container!=null && form_showed_container!=undefined )
                            form_showed_container.hide();
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                        if(cur_uploader_option.afterSubmit!=undefined){
                            cur_uploader_option.afterSubmit(evt,cur_uploader_api);
                        }                     
                    },  
                    success: function(data,status,xhr) { 
                        rbupld_not_support_file_num = this.rbupld_not_support_file_num;
                        if(cur_uploader_option.beforeSubmitedSuccess!=undefined){
                            cur_uploader_option.beforeSubmitedSuccess(data,status,xhr,this,cur_uploader_api);
                        }                    
                        returnDataType = returnDataType.toLowerCase();
                        pop_message = '';
                        return_url ='';
                        $("#tab_loading").hide();
                        if(form_showed_container!=null && form_showed_container!=undefined )
                            form_showed_container.hide();                
            
                        if(data==1) {
                            pop_message = '上傳成功';
                            if(rbupld_not_support_file_num>0) {
                                pop_message+='。但其中有'+rbupld_not_support_file_num+'個檔案格式不被支援的檔案未被上傳。';
                            } 
                        }

                        if(returnDataType=='text') {
                            if(data.length>2500) {
                                pop_message = '上傳結束，請確認檔案是否無誤，如有問題請聯絡我們';
                                var error_keyword = [
                                ['401 Unauthorized']
                                , ['404 錯誤']
                                , ['網頁過期','可能閒置過久導致頁面過期']
                                , ['連線過多','目前連線次數過多','強制登出']
                                , ['503 錯誤']
                                , ['發生錯誤','網站目前正在更新','請半小時後重試']
                                , ['發生不明錯誤','系統錯誤']
                                , ['錯誤：沒有資料','使用者已關閉帳號']
                                , ['被封鎖了','如有誤封','請點選網頁右下方的聯絡我們']
                                , ['被封鎖了','已在被封鎖的會員列表中','詳情請洽站長']
                                , ['未知的錯誤','發生未預期錯誤']
                                ];
                                
                                var is_all_finded = false;
                                var err_page_msg = '';
                                
                                for(var ei=0;ei<error_keyword.length;ei++) {
                                    
                                    for(var ek=0;ek<error_keyword[ei].length;ek++) {
                                        if(ek==0 || is_all_finded) is_all_finded = (data.indexOf(error_keyword[ei][ek])>=0);
                                    } 

                                    if(is_all_finded) {     
                                        pop_message = error_keyword[ei].join('，')
                                        break;
                                    }
                                }
                                
                                if(!is_all_finded) {
                                    var logout_keyword = ['註冊','登入','忘記密碼','還沒有帳號' ,'免費註冊','login','name="login"','id="login"'];
                                    var logout_all_finded = false;
                                    
                                    for(var lgi=0;lgi<logout_keyword.length;lgi++) {
                                        if(lgi==0 || logout_all_finded) logout_all_finded = (data.indexOf(logout_keyword[lgi])>=0);
                                    }    

                                    if(logout_all_finded) pop_message='因帳號已登出所以上傳失敗！請重新登入';
                                }
                                
                            }
                            else if(data!=1){
                                pop_message = data;
                                if(rbupld_not_support_file_num>0 && pop_message.indexOf('成功')==(pop_message.length-2)) {
                                    pop_message+='。但其中有'+rbupld_not_support_file_num+'個檔案格式不被支援的檔案未被上傳。';
                                }
                            }
                        }
                        else if(returnDataType=='json') {
                            if(data.message!=undefined)
                                pop_message = data.message;
                            else if(data.content!=undefined) {
                                pop_message = data.content;
                            }
                            
                            if(rbupld_not_support_file_num>0 && pop_message.indexOf('成功')==(pop_message.length-2)) {
                                pop_message+='。但其中有'+rbupld_not_support_file_num+'個檔案格式不被支援的檔案未被上傳。';
                            }                                                       
                            
                            if(data.return_url!=undefined) {
                                return_url = data.return_url;
                            }                            
                        }
                        
                        if(return_url=='')  $(document).on('click','.blbg,.announce_bg',closeAndReload); 
                        
                        if(pop_type=='show_pop_message') {
                            if(return_url!='') {
                                pop_container_selector = '#tabPopM';
                                $(pop_container_selector+' .n_bllbut').attr('href',return_url).attr('onclick','');
                                $(document).on('click',pop_container_selector+' .n_bllbut,.blbg,.announce_bg',function(){
                                    location.href=return_url;
                                    return false;
                                });
                            }                 
                            
                            if(pop_message!='') show_pop_message(pop_message);
                            cur_uploader_api.reset();
                            nowElt.find('.fileuploader-thumbnails-input').show();
                        }
                        else if(pop_type=='c5') {
                            if(return_url!='') {
                                pop_container_selector = '#tab05';
                                $(pop_container_selector+' .n_bllbut').attr('href',return_url).attr('onclick','');
                                
                                $(document).on('click',pop_container_selector+' .n_bllbut,.blbg,.announce_bg',function(){
                                    location.href=return_url;
                                    return false;
                                });
                            }                  
                            if(pop_message!='') c5(pop_message); 
                            cur_uploader_api.reset();
                            nowElt.find('.fileuploader-thumbnails-input').show();
                        }  

                        if(cur_uploader_option.afterSubmitedSuccess!=undefined){
                            cur_uploader_option.afterSubmitedSuccess(data,status,xhr,this,cur_uploader_api);
                        } 
                        
                        if(cur_uploader_option.afterSubmit!=undefined){
                            cur_uploader_option.afterSubmit(evt,cur_uploader_api);
                        }                       
                    },            
                }); 
            }
            else {
                cur_uploader_api.reset();
                nowElt.find('.fileuploader-thumbnails-input').show();                
                if(cur_uploader_option.afterSubmit!=undefined){
                    cur_uploader_option.afterSubmit(evt,cur_uploader_api);
                } 
                if(rbupld_not_support_file_num>0) {
                    var not_support_msg = '上傳失敗：所選取的檔案皆為不被支援的檔案格式，請重新操作';

                    if(pop_type=='show_pop_message') {
                        show_pop_message(not_support_msg);
                    }
                    else if(pop_type=='c5')  {
                        c5(not_support_msg);
                    }   
                }
            }

            rbupld_not_support_file_num = 0;
            resize_before_upload_fileReaderSet[index] = {};        
            return false;

        });    
    });                                     
                                    

    
}


function create_img_to_resize(checkWidth,checkHeight,dataUrl,blobElt,eltName,fileName,fileType,cur_uploader_option,listEl,parentEl, newInputEl, inputEl,exist_selected_file_num,index) {
    var img = new Image();
    img.src = dataUrl;

    img.onload = function() {
       var width = this.width, 
       height = this.height, 
       compressRatio = 1,
       canvas = document.createElement("canvas"),
       context = canvas.getContext("2d"),
       html = "",
       newImg;
       if(height>checkHeight) {
           if(width<=checkWidth || height>=width) {
               imgNewWidth = checkHeight * width / height; 
               imgNewHeight = checkHeight;
           }
           else {
               imgNewHeight=checkWidth * height / width; 
               imgNewWidth = checkWidth;
           }
       }
       else if(width>checkWidth) {
           imgNewHeight=checkWidth * height / width; 
           imgNewWidth = checkWidth;
       }
       else {imgNewHeight=height;imgNewWidth=width;}    

       canvas.width = imgNewWidth;
       canvas.height = imgNewHeight;
       context.clearRect(0, 0, imgNewWidth, imgNewHeight);

       context.drawImage(img, 0, 0, imgNewWidth, imgNewHeight);

       newImg = canvas.toDataURL(fileType, compressRatio);

       canvas.toBlob(function(blob) {
           if(typeof(blobElt[eltName])=='undefined') blobElt[eltName] = [];
           blobElt[eltName][fileName]=blob;

           rbupld_image_handled_numSet[index]++;

           if(rbupld_image_handling_numSet[index]==rbupld_image_handled_numSet[index]) { 
               var cur_api = $.fileuploader.getInstance(inputEl.get(0));
               var cur_fileSelected = cur_api.getChoosedFiles();               

               if(rbupld_not_support_file_numSet[index]>0 
                && (exist_selected_file_num+rbupld_image_handled_numSet[index]+rbupld_add_not_support_file_numSet[index])==cur_fileSelected.length
               ) {
                   alert('所選取的檔案中，有'+rbupld_not_support_file_numSet[index]+'個檔案將不會被上傳，因檔案格式不被支援。');
               }
               
               resize_pic_loading_close(cur_uploader_option,listEl,parentEl, newInputEl, inputEl);
               rbupld_image_handling_numSet[index]=rbupld_image_handled_numSet[index]=0;               
           }
       }, fileType, compressRatio);
       context.clearRect(0, 0, canvas.width, canvas.height);
    };     
}

function convert_format_msg(){
    $(".blbg").hide();
    $("#tabPopM").hide(); 
    
    $(document).off('click','#tabPopM .n_bllbut,.blbg,#tabPopM .bl_gb',convert_format_msg);

    
    return false;
}

function resize_pic_loading_close(cur_uploader_option,listEl,parentEl, newInputEl, inputEl) {
        $(document).off('click','#tabPopM .n_bllbut,.blbg,#tabPopM .bl_gb',convert_format_msg);
        $(document).on('click','.blbg',closeAndReload); 
        $(".announce_bg").attr('onclick',org_announce_bg_onclick_value);
        var cur_api = $.fileuploader.getInstance(inputEl.get(0));
        if(rbupld_container_initial_visible[cur_api.rbupld_uploader_index]!='none') {
            //$(".blbg").hide();
            $(".announce_bg").hide();
        }

		$("#tab_loading").hide();  
        $('#tabPopM').hide();   
        $('#tab05').hide();
        //$('.announce_bg').hide();
        if(cur_uploader_option.afterResize!=undefined) {
           cur_uploader_option.afterResize(listEl,parentEl, newInputEl, inputEl);            
        }                         
}