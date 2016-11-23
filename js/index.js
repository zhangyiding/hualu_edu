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
    $("#advice ul li").click(function(e){
        $(this).addClass("active").siblings(".active").removeClass("active");
    })

//国资委培训切换
    $("span.train_1").click(function(){
        $("ul.new_1").fadeIn();
        $("ul.new_2").fadeOut();
        $(this).css("background-image","url('img/rightbar2.png')");
        $("span.train_1").css("backgroundImage","url('img/rightbar1.png')");
    })
    $("span.train_2").click(function(){
        $("ul.new_1").fadeOut();
        $("ul.new_2").fadeIn();
        $(this).css("background-image","url('img/rightbar1.png')");
        $("span.train_1").css("backgroundImage","url('img/rightbar2.png')");
    })

//首页部分切换
    $(".nav li a").click(function(e){
        e.preventDefault();
        //console.log("1");
        $(this).parent().addClass("active").siblings(".active").removeClass("active");
    })
});


