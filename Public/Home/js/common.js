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

$(".tradeNav li").click(function () {
    var val = $(this).text();
    if (val == "已发布") {
        $("#status").val(0);
        getData("getContent");
    } else if (val == "审核未通过") {
        $("#status").val(2)
        getData("getContent");
    } else if (val == "已下架") {
        $("#status").val(1)
        getData("getContent");
    }else if(val == "草稿"){
        $("#status").val(3)
        getData("getContent");
    }else if(val.indexOf("添加")==0){
        getData("getAddMethod");
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
            //window.location.reload();
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


function getSelectData(targets) {
    $.ajax({
        type: "get",
        url: targets,
        data: {
            status: $("#status").val(),
            new_page: $("#page").val(),
            channel: $("#channel").val(),
        },
        dataTyep: "json",
        success: function (data) {
            $("#select_data_area").html(data);
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

function submitNewChanne(id, target, contro,type) {
    if (typeof contro == "undefined") {
        contro = "News";
    }

    if (type=="save"){
        $("#send_status").val(3);
    }



    if (target == "addVideo") {
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
             //   $(".btn-primary").removeAttr("onclick");
                $(".btn-primary").css("background" ,"#efefef");
                $(".btn-primary").css("border" ,"1px solid #efefef");

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
        url: "../News/uploadFile",
        processData: false,
        contentType: false,
        data: form,
        success: function (data) {
            var num = $("#thumbnailNum").val();
            var subname = $("#subname").val();
            if (subname == "video_images" && num == 1) {
                $("#thumbnail_span_" + num).html("<video style='width: 300px' id='videodd' controls='controls' ><source src='" + data + "' type='video/mp4'></video>");

            } else if (subname == "music_images" && num == 1) {
                $("#thumbnail_span_" + num).html("<audio id='videodd' controls='controls'><source src='" + data + "' type='audio/map3'></audio>");
            } else {
                $("#thumbnail_span_" + num).html("<img src='" + data + "' class='eidtImg'>");
            }


            $("#thumbnail_file_" + num).val(data.replace("..", ""));
            $("#thumbnail_file").val("");

        },
    })
});

function getVidDur() {
    var videoAudio = document.getElementById("videodd").duration;
    $("#videoLength").val(videoAudio);
}

function newsoOnload() {
    $("#addconversion").click();
    var channel = $("#hideChannel").val();
    if (channel != "") {
        $("#channel").val(channel);
    }
    getSelectData('../News/getGuzhiChannel');
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
        $("#thumbnail_checkbox_" + i).removeClass("hide");
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
    var num = $("#specialImg").val();
    var id = "#thumbnail_span_" + num;
    addClass(".that");
    removeClass(id);
    $(id).attr("onclick", "uploadIMG('thumbnail','" + num + "')");
}


function getChannelInfor(str, id, status) {
    if (status == "close") {
        $("#channel_" + str + "_" + id).hide();
    } else {
        $("#channel_" + str + "_" + id).show();
        if (status == "group") {
            checkBoxCheced(id);
        }
    }

}

function userMethod(target, num) {
    var form = new FormData(document.getElementById("formid" + num));
    $.ajax({
        type: "post",
        url: target,
        processData: false,
        contentType: false,
        data: form,
        beforeSend: function () {
            //$("#loading_" + num).show();
        },

        success: function (data) {
            getChannelInfor('infor', num, 'close');
           // self.location = document.referrer;
        }
    });

}

/**
 *@Auth group checkbox start status onload  Method
 ****/
function checkBoxCheced(id) {
    var rules = $("#rules" + id).val();

    var arr = rules.split(",");

    for (var i = 0; i < arr.length; i++) {


        $("#check" + id + "two" + arr[i] + "s").attr("checked", "checked");
    }
    $("#check" + id + "two" + "1s").attr("checked", "checked");
    $("#check" + id + "two" + "1s").attr("disabled", "false");
}
/**
 *@Auth group checkbox status Method
 ****/
function checkALL(id, sid) {
    var first = $("#check" + id + "two" + sid + "s").is(":checked");
    if (first == true) {
        $("." + sid).prop("checked", true);
    } else {
        $("." + sid).prop("checked", false);
    }
}

function checkAddALL(id, sid) {
    var first = $("#addcheck" + id + sid).is(":checked");
    if (first == true) {
        $("." + sid).prop("checked", true);
    } else {
        $("." + sid).prop("checked", false);
    }
}
/**
 *@Auth group update Method
 ****/
function saveNews(num) {
    var id_array = new Array();
    $("input[name='checkbox" + num + "']:checked").each(function () {
        id_array.push($(this).val());//向数组中添加元素
    });
    var rules = id_array.join(',');//将数组元素连接起来以构建一个字符串
    if (rules != "") {
        $.ajax({
            type: "POST",
            url: "updateGroup",
            data: {
                rowkey: num,
                rules: rules,
            },
            dataType: "json",
            success: function (data) {
                getChannelInfor('infor', num, 'close')
            }
        });

    }
}

/**
 *@Auth group del Method
 ****/
function deltedGroup(id) {
    $.ajax({
        type: "GET",
        url: "deltedGroup",
        data: {
            rowkey: id,
        },
        dataType: "json",
        success: function (data) {
            location.reload();
        }
    });
}



function setBanner(str){
    var id_array = new Array();
    $("input[name='checkbox']:checked").each(function () {
        id_array.push($(this).val());//向数组中添加元素
    });
    var rules = id_array.join(',');//将数组元素连接起来以构建一个字符串
    updateData('../Guzhi/setStatusBanner',rules,str);
}
