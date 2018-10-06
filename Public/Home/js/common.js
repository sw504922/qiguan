
$(document).ready(function () {
    $("#search").click();
    var url = location.href;
    $(".nav li").removeClass("active");
    var lastID = url.substring(url.lastIndexOf("/") + 1, url.lastIndexOf("."));
    $("#" + lastID).addClass("active");
    $("#anchorName").text( $("#" + lastID).text());
    //chiledren active
    var chiledren = $("#chiledren").val();

    if (url.indexOf(chiledren) > 0) {
        $("#" + chiledren + ">ul.children").css("display", "block");
        $("#" + chiledren).addClass("nav-active active");
    }

    $(".leftpanel").height($(".mainpanel").height());
    thumbnailStatus();
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

function updateData(targets,id,status) {

    $.ajax({
        type: "get",
        url: targets,
        data: {
            id: id,
            status: status,
        },
        dataTyep: "json",
        success: function (data) {
          window.location.reload();
        }
    });
}

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

function submitNewChanne(id,target) {


    var form = new FormData(document.getElementById(id));
    $.ajax({
        type: "post",
        url: "../News/" + target,
        processData: false,
        contentType: false,
        data: form,
        success: function (data) {
            window.location.reload();
        },
        error: function (data) {
           // console.log("this is error");
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
 * @button click call input File
 ***/
function uploadIMG(id,num) {

    var fileId="#" + id + "_file_"+num;
    var spanId="#" + id + "_span_"+num;
    $(fileId).click();
    $(fileId).change(function () {

        $(spanId).text($(fileId).val());
    });

}


function addConversion() {
    var num = $("#addconversion").attr("num_id");
    var maxNum = $("#addconversion").attr("max_num");
    $("#thumbnail_span_" + num).removeClass("hide");
    if (num<maxNum){
        $("#addconversion").attr("num_id", ++num);
    }


}
