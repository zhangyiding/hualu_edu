/**
 * Created by Administrator on 2016/11/25 0025.
 */
$(function(){
    window.BASE_URL = '//edu.hl.com';


    function uploadimg() {
        $.ajaxFileUpload({
            url: "/admin/news/uploadImg",
            secureuri:false,
            fileElementId:'uploadFile',
            dataType: "text",
            success : function(msg) {
                var src = 0;
                var imgUrl = "/";
                arr = msg.split("|");
                if (arr[0] == "error") {
                    alert(arr[1]);
                    return;
                }
                $("#news_img").attr("src", imgUrl + arr[1]);
                $("#profile").val(arr[1]);
            },
            error : function(msg) {
                alert("头像上传失败");
            }
        })
    }
    $('#content').css('margin-left', '0px')



});