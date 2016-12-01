/**
 * Created by Administrator on 2016/11/29 0029.
 */
$(function(){
    $("#footer").load("/index/footer");
    $("#header").load("/index/header");
    $("#advice ul li").click(function(e){
        //e.preventDefault();
        $(this).addClass("active").siblings(".active").removeClass("active");
    })
    $("#suggest ul li a").mouseover(function(){
        $(this).parent().css("background","url('/public/home/image/circle.png') no-repeat 5px 3px");
        $(this).parent().siblings().css("background","url('/public/home/image/circle_2_03.png') no-repeat 5px 3px");
    })
//  �γ�Ŀ¼�µ��л�����
    $(".con_2>div").click(function(){
        if($(this).children("span.arrow").hasClass("active")){
                $(this).children("span.arrow").removeClass("active")
                $(this).siblings().children("span.arrow").removeClass("active");
        }else{
                $(this).children("span.arrow").addClass("active");
                $(this).siblings().children("span.arrow").removeClass("active");
        }
        $(this).next().slideToggle(1000).siblings("h5").slideUp(1000);
    })
    $("ul.msg li").click(function(){
        $(this).children().slideToggle();
        $(this).siblings().children().slideUp();
    })


//    ���/Ŀ¼/����֮�������л�
    $(".cen_2").click(function(e){
        e.preventDefault();
        $(".con_2").show();
        $(".con_3").hide();
        $(".con_1").hide();
    })
    $(".cen_1").click(function(e){
        e.preventDefault();
        $(".con_1").show();
        $(".con_2").hide();
        $(".con_3").hide();
    })
    $(".cen_3").click(function(e){
        e.preventDefault();
        $(".con_3").show();
        $(".con_1").hide();
        $(".con_2").hide();
    })
})