/**
 * Created by Administrator on 2016/12/6 0006.
 */
$(function(){
    //����Ч��
    jQuery(".picScroll-left")
        .slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"leftLoop",vis:3});
    //Ŀ¼�ṹ֮�������л�
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
    //$("ul.msg li").click(function(){
    //    $(this).children().slideToggle();
    //    $(this).siblings().children().slideUp();
    //})
    //����ͼ��ʵ��
    $("div.con_2 ul.msg li p ").hover(function(){
        $(this).parent().siblings(".lf").show();
    },function(){
        $(this).parent().siblings(".lf").hide();
    });
    //չ��������֮����л�
    $("span.sa_2").click(function(){
        console.log("1");
        $("div.con_2").hide();
        $("div.shrink").show();
    });
    $("div.shrink span").click(function(){
        $("div.con_2").show();
        $("div.shrink").hide();
    });
    //ʵ�ֽ��ù��ܵĲ���������
    var path = $("div.mod-video-warp").attr('value');
    var res_id = $("div.mod-video-warp").attr('res_id');
    var time = $("div.mod-video-warp").attr('time');




})