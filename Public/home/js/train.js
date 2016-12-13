/**
 * Created by Administrator on 2016/11/24 0024.
 */
$(function(){


    $("#page ul li a").mouseover(function(){
        $("#page ul li.active").removeClass("active");
    })


//   ��ҳ�л�����
    $(".nav li a").click(function(e){
        //e.preventDefault();
        $(this).parent().addClass("active").siblings(".active").removeClass("active");
    });


    //将当前url中参数转换为数组
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

    if(json.news_type == 2){
        $('div.banner ul li.busy').addClass('active');
    }else {
        $('div.banner ul li.country').addClass('active');
    }








});
