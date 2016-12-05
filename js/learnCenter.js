/**
 * Created by Administrator on 2016/12/1 0001.
 */
$(function(){
    $("#header").load("footer/head_2.html");
    $("#footer").load("footer/footer.html");

    $("div.con_2 li p").hover(function(){
            $(this).css("color","#73B2E7");
        },
        function(){
            $(this).css("color","#686868")
        });

    //¶¯»­Ð§¹û
    jQuery(".picScroll-left")
        .slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"leftLoop",vis:3});




})