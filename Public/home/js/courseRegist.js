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
        $("#step").attr("class","step_2");
    })
   $("div.pay span.p_1").click(function(){
       $("div.page_1").hide();
       $(".page_2").show();
       $("#step").attr("class","step_1");
   })
    $("div.pay span.p_2").click(function(){
        $("div.pay").hide();
        $("div.check_1").show();
        $("#step").attr("class","step_3");
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
    $('div.check_2 span').click(function(){
        location.href = BASE_URL+'/course'
    })
})