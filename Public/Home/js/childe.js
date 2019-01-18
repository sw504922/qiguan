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