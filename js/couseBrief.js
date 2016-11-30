/**
 * Created by Administrator on 2016/11/29 0029.
 */
$(function(){
    $("#footer").load("footer/footer.html");
    $("#header").load("footer/head.html");
    $("#advice ul li").click(function(e){
        //e.preventDefault();
        $(this).addClass("active").siblings(".active").removeClass("active");
    })
    $("#suggest ul li a").mouseover(function(){
        $(this).parent().css("background","url('image/circle.png') no-repeat 5px 3px");
        $(this).parent().siblings().css("background","url('image/circle_2_03.png') no-repeat 5px 3px");
    })
//  课程目录下的切换功能
    $(".con_2 div").click(function(){
        $(this).next().slideToggle(2000).siblings("p").slideUp(2000);
    })
})