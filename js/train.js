/**
 * Created by Administrator on 2016/11/24 0024.
 */
$(function(){
    $("#page ul li a").mouseover(function(){
        $("#page ul li.active").removeClass("active");
    })
    $(".banner ul").on("click","li",function(){
        $(this).addClass("active").siblings(".active").removeClass("active");
    })
    //$(".banner li.country").click(function(){
    //    $("div.tab_1").show();
    //    $("div.tab_2").hide();
    //})
    //$(".banner li.busy").click(function(){
    //    $("div.tab_2").show();
    //    $("div.tab_1").hide();
    //})
//   首页切换部分
    $(".nav li a").click(function(e){
        //e.preventDefault();
        $(this).parent().addClass("active").siblings(".active").removeClass("active");
    })




//循环后台数据到指定位置
    $("#dataPage").pagination({
        dataSource:function (done) {
            $.ajax({
                type: "GET",
                url: "//edu.hl.com/news/getNewsList",
                success: function(response){
//                        console.log(response);
                    done(response.result.data);
                }
            })

        },
        pageSize:5,
        showPrevious:true,
        showNext:true,
        callback:function(data,pagination){
            console.log(data);
//                console.log(pagination);
            var html="<ul>";
            for(var i=0;i<data.length;i++){
                html+="<li><a href='train_list.html'><span class='term'>"
                +data[i].title+"</span><span class='date'>"
                + data[i].ctime+"</span></a></li>";
            }
            html+="</ul>";
            $("#dataContainer").html(html);
        }
    })








})
