
$(document).ready(function () {
    $("#search").click();
    var url = location.href;
    $(".nav li").removeClass("active");
    var lastID = url.substring(url.lastIndexOf("/") + 1, url.lastIndexOf("."));
    $("#" + lastID).addClass("active");

    //chiledren active
    var chiledren = $("#chiledren").val();

    if (url.indexOf(chiledren) > 0) {

        $("#" + chiledren + ">ul.children").css("display", "block");
        $("#" + chiledren).addClass("nav-active active");
    }



})



/**
 * @日期
 * format: 'yyyy-mm-dd',天
 * format: 'yyyy-mm-dd hh:ii:ss',小时
 * minView:显示最小视图
 *
 * ******日期*******/
$("#dateStart").datetimepicker({
    format: 'yyyy-mm-dd',
    minView: 'month',
    language: 'zh-CN',
    autoclose: true,
})
$("#dateEnd").datetimepicker({
    format: 'yyyy-mm-dd',
    minView: 'month',
    language: 'zh-CN',
    autoclose: true,
})


/**
 * @send data get Json
 * getData:get
 * submitNewChanne:form Post Method
 * ****/

function getData(targets) {

    $.ajax({
        type: "get",
        url: targets,
        data: {
            chtype: $("#selectChannel").text(),
            chname: $("#channelName").val(),
            datestart: $("#dateStart").val(),
            dateend: $("#dateEnd").val(),
            parent: $("#parentHidden").val(),
            manager: $("#selectManager").text(),
            new_page: $("#page").val(),
            process: $("#processHidden").val(),
            datesearch: $("#selectTime").text(),
        },
        dataTyep: "json",
        beforeSend: function () {
            $('#get_data_area').empty();
            $(".loading").show();
        },
        success: function (data) {
            $(".loading").hide();
        }
    });
}

function submitNewChanne(num) {


    var form = new FormData(document.getElementById(id));
    $.ajax({
        type: "post",
        url: "../News/" + target,
        processData: false,
        contentType: false,
        data: form,
        success: function (data) {

        },
        error: function (data) {
            console.log("this is error");
        }
    })
}

/**
 * @limit
 *
 ********/
function limitPage(TotalPage, Total, targets) {
    var totalPage = TotalPage;
    var totalRecords = Total;
    var pageNo = $("#page").val();
    if (!pageNo) {
        pageNo = 1;
    }
    kkpager.generPageHtml({
        pno: pageNo,
        total: totalPage,
        totalRecords: totalRecords,
        mode: 'click',
        click: function (n) {
            $("#page").val(n);
            getNextPage(targets);
            this.selectPage(n);
            return true;
        }
        , lang: {
            firstPageText: '首页',
            firstPageTipText: '首页',
            lastPageText: '尾页',
            lastPageTipText: '尾页',
            prePageText: '上一页',
            prePageTipText: '上一页',
            nextPageText: '下一页',
            nextPageTipText: '下一页',
            totalPageBeforeText: '共',
            totalPageAfterText: '页',
            currPageBeforeText: '第',
            currPageAfterText: '页',
            totalInfoSplitStr: '/',
            totalRecordsBeforeText: '共',
            totalRecordsAfterText: '条数据',
            gopageBeforeText: '',
            gopageButtonOkText: '确定',
            gopageAfterText: '页',
            buttonTipBeforeText: '第',
            buttonTipAfterText: '页'


        }
    }, true);
}





/**
 * channel_open
 *****/
var globalConfig = {};
globalConfig.paramAesKey = "miningaccountH5";

function generateH5Path(type) {
    var channelCode = $("#addch_code").val().trim();
    var activity = $("#activity").val().trim();
    if (activity == null || activity == undefined || activity == '') {
        activity = '';
    }
    if (!channelCode) {
        alert("请先填写渠道码");
        return;
    }

    var baseUrl = "https://accountapi.investassistant.com/miningaccount/accounth5/regist?activity=$$activity$$&channel_open=$$channel_open$$&hmsr=$$channel_open1$$&hmpl=&hmcu=&hmkw=&hmci=";
    var aesChannelCode = aesEncode(channelCode, globalConfig.paramAesKey).toString();

    aesChannelCode = formatUrlParam(aesChannelCode);
    var url = baseUrl.replace("$$channel_open$$", aesChannelCode);
    url = url.replace("$$channel_open1$$", channelCode);
    url = url.replace("$$activity$$", activity);

    $("#channaeCodeShow").text(channelCode);
    $("#addqr_link").val(url);

}


function formatUrlParam(param) {
    param = param.replaceAll("\\+", "*");
    param = param.replaceAll("\\/", "-");
    param = param.replaceAll("\\=", ".");
    return param;
}

function reverseFormatUrlParam(param) {
    param = param.replaceAll("\\*", "+");
    param = param.replaceAll("\\-", "/");
    param = param.replaceAll("\\.", "=");
    return param;
}

function aesEncode(data, val) {
    if (data) {
        val = format16Key(val);
        var key = CryptoJS.enc.Latin1.parse(val);
        var iv = CryptoJS.enc.Latin1.parse(val);
        var encrypted = CryptoJS.AES.encrypt(data, key, {
            iv: iv,
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        });
        return encrypted;
    }
}

// 解密url返回的参数
function aesDecode(data, val) {
    if (data) {
        val = format16Key(val);
        var key = CryptoJS.enc.Latin1.parse(val);
        var iv = CryptoJS.enc.Latin1.parse(val);
        var decrypted = CryptoJS.AES.decrypt(data, key, {
            iv: iv,
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        });
        return decrypted.toString(CryptoJS.enc.Utf8);
    }
}

// 格式化成16位的key
function format16Key(val) {
    var len = val.toString().length;
    var tmp = "0000000000000000";
    if (len < 16) {
        val += tmp.substring(0, 16 - len);
    }
    if (len > 16) {
        val = val.substring(0, 16);
    }
    return val;

}

String.prototype.replaceAll = function (s1, s2) {
    return this.replace(new RegExp(s1, "gm"), s2);
}


