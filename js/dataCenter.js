/**
 * Created by Administrator on 2016/12/6 0006.
 */
$(function(){
    $("p.data_2").click(function(){
        $(this).addClass("current").siblings().removeClass("current");
        $("div.co_right_1").hide();
        $("div.co_right_2").show();
    })
    $("p.data_1").click(function(){
        $(this).addClass("current").siblings().removeClass("current");
        $("div.co_right_2").hide();
        $("div.co_right_1").show();
    })
})