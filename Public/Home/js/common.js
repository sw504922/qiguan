$(document).ready(function () {
    $("#search").click();
    var url = location.href;
    $(".nav li").removeClass("active");
    var lastID = url.substring(url.lastIndexOf("/") + 1, url.lastIndexOf("."));
    $("#" + lastID).addClass("active");


    var user_auth=$("#user_auth").val();
    if (user_auth=="转化人员" ){
        $(".authAll").hide();
    }

    if (user_auth=="商务人员" ){
        $(".authSWAll").hide();
    }


})

/**
 *@navigation set value
 * sheChannelType:渠道
 * sheProcessType:进度
 * sheParentType:推荐人
 * sheManagerType:商务
 *****/
function sheChannelType(str) {
    $("#selectChannel").text(str);
}

function sheProcessType(str, value) {
    $("#selectProcess").text(str);
    $("#processHidden").val(value);
}

function sheParentType(str, value) {
    $("#selectParent").text(str);
    $("#parentHidden").val(value);

}

function sheManagerType(str) {
    $("#selectManager").text(str);

}
function sheTimeType(str) {
    $("#selectTime").text(str);
}

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
            $("#get_data_area").html(data);
            $(".mainpanel").removeAttr("style");
            $(".mainpanel").css("overflow-x", "scroll");
            $(".headerbar").css("width",$(".dataTable").width()+110);
            $(".divBox,.headTop").css("width",$(".dataTable").width()+100);
        }
    });
}

function submitNewChanne(num) {

    if (typeof num == "undefined") {
        var id = "add_new_channel";
        target = "addChannelMethod";
    } else {
        var id = "update_new_channel" + num;
        target = "updateChannelMethod";
    }
    var status = checkInput(id, num);
    if (status == false) {
        return false;
    }

    var form = new FormData(document.getElementById(id));
    $.ajax({
        type: "post",
        url: "../Channel/" + target,
        processData: false,
        contentType: false,
        data: form,
        success: function (data) {
            if (id != "add_new_channel") {
                getChannelInfor("update", num, "close");
            } else {
                addLayer("addChannel", "close");
            }
            window.location.reload();
        },
        error: function (data) {
            console.log("this is error");
        }
    })
}

/**
 *@check
 ****/
function checkInput(id, num) {
    if (id == "add_new_channel") {
        if ($("#addcontact").val() == "") {
            alert("请输入姓名");
            return false;
        }

        if ($("#addfull_name").val() == "") {
            alert("请输入渠道名称");
            return false;
        }

        if ($("#addshort_name").val() == "") {
            alert("请输入渠道简称");
            return false;
        }

        if ($("#addch_code").val() == "") {
            alert("请输入渠道码");
            return false;
        }
        if ($("#addqr_link").val() == "") {
            alert("请输入渠道链接");
            return false;
        }
        if ($("#qr_code_file").val() == "") {
            alert("请上传二维码");
            return false;
        }
    } else {
        if ($("#update_contact" + num).val() == "") {
            alert("请输入姓名");
            return false;
        }

        if ($("#update_full_name" + num).val() == "") {
            alert("请输入渠道名称");
            return false;
        }

        if ($("#update_short_name" + num).val() == "") {
            alert("请输入渠道简称");
            return false;
        }

    }

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

function getNextPage(targets) {
    getData(targets);
}



/**
 *@layer
 * addLayer:多功能展示蒙层
 * getChannelInfor:用户信息编辑
 * addConversion:转化人功能
 *****/
var conversionBegin = 2;
function addLayer(id, status) {

    if (status == "close") {
        $("#" + id).hide();
    } else {
        $("#" + id).show();
        $(".conversion_bai").val("");
        for (i = conversionBegin; i < $(".conver").length; i++)
            $("#con" + i).hide();
    }
}

function getChannelInfor(str, id, status) {
    if (str == "update" && status != "close") {
        $("#channel_type_select" + id).val($("#channel_type" + id).val());
        $("#manager_select" + id).val($("#manager" + id).val());

        //转化人显示
        for (i = conversionBegin; i < $(".conver" + id).length; i++) {
            $("#con" + i + id).hide();
        }

        for (i = 0; i < $(".conver" + id).length; i++) {
            $("#coverName_select" + i + id).val($("#conversionNameArr" + i + id).val());

        }

    }else if(str == "water" && status != "close"){
        updateProcess(id,"getFlowerTrade");
    }
    if (status == "close") {
        $("#channel_" + str + "_" + id).hide();
    } else {
        $("#channel_" + str + "_" + id).show();
    }

}


function addConversion(num) {
    if (typeof num == "undefined")
        num = "";

    var num_id = $("#addconversion" + num).attr("num_id");
    $("#con" + num_id + num).show();
    $("#addconversion" + num).attr("num_id", ++num_id);

}


/**
 * @conversion reset div
 ******/
function closeConversion(num) {
    if (typeof num == "undefined")
        num = "";

    for (i = conversionBegin; i < $(".conver" + num).length; i++)
        $("#con" + i + num).hide();

    $("#addconversion" + num).attr("num_id", conversionBegin);
    $(".conversion_bai" + num).val("");

}



/**
 * copy url
 * ****/
function setCopy(id) {
    var Url2 = document.getElementById(id);
    Url2.select();
    if (document.execCommand('copy', false, null)) {
        document.execCommand("Copy");

    }
}

/**
 * @button click call input File
 ***/
function setFileClick(id) {
    var id = "#" + id;
    $(id + "_file").click();
    $(id + "_file").change(function () {
        console.log($(id + "_file").val());
        $(id + "_span").text($(id + "_file").val());
    });

}


function updateProcess(num,targets) {

    $.ajax({
        type: "get",
        url: targets,
        data: {
            process: $("#process"+num).val(),
            fund_account:$("#fund_account"+num).text(),
            wechat:$("#editWechat"+num).val(),
            desc:$("#editDesc"+num).val(),
        },
        dataTyep: "json",
        success: function (data) {

            if (targets=="getFlowerTrade"){

                $("#flowerwater"+num).empty();
                $("#flowerwater"+num).html(data);
            }else{
                window.location.reload();
            }

        }
    });
}