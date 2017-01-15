/**
 * Created by Administrator on 2016/12/6 0006.
 */
$(function() {


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

        $.get(BASE_URL + '/user/changePwd', param, function (repeson) {
            if (repeson.code == 10000) {
                alert('修改成功');
            } else {
                alert(repeson.msg);
            }


        })
    });

    function getUserInfo(){

        $.ajax({
            type:"get",
            url:BASE_URL+"/user/userInfo",
            success:function(data){
                if(data.code =='10000'){
                    var result = data.result;
                    if(result.avatar){
                        $('div.pic').html('');
                        var temp = '<img src="'+ result.avatar +'">';
                        $('div.pic').append(temp);
                    }
                  if(result.ethnic){
                      $('#ethnic option[value = '+ result.ethnic+']').attr('selected','true');
                  }

                    if(result.politics_status){
                        $('#politics_status option[value = '+ result.politics_status+']').attr('selected','true');
                    }
                    if(result.culture_degree){
                        $('#culture_degree option[value = '+ result.culture_degree+']').attr('selected','true');
                    }

                }else {
                    alert('暂无数据');
                }
            }
        });
    }
    getUserInfo();

    $('#uploadify').uploadify({
        'formData': {
            'timestamp': new Date().getTime(),
            'type': '3'
        },
        'swf': '/Public/admin/js/uploadify.swf',//swf路径
        'uploader': '/admin/course/upload',//处理文件的服务器地址
        'filesSelected': '1',//同时选择文件的个数
        'uploadLimit': '30',//选择文件的总数
        'method': 'post',
        'auto': false,
        'multi': true,
        'width': '120',
        'height': '40',
        'cancelImg': '/Public/admin/image/uploadify-cancel.png',
        'buttonText': '请选择图片',
        'fileObjName': 'uploadFile',
        'fileSizeLimit': '0',
        'onUploadSuccess': function (file, data, response) {
            var obj = eval('(' + data + ')');

            if (obj.code !== 10000)
                alert(obj.msg);
            else if (img_url = obj.result.path) {
                $('div.pic img').attr({src:BASE_URL+'/'+img_url,value:img_url});
            }
        }
    })


    $('div.btn').click(function(){
        var params = $('div.co_right_1 form').serialize();


        if(avatar =  $('div.pic img').attr('value')){
            params += '&avatar='+avatar;
        }


        $.post(BASE_URL+'/user/updateInfo',params,function(response){
            if(response.code == 10000){
                alert('操作成功');
                location.href = BASE_URL+'/user/info';
            }else {
                alert(response.msg);
            }
        });
    })

})