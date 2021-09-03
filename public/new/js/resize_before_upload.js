function resize_before_upload(checkWidth
                                ,checkHeight,form_selector='form'
                                ,file_selector='input[type=file]'
                                ,fileupload_list_selector="input[name^='fileuploader-list-']"
                                ,submit_selector='input[type=submit]'
                                ,outer_selector=''
                                ,returnDataType='text'
                                ,pop_type='show_pop_message'
                                ) {
    var compressRatio = 1,
    checkHeight = checkHeight,
    checkWidth =checkWidth,
    imgNewHeight = checkHeight, 
    imgNewWidth =checkWidth, 
    canvas = document.createElement("canvas"),
    context = canvas.getContext("2d"),
    file, fileReader, dataUrl;
    blobElt = []; 

    $(form_selector).on('change',file_selector,function() {
        console.log(this.name);
        var nowEltName = this.name;
        canvas = document.createElement("canvas"),context = canvas.getContext("2d");

        fileSelected = this.files;
        fileReaderSet = [];        
        console.log(fileSelected);
        for(i=0;i<fileSelected.length;i++) {
            canvas = document.createElement("canvas"),context = canvas.getContext("2d");

            let curFileEntry = fileSelected[i];
            if (curFileEntry && curFileEntry.type.indexOf("image") == 0) {
                fileReaderSet[curFileEntry.name+curFileEntry.size.toString()]  = new FileReader();

                fileReaderSet[curFileEntry.name+curFileEntry.size.toString()].onload = function(evt) {
                   var img = new Image();
                   dataUrl = evt.target.result,
                   img.src = dataUrl;

                   img.onload = function() {
                       var width = this.width, 
                       height = this.height, 
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
                       console.log('curFileEntry.type='+curFileEntry.type);

                       newImg = canvas.toDataURL(curFileEntry.type, compressRatio);

                       canvas.toBlob(function(blob) {
                           if(typeof(blobElt[nowEltName])=='undefined') blobElt[nowEltName] = [];
                           blobElt[nowEltName][curFileEntry.name+curFileEntry.size.toString()]=blob;
                           console.log('curFileEntry.type='+curFileEntry.type);
                       }, curFileEntry.type, compressRatio);
                       context.clearRect(0, 0, canvas.width, canvas.height);
                   };                        
                };

                fileReaderSet[curFileEntry.name+curFileEntry.size.toString()].readAsDataURL(curFileEntry);
            }
        }
    });  

    var form_showed_container = null;
     if(outer_selector!='' && outer_selector!=undefined  && outer_selector!=null)
        form_showed_container = $(outer_selector);
    else outer_selector='body';
    $(outer_selector).on('submit',form_selector,function(evt){
        loading();
        var nowElt = $(evt.target);
        console.log(form_selector);
        var nowFormElt = nowElt;
        nowBtnElt = nowFormElt.find(submit_selector);
        var realUploadingFiles = {};
        if(nowFormElt.find(fileupload_list_selector).length>0 ) {
            var nowFormUploaderElt =nowFormElt.find(fileupload_list_selector);
            var nowUploaderVal = nowFormUploaderElt.val();
            if(nowUploaderVal!=undefined && nowUploaderVal!='' && nowUploaderVal!=null) {
                realUploadingFiles = JSON.parse(nowFormElt.find(fileupload_list_selector).val());
            }
        }
        
        var realUploadFileArr = [];
        for(var k in realUploadingFiles) {
           realUploadFileArr.push(realUploadingFiles[k]['file'].replace('0:/',''));
        }
        console.log(realUploadFileArr);
        console.log(nowFormElt);
        nowBtnElt.off( "click", "**" );
        fdata = new FormData();
 
        $.each(nowFormElt.serializeArray(), function( index, value ) {
            fdata.append(value.name, value.value);
        });
        realBlobIndex = 0;
        fileElt = nowFormElt.find('input[type=file]');
        for(f=0;f<fileElt.length;f++) {
            let curFileElt = fileElt.eq(f);
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

                if(realUploadFileArr.indexOf(fileInputed.name)>=0)
                    fdata.append(postFileName, blobElt[fileEltName][fileInputed.name+fileInputed.size.toString()],fileInputed.name);
            }
        }
        console.log(fdata);
        console.log(nowFormElt.attr('method'));
        console.log(nowFormElt.attr('action'));
        console.log(nowFormElt.attr('enctype'));
        $.ajax({
            type: nowFormElt.attr('method'),
            url: nowFormElt.attr('action'),
            data: fdata,
            dataType:returnDataType,
            contentType: false, 
            processData: false, 
            mimeType: nowFormElt.attr('enctype'),        
            error: function(xhr, status, error){
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
                console.log(returnDataType);
                $(".loading").hide(); 
                if(form_showed_container!=null && form_showed_container!=undefined )
                    form_showed_container.hide();                

                if(returnDataType=='text') {
                    if(data==1 || data.length>250) {
                        pop_message = '上傳成功';
                    }
                    else {
                        pop_message = data;
                    }
                }
                else if(returnDataType=='json') {
                    console.log(data.message);
                    if(data.message!=undefined)
                        pop_message = data.message;
                    if(data.return_url!=undefined)
                        return_url = data.return_url;
                }
                
                if(pop_type=='show_pop_message') {
                    if(return_url!='') {
                        pop_container_selector = '#tabPopM';
                        $(pop_container_selector+' .n_bllbut').attr('href',return_url).attr('onclick','');
                        
                        $(document).on('click',pop_container_selector+' .n_bllbut',function(){
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
                        
                        $(document).on('click',pop_container_selector+' .n_bllbut',function(){
                            location.href=return_url;
                            return false;
                        });
                    }                  
                    c5(pop_message); 

                }
                console.log(data);            
            },            
        }); 

        return false;

    });    
    
}