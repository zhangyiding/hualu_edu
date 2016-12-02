/**
 * Created by Administrator on 2016/11/28 0028.
 */
$(function(){

    $("div.page_1 .info_2").click(function(){
        $(".page_1").hide();
        $(".page_2").show();
    })

    $("div.page_2 span.p_2").click(function(){
        $(".page_2").hide();
        $(".page_1 .page_body").hide();
        $(".page_1").show();
        $(".page_1 .pay").show();
        $("#entry img").attr("src","/public/home/image/wait_2.png");
    })
   $("div.pay span.p_1").click(function(){
       $("div.page_1").hide();
       $(".page_2").show();
       $("#entry img").attr("src","/public/home/image/wait_1.png");
   })
    $("div.pay span.p_2").click(function(){
        $("div.pay").hide();
        $("div.check_1").show();
        $("#entry img").attr("src","/public/home/image/wait_3.png");
    })
    $("div.check_1 span").click(function(){
        $("div.check_1").hide();
        $("div.check_2").show();
    })
    $("div.page_2 span.p_1").click(function(){
        $("div.pay").hide();
        $(".page_2").hide();
        $(".page_1").show();
        $("div.page_body").show();

    })
})