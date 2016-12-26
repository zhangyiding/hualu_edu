/**
 * Created by Administrator on 2016/11/25 0025.
 */
$(function(){
    window.BASE_URL = '//edu.hl.com';

    $('#content').css('margin-left', '0px');

    $("ul.kind").on("click","li",function(){
        $(this).addClass("active").siblings().removeClass("active");
        $(this).parent().siblings("span.lf").html($(this).html());
    });





});