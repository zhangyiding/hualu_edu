$(function() {
    $("div.section ul.news li p a").mouseover(function(){

        $(this).parent().siblings("i").addClass("current");
        $(this).parents("li").siblings().find("i").removeClass("current");
    });


//����ί��ѵ�л�
    $("span.train_1").click(function(){
        $("ul.new_1").fadeIn();
        $("ul.new_2").fadeOut();
        $(this).addClass("train_1").removeClass("train_2").siblings().removeClass("train_1").addClass("train_2");
    })
    $("span.train_2").click(function(){
        $(this).addClass("train_1").removeClass("train_2").siblings().removeClass("train_1").addClass("train_2");
        $("ul.new_1").fadeOut();
        $("ul.new_2").fadeIn();
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

cseList(2);

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


