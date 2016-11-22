$(function() {
    //banner呼吸轮播效果；
    var num=0;
    var tim;
    var nextFn=function(){
        $('.imgList li').eq(num).fadeOut();
        num++;
        if(num>4){
            num=0;
        }
        $('.imgList li').eq(num).fadeIn();
        $('.btnList li').eq(num).addClass('current').siblings().removeClass('current');
    }
    var prevFn=function(){
        $('.imgList li').eq(num).fadeOut();
        num--;
        if(num<0){
            num=4;
        }
        $('.imgList li').eq(num).fadeIn();
        $('.btnList li').eq(num).addClass('current').siblings().removeClass('current');
    }
    tim=setInterval(nextFn, 3000);
    $('.rightBtn').click(nextFn);
    $('.leftBtn').click(prevFn);

    $('.banner').hover(function() {
        clearInterval(tim);
    }, function() {
        clearInterval(tim);
        tim=setInterval(nextFn, 3000);
    });

    $('.btnList li').click(function(event) {
        var i=$(this).index();
        $(this).addClass('current').siblings().removeClass('current');
        $('.imgList li').eq(i).fadeIn().siblings().fadeOut();
        num=i;
    });

});
/课程的切换效果/
$("#study_2").click(function(e){
    e.preventDefault();
    $(".learn_2").show().siblings().hide();
    $(this).addClass("active").siblings(".active").removeClass("active");
    //$(this).removeClass("net").siblings().addClass("net");
})
$("#study_3").click(function(e){
    e.preventDefault();
    $(".learn_3").show().siblings().hide();
    $(this).addClass("active").siblings(".active").removeClass("active");
    //$(this).removeClass("net").siblings().addClass("net");
})
$("#study_4").click(function(e){
    e.preventDefault();
    $(".learn_4").show().siblings().hide();
    $(this).addClass("active").siblings(".active").removeClass("active");
    //$(this).removeClass("net").siblings().addClass("net");
})
$("#study_5").click(function(e){
    e.preventDefault();
    $(".learn_5").show().siblings().hide();
    $(this).addClass("active").siblings(".active").removeClass("active");
    //$(this).removeClass("net").siblings().addClass("net");
})
$("#study_1").click(function(e){
    e.preventDefault();
    $(".learn_1").show().siblings().hide();
    $(this).addClass("active").siblings(".active").removeClass("active");
    //$(this).removeClass("net").siblings().addClass("net");
})