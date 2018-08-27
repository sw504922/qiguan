function signMethod() {
    $.ajax({
        type: 'get',
       url: './sign/check/'+$("#user").val()+'/'+$("#pwd").val(),

        dataType: 'json',
        success: function (data) {
            //验证成功后实现跳转
            alert(JSON.stringify(data));
            window.location.href = "../index/weclome";
        },
        error: function (xhr, status, error) {
            console.log(xhr);
            console.log(status);
            console.log(error);
        }
    });
}