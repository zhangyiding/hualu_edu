/**
 * Created by Administrator on 2016/12/6 0006.
 */
$(function(){
    //动画效果
    jQuery(".picScroll-left")
        .slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"leftLoop",vis:3});
    //目录结构之间下拉切换
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
    //背景图的实现
    $("div.con_2 ul.msg li p span.rg a").hover(function(){
        $(this).parent().siblings(".lf").show();
    },function(){
        $(this).parent().siblings(".lf").hide();
    })
    //展开与收起之间的切换
    $("span.sa_2").click(function(){
        console.log("1");
        $("div.con_2").hide();
        $("div.shrink").show();
    })
    $("div.shrink span").click(function(){
        $("div.con_2").show();
        $("div.shrink").hide();
    })

    //获得视频播放和暂停事件



    var timer;
    var video=document.getElementById("video");
    var request=function(){
        var  currentTime=(video.currentTime).toFixed(1);
        var current={
            "currentTime" : currentTime
        }
        console.log(currentTime);
            //$.post("",current,function(response){
            //    console.log(response);
            //})
        }
    video.addEventListener("play",function(){
        timer=setInterval(request,100);

    });
    video.addEventListener("pause",function(){
        clearInterval(timer);
        timer=null;
    })
    video.addEventListener("ended",function(){
        console.log("end");
        clearInterval(timer);
        timer=null;

    })







})