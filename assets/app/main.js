/**
 * Created with JetBrains PhpStorm.
 * User: Administrator
 * Date: 12-11-6
 * Time: 上午10:22
 * To change this template use File | Settings | File Templates.
 */
$(function () {

    $(".navi li").hover(function () {
        $(this).find('ul:first').slideDown("fast").css({visibility: "visible", display: "block"});
    }, function () {
        $(this).find('ul:first').slideUp("fast").css({visibility: "hidden"});
    });
    if ($(".navi li ul li:has(ul)").length > 0) {
        $(".navi li ul li ul").parent().addClass("arrow");
    }

    if ($('#slideshow').length) {
        $('#slideshow').bxSlider({
            mode: 'fade',
            controls: false,
            auto: true,
            speed: 800,
            pause: 5000,
            pager: true
        });
    }


    $(".widget,.news,.box").each(function () {
        $(this).find("li:last").css('border-bottom', 'none');
    });

    $(".searchInput").hover(function () {
        if ($(this).val() == '请输入关键字!') {
            $(this).val('');
        }
        $(this).focus();
    }, function () {
        if ($(this).val() == '') {
            $(this).val('请输入关键字!');
        }
        $(this).blur();
    });

    $(".searchBtn").click(function () {
        var obj = $(this).parent().find(".searchInput");
        var key = $.trim(obj.val());
        if (key == '' || key == '请输入关键字!') {
            obj.focus();
        }
        window.location = weburl + "search/" + encodeURI(key) + "/";
        return false;
    });

    $(".searchInput").keydown(function (event) {
        var obj = $(this).parent().find(".searchBtn");
        if (event.which == 13) {
            obj.trigger('click');
        }
    });

    $("#favorite").click(function () {
        AddFavorite(document.location, document.title);
        return false;
    });
    $("#home").click(function () {
        setHomepage(document.location);
        return false;
    });
});

function AddFavorite(sURL, sTitle) {
    try {
        window.external.addFavorite(sURL, sTitle);
    } catch (e) {
        try {
            window.sidebar.addPanel(sTitle, sURL, "");
        } catch (e) {
            alert("浏览器不支持自动添加收藏,请手动添加.");
        }
    }
}

function setHomepage(pageURL) {
    if (document.all) {
        document.body.style.behavior = 'url(#default#homepage)';
        document.body.setHomePage(pageURL);
    } else if (window.sidebar) {
        if (window.netscape) {
            try {
                netscape.security.PrivilegeManager
                    .enablePrivilege("UniversalXPConnect");
            } catch (e) {
                alert("该操作被浏览器拒绝，如果想启用该功能，请在地址栏内输入 about:config,然后将项signed.applets.codebase_principal_support 值该为true");
            }
        }
        var prefs = Components.classes['@mozilla.org/preferences-service;1']
            .getService(Components.interfaces.nsIPrefBranch);
        prefs.setCharPref('browser.startup.homepage', pageURL);
    }
}