/**
 * Created by Administrator on 2016/12/1 0001.
 */
$(function(){
    $("#header").load("footer/head_2.html");
    $("#footer").load("footer/footer.html");

    $("div.con_2 li p").hover(function(){
            $(this).css("color","#73B2E7");
        },function(){
            $(this).css("color","#686868")
        });

    //动画效果
    jQuery(".picScroll-left")
        .slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"leftLoop",vis:3});

//    下拉切换
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
        $(this).children("p").slideToggle();
        $(this).siblings().children("p").slideUp();
    })
//    当前课程表与已结课目录之间的切换
    $("ul.co_left li.lf_1").click(function(){
        $(this).addClass("active").siblings().removeClass("active");
        $("div.current_2").hide();
        $("div.banner").show();
        $("div.current_1").show();
    })
    $("ul.co_left li.lf_2").click(function(){
        $(this).addClass("active").siblings().removeClass("active");
        $("div.banner").hide();
        $("div.current_1").hide();
        $("div.current_2").show();
    })


})