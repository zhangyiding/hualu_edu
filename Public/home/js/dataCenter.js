/**
 * Created by Administrator on 2016/12/6 0006.
 */
$(function() {

    var url ='//edu.hl.com';
    $("p.data_2").click(function () {
        $(this).addClass("current").siblings().removeClass("current");
        $("div.co_right_1").hide();
        $("div.co_right_2").show();
    });
    $("p.data_1").click(function () {
        $(this).addClass("current").siblings().removeClass("current");
        $("div.co_right_2").hide();
        $("div.co_right_1").show();
    });


    $("div.firm").click(function (e) {
        var old_pwd = $("input[name=old_pwd]").val();
        var new_pwd = $("input[name=new_pwd]").val();
        var cfm_pwd = $("input[name=cfm_pwd]").val();

        if (new_pwd !== cfm_pwd) {
            alert('新密码和确认密码不一致');
            return;
        }

        var param = {
            'old_pwd': old_pwd,
            'new_pwd': new_pwd,
            'cfm_pwd': cfm_pwd
        };

        $.get(url + '/user/changePwd', param, function (repeson) {
            if (repeson.code == 10000) {
                alert('修改成功');
            } else {
                alert(repeson.msg);
            }


        })
    })
})