function fileuploaderAjaxImage($target, opts) {
    let defaults = {
        url: null,
        ajaxDataImgName: "msg",
        limit: 6,
        extensions: ["jpg", "jpeg", "png", "gif"],
        fileMaxSize: 8,
        editor: {
            cropper: {
                ratio: null,
                minWidth: 400,
                minHeight: 360,
                showGrid: false,
            },
            quality: 100,
            maxWidth: null,
            maxHeight: null,
        },
    };
    opts = Object.assign(defaults, opts);

    return $target.fileuploader({
        changeInput: " ",
        theme: "thumbnails", // 使用 theme 記得要引入對應的 css
        skipFileNameCheck: true,
        enableApi: true,
        addMore: true,
        limit: opts.limit,
        extensions: opts.extensions, // 允許的檔案格式
        fileMaxSize: opts.fileMaxSize, // 單一檔案最大上傳檔案大小
        thumbnails: {
            box:
                '<div class="fileuploader-items">' +
                '<ul class="fileuploader-items-list">' +
                '<li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner"><i>+</i></div></li>' +
                "</ul>" +
                "</div>",
            item:
                '<li class="fileuploader-item file-has-popup">' +
                '<div class="fileuploader-item-inner">' +
                '<div class="actions-holder">' +
                '<a class="fileuploader-action fileuploader-action-sort" title="${captions.sort}"><i></i></a>' +
                '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i></i></a>' +
                "</div>" +
                '<div class="thumbnail-holder">' +
                "${image}" +
                '<span class="fileuploader-action-popup"></span>' +
                "</div>" +
                '<div class="progress-holder">${progressBar}</div>' +
                "</div>" +
                "</li>",
            item2:
                '<li class="fileuploader-item file-has-popup">' +
                '<div class="fileuploader-item-inner">' +
                '<div class="actions-holder">' +
                '<a class="fileuploader-action fileuploader-action-sort" title="${captions.sort}"><i></i></a>' +
                '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i></i></a>' +
                "</div>" +
                '<div class="thumbnail-holder">' +
                "${image}" +
                '<span class="fileuploader-action-popup"></span>' +
                "</div>" +
                '<div class="progress-holder">${progressBar}</div>' +
                "</div>" +
                "</li>",
            startImageRenderer: false, // ajax 上傳完再 render
            canvasImage: false,
            _selectors: {
                list: ".fileuploader-items-list",
                item: ".fileuploader-item",
                start: ".fileuploader-action-start",
                retry: ".fileuploader-action-retry",
                remove: ".fileuploader-action-remove",
            },
            onItemShow: function(item, listEl, parentEl, newInputEl, inputEl) {
                var plusInput = listEl.find(".fileuploader-thumbnails-input"),
                    api = $.fileuploader.getInstance(inputEl.get(0));

                plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? "hide" : "show"]();

                if (item.format == "image") {
                    item.html.find(".fileuploader-item-icon").hide();
                }
            },
            onItemRemove: function(html, listEl, parentEl, newInputEl, inputEl) {
                var plusInput = listEl.find(".fileuploader-thumbnails-input"),
                    api = $.fileuploader.getInstance(inputEl.get(0));

                html.children().animate({ opacity: 0 }, 200, function() {
                    html.remove();

                    if (api.getOptions().limit && api.getChoosedFiles().length - 1 < api.getOptions().limit) plusInput.show();
                });
            },
        },
        editor: opts.editor,
        dragDrop: {
            container: ".fileuploader-thumbnails-input",
        },
        afterRender: function(listEl, parentEl, newInputEl, inputEl) {
            var plusInput = listEl.find(".fileuploader-thumbnails-input"),
                api = $.fileuploader.getInstance(inputEl.get(0));

            plusInput.on("click", function() {
                api.open();
            });
        },
        upload: {
            data:opts.data,
            url: opts.url, // ajax 上傳位址
            type: "POST",
            enctype: "multipart/form-data",
            start: true, // 使用者選取圖片後直接上傳
            synchron: false,
            onSuccess: function(data, item) {
                item.html.find(".fileuploader-action-remove").addClass("fileuploader-action-success");

                item.name = data[opts.ajaxDataImgName]; // 修改 item 檔名，對應 server 圖片路徑
                setTimeout(function() {
                    item.html.find(".progress-holder").hide();
                    item.renderThumbnail();

                    item.html.find(".fileuploader-item-image").show();
                }, 400);
            },
            onError: function(item) {
                item.html.find(".progress-holder, .fileuploader-action-popup, .fileuploader-item-image").hide();
            },
            onProgress: function(data, item) {
                var progressBar = item.html.find(".progress-holder");

                if (progressBar.length > 0) {
                    progressBar.show();
                    progressBar.find(".fileuploader-progressbar .bar").width(data.percentage + "%");
                }

                item.html.find(".fileuploader-action-popup, .fileuploader-item-image").hide();
            },
        },
        sorter: {
            selectorExclude: null,
            placeholder: null,
            scrollContainer: window,
        },
        captions: {
            confirm: "確定",
            cancel: "取消",
            name: "檔名",
            type: "類型",
            size: "大小",
            dimensions: "寬高",
            duration: "Duration",
            crop: "裁切",
            rotate: "旋轉",
            sort: "排序",
            download: "下載",
            remove: "刪除",
            drop: "拖移",
            removeConfirmation: "確定要刪除檔案嗎?",
            button: "選擇檔案",
            feedback: "選擇檔案上傳",
            feedback2: "檔案已選取",
            errors: {
                filesLimit: "最多只允許上傳 ${limit} 張(含大頭照)的照片",
                filesType: "只允許上傳 ${extensions} 檔案格式",
                filesSize: "${name} 檔案太大! 檔案上傳上限為 ${maxSize} MB",
                filesSizeAll: "您選擇的檔案過大! 檔案上傳上限為 ${maxSize} MB",
            },
        },
    });
}
