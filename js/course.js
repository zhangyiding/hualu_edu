/**
 * Created by Administrator on 2016/11/25 0025.
 */
$(function(){
//    liÌø×ªÒ³Ãæ
    $("ul.learn li").click(function(){
        location.href="courseBrief.html";
    })





    function parseQueryString(url){
        var json = {};
        var arr = url.substr(url.indexOf('?') + 1).split('&');
        arr.forEach(function(item) {
            var tmp = item.split('=');
            json[tmp[0]] = tmp[1];
        })
        return json;
    }
    var url =window.location.href;
    var json = parseQueryString(url);
    console.log(json);
    if(json.is_master=="1"){
        $("div.row_right ul li.master_1").addClass("active")
            .siblings().removeClass("active");
    }else if(json.is_master=="2"){
        $("div.row_right ul li.master_2").addClass("active")
            .siblings().removeClass("active");
    }

    switch (json.pub){
        case 1:
            $("div.row_right ul li.pub_1").addClass("active")
                .siblings().removeClass("active");
            break;
        case 2:
            $("div.row_right ul li.pub_2").addClass("active")
                .siblings().removeClass("active");
            break;
        case 3:
            $("div.row_right ul li.pub_3").addClass("active")
                .siblings().removeClass("active");
            break;
    }
})