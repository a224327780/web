;(function ($) {
    var methods = {};
    methods.init = function (options) {
        return this.each(function () {
            var $this = $(this);
            var id = $this.attr('id');

            // if (options.init) {
            //     for (var i in defaults.init) {
            //         if (options.init[i]) {
            //             defaults.init[i] = options.init[i];
            //         }
            //     }
            //     delete options.init;
            // }
            var settings = $.extend(defaults, {browse_button: id, id: id, _container: $this.parent()}, options);

            var csrf_token = $('meta[name=csrf-token]').attr('content');
            var csrf_param = $('meta[name=csrf-param]').attr('content');
            if (csrf_param && csrf_token) {
                settings.multipart_params[csrf_param] = csrf_token;
            }

            var uploader = new plupload.Uploader(settings);
            uploader.init();
        });
    };

    methods.error = function (msg) {
        alert(msg);
    };

    var previewImage = function (file, callback) {
        if (!file || !/image\//.test(file.type)) return;
        if (file.type == 'image/gif') {
            var fr = new mOxie.FileReader();
            fr.onload = function () {
                callback(fr.result);
                fr.destroy();
                fr = null;
            };
            fr.readAsDataURL(file.getSource());
            return;
        }
        var img = new mOxie.Image();
        img.onload = function () {
            img.downsize(300);
            var src = img.type == 'image/jpeg' ? img.getAsDataURL('image/jpeg') : img.getAsDataURL();
            callback && callback(src);
            img.destroy();
            img = null;
        };
        img.load(file.getSource());
    };

    var handlers = {
        postInit: function (uploader) {
            var settings = this.settings;
            if (settings.events.PostInit) {
                if (!settings.events.PostInit.call(this, uploader)) {
                    return;
                }
            }
            var $parent = settings._container;

            var $progress = $('<div />', {
                'class': 'progress progress-xs',
                'html': $('<div />', {'class': 'progress-bar'})
            });
            $parent.append($progress);
            $progress.hide();

            var $input = $('#' + settings.id);
            $input.css({'cursor': 'pointer'});

            var src = $input.val();
            var preview = $parent.find('.upload-preview');
            if (preview.length <= 0) {
                var img = '<img src="'+ src +'" class="upload-preview" style="display:none" />';
                $parent.append(img);
                preview = $parent.find('.upload-preview');
            }
            preview.css({'width': $input.outerWidth(), 'margin-top': '10px', 'max-width': '300px'}).fadeIn();

            preview.on('error', function () {
                $(this).hide();
            });
        },
        filesAdded: function (uploader, files) {
            uploader.start();
        },
        error: function (uploader, err) {
            methods.error(err['message'] + err['code'] + err['file']);
        },
        uploadProgress: function (uploader, file) {
            var settings = this.settings;
            var $progress = settings._container.find('.progress');
            $progress.show().find('.progress-bar').css('width', file.percent + "%");
        },
        fileUploaded: function (uploader, file, data) {
            var response = JSON.parse(data.response);
            if (response['code'] !== 0) {
                methods.error(response['msg']);
                return;
            }
            var result = response.data;

            var settings = this.settings;
            $('#' + settings.id).val(result['file']);

            if (settings.events.FileUploaded) {
                if (!settings.events.FileUploaded.call(this, uploader, result)) {
                    return;
                }
            }

            settings._container.find('.progress').hide();
            settings._container.find('.upload-preview').attr('src', result['src']).show();
        },
        uploadComplete: function (uploader, files) {
        }
    };

    var defaults = {
        container: undefined,
        flash_swf_url: '/assets/common/p-upload/upload.swf',
        url: '/default/upload',
        multipart_params: {},
        multi_selection: false,
        filters: {
            max_file_size: '5mb',
            mime_types: [{title: "Image files", extensions: "jpg,gif,png,jpeg"}]
        },
        init: {
            PostInit: handlers.postInit,
            FilesAdded: handlers.filesAdded,
            UploadProgress: handlers.uploadProgress,
            FileUploaded: handlers.fileUploaded,
            UploadComplete: handlers.uploadComplete,
            Error: handlers.error
        },
        events: {}
    };

    $.fn.pUpload = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        return methods.init.apply(this, arguments);
    };
})($);