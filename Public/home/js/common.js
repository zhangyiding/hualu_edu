/**
 * Created by Administrator on 2016/11/25 0025.
 */
$(function(){
    window.BASE_URL = '//'+document.domain;

    $("#footer").load("/index/footer");
    $("#header").load("/index/header");
    $("#nav").load("/index/nav");


    window.onload = function(){
        $('ul.learn').on("click","span.btn_2 img",function(){
            var course_id = $(this).attr('value');
            $.post(BASE_URL+'/user/userIsLogin',function(response){
                if(response.code == '10000'){
                    window.location.href = BASE_URL+'/course/register?course_id='+course_id;
                }else {
                    $(".modal_1").fadeIn();
                }
            })
        });
    }


});