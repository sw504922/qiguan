$(document).ready(function () {
    $("#search").click();
    var url = location.href;
    var lastID = url.substring(url.lastIndexOf("/") + 1, url.lastIndexOf("."));

    $("#" + lastID).addClass("active");
    $("#anchorName").text($("#" + lastID).text());
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

function updateData(targets, id, status) {

    $.ajax({
        type: "get",
        url: targets,
        data: {
            id: id,
            status: status,
        },
        dataTyep: "json",
        success: function (data) {
            // window.location.reload();
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
            status: $("#status").val(),
            new_page: $("#page").val(),
            channel: $("#channel").val(),
        },
        dataTyep: "json",
        beforeSend: function () {
            $('#get_data_area').empty();
            $(".loading").show();
        },
        success: function (data) {
            $(".loading").hide();
            $("#get_data_area").html(data);
            setBackHeight();
        }
    });
}

/**
 * @set height bak
 *
 ********/
function setBackHeight() {
    setTimeout(function () {
        $(".leftpanel").css("height", $(document).height())
    }, 1000);
}

function submitNewChanne(id, target, contro) {
    if (typeof contro == "undefined") {
        contro = "News";
    }
    if (target=="addVideo"){
        getVidDur();
    }
    var title = $("input[name='title']").val();
    if (title == "") {
        alert("标题不能为空")
        return false;
    } else {
        var form = new FormData(document.getElementById(id));
        $.ajax({
            type: "post",
            url: "../" + contro + "/" + target,
            processData: false,
            contentType: false,
            data: form,
            success: function (data) {
                //window.location.reload();
            },
            error: function (data) {
                // console.log("this is error");
            }
        })
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
            getData(targets);
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
function uploadIMG(id, num) {
    console.log(num);
    $("#thumbnailNum").val(num);
    $("#thumbnail_file").click();

}
$("#thumbnail_file").bind("change", function (event) {
    var form = new FormData(document.getElementById("uploadForm"));
    $.ajax({
        type: "post",
        url: "../News/uploadFile" ,
        processData: false,
        contentType: false,
        data: form,
        success: function (data) {
            var num= $("#thumbnailNum").val();
            var subname=$("#subname").val();
            if (subname=="video_images"){
                $("#thumbnail_span_"+num).html("<video style='width: 300px' id='videodd' controls='controls' ><source src='"+data+"' type='video/mp4'></video>");

            }else if(subname=="music_images"){
                $("#thumbnail_span_"+num).html("<audio src='"+data+"' class='eidtImg' controls='controls'></audio>");
            }else{
                $("#thumbnail_span_"+num).html("<img src='"+data+"' class='eidtImg'>");
            }


            $("#thumbnail_file_"+num).val(data.replace("..",""));
            $("#thumbnail_file").val("");

        },
    })
});

function getVidDur()
{
    var videoAudio=document.getElementById("videodd").duration;
    $("#videoLength").val(videoAudio);
    console.log(videoAudio);
}
function newsoOnload(){
    $("#addconversion").click();
    var channel=$("#hideChannel").val();
    if (channel!=""){
        $("#channel").val(channel);
    }
    getData('../News/getGuzhiChannel');
}
/**
 * @button click add Thumbnail text
 ***/
function addConversion() {
    var num = $("#addconversion").attr("num_id");
    var maxNum = $("#addconversion").attr("max_num");

    for (var i = 1; i <= num; i++) {
        $("#thumbnail_span_" + i).removeClass("hide");
        $("#thumbnail_text_" + i).removeClass("hide");
    }
    if (num < parseInt(maxNum)) {
        $("#addconversion").attr("num_id", ++num);
    }
}

$(".setPublish").click(function () {
    var value = $(this).val();
    console.log(value);
    addClass("#publishtime");
    if (value == "1") {
        removeClass("#publishtime");
    }
})

function removeClass(str) {
    $(str).removeClass("hide");
}

function addClass(str) {
    $(str).addClass("hide");
}

/**
 * @日期
 * format: 'yyyy-mm-dd',天
 * format: 'yyyy-mm-dd hh:ii:ss',小时
 * minView:显示最小视图
 *
 * ******日期*******/
$("#publishtime").datetimepicker({
    format: 'yyyy-mm-dd hh:ii:ss',
    minView: 'hour',
    language: 'zh-CN',
    autoclose: true,
})


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


function thumbnailStatus() {
    $("#subname").val("guanzhi");
    var num = $("#specialImg").val();
    var id = "#thumbnail_span_" + num;
    addClass(".that");
    removeClass(id);
    $(id).attr("onclick", "uploadIMG('thumbnail','" + num + "')");
}