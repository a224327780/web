var App = App || {};
App.isJson = function (obj) {
    return typeof (obj) === "object" && Object.prototype.toString.call(obj).toLowerCase() === "[object object]" && !obj.length;
};

App.message = function (msg, time) {
    let t = time || 3000;
    let $alert = $('<div />', {
        'class': 'alert alert-warning',
        'html': [
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>',
            msg
        ]
    });
    let obj = $('.right, .login-error');
    obj.find('.alert').remove();
    obj.append($alert);
    window.setTimeout(function () {
        $('.alert').fadeOut(300, function () {
            $(this).remove();
        });
    }, t);
};

App.ajax = {};
App.ajax.request = function ($this, method, option) {
    let options = $.extend({type: 'json'}, option);
    let text = $this.attr('title') || $this.data('original-title') || $this.val() || $this.text();
    let l = Ladda.create($this[0]);
    l.start();

    $.ajax({
        type: method,
        url: options.url || $this.attr('href'),
        data: options.data,
        dataType: options.type || 'json',
        context: $this,
        beforeSend: options.beforeSend || function () {
            // $.jBox.tip('正在' + text + '...', 'loading');
            $this.attr('disabled', 'disabled').addClass('disabled');
            // $('<i />', {'class': 'fa fa-spinner fa-spin'}).prependTo($this)
        },
        error: options.error || function (jqXHR, statusText, error) {
            var title = typeof error === 'object' ? statusText : error;
            $this.removeAttr('disabled', 'disabled').removeClass('disabled');
            App.message(jqXHR.responseText, 5000);
            l.stop();
        },
        success: options.success || function (result) {
            if (!App.isJson(result)) {
                App.message(result, 'error');
                l.stop();
                return;
            }
            if (result.code === 0) {
                window.setTimeout(function () {
                    let redirect = $this.attr('redirect') || result['redirect'] || false;
                    if (redirect) {
                        window.location.href = redirect;
                    } else {
                        window.location.reload();
                    }
                }, 2000);
                return;
            }
            App.message(result['msg'], 5000);
            $this.removeAttr('disabled', 'disabled').removeClass('disabled');
            l.stop();
        }
    });
};

App.ajax.get = function ($this, options) {
    App.ajax.request($this, 'GET', options);
};

App.ajax.post = function ($this, option) {
    var options = option || {};
    if (!options.data && !options.url) {
        var form = $this.parents('form');
        var url = form.attr('action');
        var data = form.serialize();
        options = $.extend({'data': data, 'url': url}, options);
    }
    App.ajax.request($this, 'POST', options);
};

(function ($) {
    $.fn.ajaxGet = function (options) {
        return this.each(function () {
            App.ajax.get($(this), options);
        });
    };
})(jQuery);

(function ($) {
    $.fn.ajaxPost = function (options) {
        return this.each(function () {
            App.ajax.post($(this), options);
        });
    };
})(jQuery);

(function ($) {

    $('[data-toggle="tooltip"]').tooltip();

    $('.hint-block').each(function () {
        var $hint = $(this);
        $hint.parent().find('input, textarea').attr('placeholder', $hint.html());
        // $hint.parent().find('label').addClass('help').popover({
        //     html: true,
        //     trigger: 'hover',
        //     placement: 'right',
        //     content: $hint.html()
        // });
    });
})(jQuery);

$(function () {
    $(document).on('afterValidateAttribute', 'form', function (event, attribute, message) {
        var $form = $(this);
        var $error = $form.find(attribute.container).find(attribute.error);
        var px = message.length > 0 ? '-10px' : 0;
        $error.css('margin-bottom', px);
        return false;
    });

    $(document).on('beforeSubmit', 'form', function () {
        var form = $(this);
        if (form.find('.has-error').length) {
            return false;
        }
        form.find(':submit').ajaxPost();
        return false;
    });
});

$(function () {
    $('.empty').parent('td').css('border-bottom', 'none');

    $('.glyphicon-search').on('click', function () {
        $(this).parent('form').submit();
    });

    $('.search input[name="wd"]').on('keypress', function (event) {
        if (event.keyCode === 13) {
            $(this).parent('form').submit();
        }
    });

    var $alert = $('.alert');
    if ($alert.length) {
        window.setTimeout(function () {
            $alert.fadeOut(500, function () {
                $alert.remove();
            });
        }, 5000);
    }
});