image_handling_num=0;
image_handled_num=0;
function resize_before_upload(uploader,checkWidth
                                ,checkHeight
                                ,outer_selector=''
                                ,returnDataType='text'
                                ,pop_type='show_pop_message'
                                ) {
    uploader.each(function( index,curUploaderElt ) {
        var 
        imgNewHeight = checkHeight, 
        imgNewWidth =checkWidth, 
        file, fileReader, dataUrl=[];

        var cur_uploader_api = $.fileuploader.getInstance($(curUploaderElt));
        var blobElt = []; 
        var blobEltOfIos = [];
        var listInputElt = cur_uploader_api.getListInputEl();

        cur_uploader_api.setOption('afterSelect',function(istEl,parentEl, newInputEl, inputEl){

            image_handling_num=0;
            image_handled_num=0;    
            
            org_announce_bg_onclick_value = $(".announce_bg").attr('onclick');
            $(".announce_bg").attr('onclick','');
            
            var nowEltName = cur_uploader_api.getInputEl().attr('name');
            var hasHeicType = false;
            var hasHeifType = false;

            var fileSelected = cur_uploader_api.getChoosedFiles();

            if(fileSelected.length>0) loading();
            else return false;
            var fileReaderSet = []; 
         
            for(i=0;i<fileSelected.length;i++) {
                let curFileEntry = fileSelected[i];
                fileReaderSet[curFileEntry.name]  = new FileReader();

                fileReaderSet[curFileEntry.name].onload = function(evt) {
  
                    dataUrl[curFileEntry.name] = evt.target.result;
                    
                    file_name_splits = curFileEntry.name.split('.');
                    file_ext_name = file_name_splits.pop();                    
                    
                    if (curFileEntry && curFileEntry.type.indexOf("image") == 0
                        && file_ext_name.toLowerCase()!='heif'
                        && file_ext_name.toLowerCase()!='heic'
                    ) 
                    {
                        image_handling_num++;
                        create_img_to_resize(checkWidth,checkHeight,dataUrl[curFileEntry.name] ,blobElt,nowEltName,curFileEntry.name,curFileEntry.type) ;
                    }
                    else {
                        if(file_ext_name.toLowerCase()=='heic' || file_ext_name.toLowerCase()=='heif' ) {
                            image_handling_num++;
                            fetch(dataUrl[curFileEntry.name] )
                              .then((res) => res.blob())
                              .then((blob) => heic2any({
                                blob,
                                toType:"image/jpeg",
                                quality: 1
                              }))
                              .then((blob) => {
                                 create_img_to_resize(checkWidth,checkHeight,URL.createObjectURL(blob),blobElt,nowEltName,curFileEntry.name,blob.type) ;

                              })
                              .catch((e) => {
                                console.log(e);
                                image_handled_num++;
                              });                    
                        }
                    }                    
                }
                fileReaderSet[curFileEntry.name].readAsDataURL(curFileEntry.file);

            }
        });  

        var form_showed_container = null;
         if(outer_selector!='' && outer_selector!=undefined  && outer_selector!=null)
            form_showed_container = cur_uploader_api.getParentEl().closest(outer_selector);
        else outer_selector='body';
        var curUploaderFormElt = cur_uploader_api.getParentEl().closest('form');
        curUploaderFormElt.on('submit',function(evt){
            loading();
            var nowElt = $(evt.target);
            var nowFormElt = nowElt;
            var realUploadingFiles = cur_uploader_api.getChoosedFiles();

            var realUploadFileArr = [];
            for(var k in realUploadingFiles) {
               realUploadFileArr.push(realUploadingFiles[k].name);
            }

            fdata = new FormData();
     
            $.each(nowFormElt.serializeArray(), function( index, value ) {
                fdata.append(value.name, value.value);
            });

            realBlobIndex = 0;
            fileElt = nowFormElt.find('input[type=file]');

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
                mimeType: nowFormElt.attr('enctype'),        
                error: function(xhr, status, error){
                    $(document).on('click','.blbg,.announce_bg',closeAndReload); 
                    pop_content = '上傳失敗：'+error+'('+xhr.status+')';

                    if(pop_type=='show_pop_message') {
                        pop_container_selector = '';

                        show_pop_message(pop_content);
                    }
                    else if(pop_type=='c5')  {
                        
                      
                        c5(pop_content);
                    }
                    if(form_showed_container!=null && form_showed_container!=undefined )
                        form_showed_container.hide();
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                },  
                success: function(data) { 
                    returnDataType = returnDataType.toLowerCase();
                    pop_message = '';
                    return_url ='';
                    $(".loading").hide(); 
                    if(form_showed_container!=null && form_showed_container!=undefined )
                        form_showed_container.hide();                

                    if(data==1) {
                        pop_message = '上傳成功';
                    }

                    if(returnDataType=='text') {
                        if(data.length>2500) {
                            pop_message = '上傳成功';
                        }
                        else {
                            pop_message = data;
                        }
                    }
                    else if(returnDataType=='json') {
                        if(data.message!=undefined)
                            pop_message = data.message;
                        else if(data.content!=undefined) {
                            pop_message = data.content;
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
                        
                        show_pop_message(pop_message);
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
                        c5(pop_message); 

                    }           
                },            
            }); 

            return false;

        });    
    });                                     
                                    

    
}


function create_img_to_resize(checkWidth,checkHeight,dataUrl,blobElt,eltName,fileName,fileType) {

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

           image_handled_num++;
           if(image_handling_num==image_handled_num) {
               resize_pic_loading_close();
               image_handling_num=image_handled_num=0;
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

function resize_pic_loading_close() {
        $(document).off('click','#tabPopM .n_bllbut,.blbg,#tabPopM .bl_gb',convert_format_msg);
        $(document).on('click','.blbg',closeAndReload); 
        $(".announce_bg").attr('onclick',org_announce_bg_onclick_value);
		$(".announce_bg").hide();
        $(".blbg").hide();
		$("#tab_loading").hide();  
        $('#tabPopM').hide();   
        $('#tab05').hide();   
}