$(function() {
    //$.get("//hualu.etago.cn",function(data){
    //    console.log(data);
    //    if(data.msg="�ɹ�"){
    //        console.log("1");
    //        $(".modal_1").fadeIn();
    //    }
    //
    //})



    //banner�����ֲ�Ч����
    window.onload = function(){
        var num=0;
        var tim;
        var size=$(".imgList li").size()-1;
        var nextFn=function(){
            $('.imgList li').eq(num).fadeOut("slow");
            num++;
            if(num>size){
                num=0;
            }
            $('.imgList li').eq(num).fadeIn("slow");
            $('.btnList li').eq(num).addClass('current').siblings().removeClass('current');
        }
        var prevFn=function(){
            $('.imgList li').eq(num).fadeOut("slow");
            num--;
            if(num<0){
                num=size;
            }
            $('.imgList li').eq(num).fadeIn("slow");
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
        });    }

    $('.btnList li').click(function(event) {
        var i=$(this).index();
        $(this).addClass('current').siblings().removeClass('current');
        $('.imgList li').eq(i).fadeIn().siblings().fadeOut();
        num=i;
    });
    $("#advice ul li").click(function(e){
        //e.preventDefault();
        $(this).addClass("active").siblings(".active").removeClass("active");
    })

//����ί��ѵ�л�
    $("span.train_1").click(function(){
        $("span.train_2").css("color","#4F4F4F");
        $("ul.new_1").fadeIn();
        $("ul.new_2").fadeOut();
        $(this).css("background-image","url('Public/home/image/rightbar1.png')");
        $(this).css("color","#FFFFFF");
        $("span.train_2").css("backgroundImage","url('Public/home/image/rightbar2.png')");
    })
    $("span.train_2").click(function(){
        $("span.train_1").css("color","#4F4F4F");
        $(this).css("color","#FFFFFF");
        $("ul.new_1").fadeOut();
        $("ul.new_2").fadeIn();
        $(this).css("background-image","url('Public/home/image/rightbar1.png')");
        $("span.train_1").css("backgroundImage","url('Public/home/image/rightbar2.png')");
    })

//��ҳ�����л�
    $(".nav li a").click(function(){
        $(this).parent().addClass("active").siblings(".active").removeClass("active");
    })
//    ��¼����


});


