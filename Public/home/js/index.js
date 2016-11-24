$(function() {
    //banner�����ֲ�Ч����
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

//����ί��ѵ�л�
    $("span.train_1").click(function(){
        $("ul.new_1").fadeIn();
        $("ul.new_2").fadeOut();
        $(this).css("background-image","url('public/home/image/rightbar1.png')");
        $("span.train_2").css("backgroundImage","url('image/rightbar2.png')");
    })
    $("span.train_2").click(function(){
        $("ul.new_1").fadeOut();
        $("ul.new_2").fadeIn();
        $(this).css("background-image","url('public/home/image/rightbar1.png')");
        $("span.train_1").css("backgroundImage","url('public/home/image/rightbar2.png')");
    })

//��ҳ�����л�
    $(".nav li a").click(function(e){
        e.preventDefault();
        //console.log("1");
        $(this).parent().addClass("active").siblings(".active").removeClass("active");
    })
//    ��¼����
    $(".login_denglu").click(function (e) {
        e.preventDefault();
        $(".modal_1").show();
    })
//  ע�Ჿ�ֵ��л�
    $(".login_zhuce").click(function (e) {
        e.preventDefault();
        $(".modal_2").show();

    })
    $("span.login_2").click(function(){
        $(".modal_2").show();
    })
});


