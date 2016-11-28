/**
 * Created by Administrator on 2016/11/28 0028.
 */
/**
 * Created by Administrator on 2016/11/24 0024.
 */
$(function(){
    $("div.aside ul li a").click(function(){
        console.log("1");
        window.location.reload();
        //window.location.href="//train_list.html";
        window.location("train_list.html");
    })
    $("#page ul li a").mouseover(function(){
        //console.log($("div.aside ul li a"));
        $("#page ul li.active").removeClass("active");
    })
    $(".banner ul").on("click","li",function(){
        $(this).addClass("active").siblings(".active").removeClass("active");
    })
//   Ê×Ò³ÇÐ»»²¿·Ö
    $(".nav li a").click(function(e){
        //e.preventDefault();
        $(this).parent().addClass("active").siblings(".active").removeClass("active");
    })

})
