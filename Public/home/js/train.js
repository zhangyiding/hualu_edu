/**
 * Created by Administrator on 2016/11/24 0024.
 */
$(function(){



    $("#footer").load("/index/footer");
    $("#header").load("/index/header");



    $("#page ul li a").mouseover(function(){
        $("#page ul li.active").removeClass("active");
    })
    $(".banner ul").on("click","li",function(){
        $(this).addClass("active").siblings(".active").removeClass("active");
    })
    $(".banner li.country").click(function(){
        $("div.tab_1").show();
        $("div.tab_2").hide();
    })
    $(".banner li.busy").click(function(){
        $("div.tab_2").show();
        $("div.tab_1").hide();
    })
//   ��ҳ�л�����
    $(".nav li a").click(function(e){
        //e.preventDefault();
        $(this).parent().addClass("active").siblings(".active").removeClass("active");
    })

})
