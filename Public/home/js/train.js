/**
 * Created by Administrator on 2016/11/24 0024.
 */
$(function(){

    var news_type = '';
    var title = '';

    $(".banner ul li").click(function(){
        $(this).addClass("active").siblings().removeClass("active");
        title = '';
        news_type = $(this).attr('value');
        $("#pageBox").html('<ul id="pagination" class="pagination-sm"></ul>');
        newsList()
    });


    $('.search i').click(function(){

        if(title = $('.search input').val()){
            news_type = '';
            newsList();
        }
    });



    function newsList(page){
        var param={};
        param.limit = 8;
        param.page = page;
        param.title = title;
        param.news_type = news_type;

        $.ajax({
            type:"get",
            url:BASE_URL+"/news/getNewsList",
            data:param,
            success:function(data){
                if(data.code =='10000'){
                    $('.aside').html('');

                    var temp = '<ul>';
                    $.each(data.result.data_list,function(i,n){
                        temp+= '<li>'
                                +'<a href="'+'/news/newsInfo?news_id='+ n.news_id + '">'
                                + '<span class="term">'
                                + n.title
                                +'</span>'
                                +'<span class="date"> '
                                + n.ctime
                                +'</span>'
                                +'</a>'
                                +'</li>'
                    });

                    temp+='</ul>'
                    $('.aside').append(temp);

                    //分页管理
                    pageStatus = 0;
                    var pageTotal = Math.ceil(parseInt(data.result.count)/(param.limit));

                    $('#pagination').twbsPagination({
                        totalPages: pageTotal,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            if(pageStatus>0){
                                newsList(page);
                            }
                        }
                    });
                    pageStatus++;
                }else {
                    alert(data.msg);
                }
            }
        });
    }

    newsList(1);

    //
    //$('.aside ul li a').hover(function(){
    //    console.log('a');
    //    $(this).parent().css("text-decoration","underline");
    //
    //})
});
