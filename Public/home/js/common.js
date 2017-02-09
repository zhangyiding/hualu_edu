/**
 * Created by Administrator on 2016/11/25 0025.
 */
$(function(){
    window.BASE_URL = '//'+document.domain;

    $("#footer").load("/index/footer");
    $("#header").load("/index/header");
    $("#nav").load("/index/nav");

});