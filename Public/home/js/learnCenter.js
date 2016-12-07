/**
 * Created by Administrator on 2016/12/1 0001.
 */
$(function(){
    $("div.con_2 li p").hover(function(){
        $(this).css("color","#73B2E7");
    },function(){
        $(this).css("color","#686868")
    });

    //¶¯»­Ð§¹û
    jQuery(".picScroll-left")
        .slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"leftLoop",vis:3});

//    ÏÂÀ­ÇÐ»»
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
//    µ±Ç°¿Î³Ì±íÓëÒÑ½á¿ÎÄ¿Â¼Ö®¼äµÄÇÐ»»
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