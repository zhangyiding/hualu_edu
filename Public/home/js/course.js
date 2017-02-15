/**
 * Created by Administrator on 2016/11/25 0025.
 */
$(function(){

    $(function(){
        getCseType();
        var is_master = '1';
        var cse_dir = '';
        var cse_type = '';
        var is_pub = '';
        $("div.row_right_dir ul li").click(function(e){
            $(this).addClass("active").siblings().removeClass("active");
            cse_dir = $(this).attr('value');
            cse_type = 0;
            is_pub = 0;
            getCseType(cse_dir);
            $("#pageBox").html('<ul id="pagination" class="pagination-sm"></ul>');
            cseList();


        });

        $("div.row_right_status ul li").click(function(e){
            $(this).addClass("active").siblings().removeClass("active");
            is_pub = $(this).attr('value');
            $("#pageBox").html('<ul id="pagination" class="pagination-sm"></ul>');
            cseList();

        });
        $("div.row_right_master ul li").click(function(e){
            $(this).addClass("active").siblings().removeClass("active");
            is_master = $(this).attr('value');
            $("#pageBox").html('<ul id="pagination" class="pagination-sm"></ul>');
            cseList();
        });
        $(document).on('click',".row_right_type ul li",function(){
            $(this).addClass("active").siblings().removeClass("active");
            cse_type = $(this).attr('value');
            $("#pageBox").html('<ul id="pagination" class="pagination-sm"></ul>');
            cseList();
        });

        function getCseType(cse_dir){
            var params = {};
            params.cse_dir = (cse_dir)? cse_dir:'1';
            $.ajax({
                type:'get',
                url:BASE_URL+'/course/getCtList',
                data:params,
                success:function(data){
                    if(data.code == 10000){
                        $('.row_right_type').html('');
                        var temp = '<ul>';
                        $.each(data.result,function(i,n){
                            temp += '<li value="'+n.ct_id+'">'
                                +n.name+'</li>'
                        });
                        temp +='</ul>'
                        $('.row_right_type').append(temp);

                    }else {
                        alert('暂无数据');
                    }

                }
            });

        }




        function cseList(page){

            var param={};
            param.page = page;
            param.limit = 12;
            param.is_master = is_master;
            param.course_dir = cse_dir;
            param.course_type = cse_type;
            param.is_pub = is_pub;
            $.ajax({
                type:"get",
                url:BASE_URL+"/course/getCseList",
                data:param,
                success:function(data){
                    if(data.code =='10000'){

                        $('#allcourse').html('');
                        var temp = '<h1>共有'+data.result.count+'门课程</h1>'
                            +'<ul class="learn learn_1" style="display: block">';

                        $.each(data.result.data_list,function(i,n){
                            var is_pub_src = (n.is_pub == 1)?'Public/home/image/gongkai.png':'Public/home/image/neixun.png';

                            temp+=
                                '<li>'
                                +'<a href="'+'/course/courseInfo?course_id='+n.course_id+'"><img src="'+n.cover+'" height="147" width="224" alt=""/></a>'
                                +'<div class="hour">'+n.os_cn+'</div>'
                                +'<h4>'+n.name+'</h4>'
                                +'<div class="btn">'
                                +'<span class="btn_1">'
                                +'<img src="'+is_pub_src+'" alt=""/></span>'
                                +'<span class="btn_2">'
                                +'<img id="register" value="'+n.course_id+'" src="Public/home/image/baoming.png" alt=""/>'
                                +'</span>'
                                +'</div>'
                                +'</li>'

                        });
                    //<a href="/course/register?course_id='+ n.course_id+'">
                        temp+='</ul>'
                        $('#allcourse').append(temp);
                        $('ul.learn').on("click","span.btn_2 img",function(){
                            var course_id = $(this).attr('value');
                            $.post(BASE_URL+'/user/userIsLogin',function(response){
                                if(response.code == '10000'){
                                    window.location.href = BASE_URL+'/course/register?course_id='+course_id;
                                }else {
                                    $(".modal_1").fadeIn();
                                }
                            })
                        });







                        //分页管理
                        pageStatus = 0;
                        var pageTotal = Math.ceil(parseInt(data.result.count)/(param.limit));

                        $('#pagination').twbsPagination({
                            totalPages: pageTotal,
                            visiblePages: 7,
                            onPageClick: function (event, page) {
                                if(pageStatus>0){
                                    cseList(page);
                                }
                            }
                        });
                        pageStatus++;
                    }else {
                        alert('暂无数据');
                    }
                }
            });
        }
        cseList(1);
    })
});