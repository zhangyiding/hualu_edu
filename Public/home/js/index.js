$(function() {
    $("div.section ul.news li p a").mouseover(function(){
        $(this).parent().siblings("i").css("background","url(Public/home/image/circleHover.png) no-repeat");
        $(this).parents("li").siblings().find("i").css("background","url(Public/home/image/circle.png) no-repeat");
    });


    //banner�����ֲ�Ч����
   setTimeout( function(){
        var num=0;
        var tim;
        var size=$(".imgList li").size()-1;
        var nextFn=function(){
            $('.imgList li').eq(num).fadeOut("slow");
            num++;
            if(num>size){
                num=0;
            }
            $('.imgList li').eq(num).fadeIn("slow");
            $('.btnList li').eq(num).addClass('current').siblings().removeClass('current');
        }
        var prevFn=function(){
            $('.imgList li').eq(num).fadeOut("slow");
            num--;
            if(num<0){
                num=size;
            }
            $('.imgList li').eq(num).fadeIn("slow");
            $('.btnList li').eq(num).addClass('current').siblings().removeClass('current');
        }
        tim=setInterval(nextFn, 3000);
        $('.rightBtn').click(nextFn);
        $('.leftBtn').click(prevFn);

        $('.banner').hover(function() {
            clearInterval(tim);
        }, function() {
            clearInterval(tim);
            tim=setInterval(nextFn, 3000);
        });

    $('.btnList li').click(function(event) {
        var i=$(this).index();
        $(this).addClass('current').siblings().removeClass('current');
        $('.imgList li').eq(i).fadeIn().siblings().fadeOut();
        num=i;
    });

},3000);

//����ί��ѵ�л�
    $("span.train_1").click(function(){
        $("span.train_2").css("color","#4F4F4F");
        $("ul.new_1").fadeIn();
        $("ul.new_2").fadeOut();
        $(this).css("background-image","url('Public/home/image/rightbar1.png')");
        $(this).css("color","#FFFFFF");
        $("span.train_2").css("backgroundImage","url('Public/home/image/rightbar2.png')");
    })
    $("span.train_2").click(function(){
        $("span.train_1").css("color","#4F4F4F");
        $(this).css("color","#FFFFFF");
        $("ul.new_1").fadeOut();
        $("ul.new_2").fadeIn();
        $(this).css("background-image","url('Public/home/image/rightbar1.png')");
        $("span.train_1").css("backgroundImage","url('Public/home/image/rightbar2.png')");
    })

//��ҳ�����л�
    $(".nav li a").click(function(){
        $(this).parent().addClass("active").siblings(".active").removeClass("active");

    })
//    ��¼����

    $('#advice ul li:first-child').addClass('active');
    $("#advice ul li").click(function(e){
        $(this).addClass("active").siblings(".active").removeClass("active");
        var cd_id = $(this).find('a').attr('value');
        cseList(cd_id);

    });

cseList(1);

    function cseList(cse_dir) {
        var param = {};
        param.course_dir = cse_dir;

        $.ajax({
            type: "get",
            url: BASE_URL + "/course/courseList",
            data: param,
            success: function (data) {
                if (data.code == '10000') {
                    $('#course').html('');
                    var temp =
                        '<ul class="learn learn_1" style="display: block">';

                    $.each(data.result, function (i, n) {
                        temp +=
                            '<li>'
                            + '<a class="cse_info" href="/course/courseInfo?course_id=' + n.course_id + '"><img src="' + n.cover + '"  height="147" width="224" alt=""/></a>'
                            + '<div class="hour">' + n.os_cn + '</div>'
                            + '<h4>' + n.name + '</h4>'
                            + '<div class="btn">'
                            + '<span class="btn_1">'
                            + n.is_pub
                            + '</span>'
                            + '<span class="btn_2">'
                            + '<img value="' + n.course_id + '" src="../Public/home/image/baoming.png" alt=""/>'
                            + '</span>'
                            + ' </div>'
                            + '</li>'
                    });

                    temp += '</ul>';

                    $('#course').append(temp);

                    $('ul.learn').on("click", "span.btn_2 img", function () {
                        var course_id = $(this).attr('value');
                        $.post(BASE_URL + '/user/userIsLogin', function (response) {
                            if (response.code == '10000') {
                                window.location.href = BASE_URL + '/course/register?course_id=' + course_id;
                            } else {
                                $(".modal_1").fadeIn();
                            }
                        })
                    });
                }else {
                    alert('暂无数据');
                }
            }
        });
    }

});


