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
    var request=function(p){
        $.post("//test.tcsasac.com/news/getNewsList?page="+p,function(data){
            $("#dataContainer").html("");
            var ul=$("<ul></ul>");
            $.each(data.result.data_list,function(i,n){
                li=$("<li><a href='train_list.html'><span class='term'>"+ n.title
                    +"</span><span class='date'>"+ n.ctime+"</span></a></li>"
                );
                ul.append(li);
            })
            $("#dataContainer").append(ul);
        })
    }
    request(1);
    //分页部分的切换
    $(".mpage").Mpage({
        pageCount : 50,
        current :1,
        mclick:function(p){
            $.post("//test.tcsasac.com/news/getNewsList?page="+p,function(data){
                $("#dataContainer").html("");
                var ul=$("<ul></ul>");
                $.each(data.result.data_list,function(i,n){
                    li=$("<li><a href='train_list.html'><span class='term'>"+ n.title
                        +"</span><span class='date'>"+ n.ctime+"</span></a></li>"
                    );
                    ul.append(li);
                })
                $("#dataContainer").append(ul);
            })
        }
    })




})
