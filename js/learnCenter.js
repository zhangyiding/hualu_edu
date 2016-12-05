/**
 * Created by Administrator on 2016/12/1 0001.
 */
$(function(){
    $("#header").load("footer/head_2.html");
    $("#footer").load("footer/footer.html");

// ÂÖ²¥Í¼²¿·ÖÇÐ»»Í¼
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




})