/**
 * jquery.page.js 1.0.0
 * https://github.com/my-milk/jquery.page
 *
 * Released under the MIT license
 * https://github.com/my-milk/jquery.page/blob/master/LICENSE
 */
var loadStyle=function() {
	var code="*{margin:0;padding:0;list-style:none}a{text-decoration:none}a:hover{text-decoration:none}.mpage{padding:15px 20px;text-align:left;color:#ccc;text-align:center}.mpage a{display:inline-block;color:#428bca;display:inline-block;height:25px;line-height:25px;padding:0 10px;border:1px solid #ddd;margin:0 2px;border-radius:4px;vertical-align:middle}.mpage a:hover{text-decoration:none;border:1px solid #428bca}.mpage span.current{display:inline-block;height:25px;line-height:25px;padding:0 10px;margin:0 2px;color:#fff;background-color:#428bca;border:1px solid #428bca;border-radius:4px;vertical-align:middle}.mpage span.disabled{display:inline-block;height:25px;line-height:25px;padding:0 10px;margin:0 2px;color:#bfbfbf;background:#f2f2f2;border:1px solid #bfbfbf;border-radius:4px;vertical-align:middle}";
	var styleLink;
	styleLink = document.createElement("style");
	styleLink.type = "text/css";
	try {
		styleLink.appendChild(document.createTextNode(code));
	} catch (e) {
		styleLink.styleSheet.cssText = code;
	}
	var head = document.getElementsByTagName("head")[0];
	head.appendChild(styleLink);
}
loadStyle();
(function($){
	var ms = {
		init:function(obj,args){
			$(obj).unbind(); //修复BUG
			return (function(){
				ms.fillHtml(obj,args);
				ms.bindEvent(obj,args);
			})();
		},
		//填充html
		fillHtml:function(obj,args){
			return (function(){
				obj.empty();
				//上一页
				if(args.current > 1){
					obj.append('<a href="javascript:;" class="prevPage">上一页</a>');
				}else{
					obj.remove('.prevPage');
					obj.append('<span class="disabled">上一页</span>');
				}
				//中间页码
				if(args.current != 1 && args.current >= 4 && args.pageCount != 4){
					obj.append('<a href="javascript:;" class="pageNumber">'+1+'</a>');
				}
				if(args.current-2 > 2 && args.current <= args.pageCount && args.pageCount > 5){
					obj.append('<span>...</span>');
				}
				var start = args.current -2,end = args.current+2;
				if((start > 1 && args.current < 4)||args.current == 1){
					end++;
				}
				if(args.current > args.pageCount-4 && args.current >= args.pageCount){
					start--;
				}
				for (;start <= end; start++) {
					if(start <= args.pageCount && start >= 1){
						if(start != args.current){
							obj.append('<a href="javascript:;" class="pageNumber">'+ start +'</a>');
						}else{
							obj.append('<span class="current">'+ start +'</span>');
						}
					}
				}
				if(args.current + 2 < args.pageCount - 1 && args.current >= 1 && args.pageCount > 5){
					obj.append('<span>...</span>');
				}
				if(args.current != args.pageCount && args.current < args.pageCount -2  && args.pageCount != 4){
					obj.append('<a href="javascript:;" class="pageNumber">'+args.pageCount+'</a>');
				}
				//下一页
				if(args.current < args.pageCount){
					obj.append('<a href="javascript:;" class="nextPage">下一页</a>');
				}else{
					obj.remove('.nextPage');
					obj.append('<span class="disabled">下一页</span>');
				}
			})();
		},
		//绑定事件
		bindEvent:function(obj,args){
			return (function(){
				obj.on("click","a.pageNumber",function(){
					var current = parseInt($(this).text());
					ms.fillHtml(obj,{"current":current,"pageCount":args.pageCount});
					if(typeof(args.mclick)=="function"){
						args.mclick(current);
					}
				});
				//上一页
				obj.on("click","a.prevPage",function(){
					var current = parseInt(obj.children("span.current").text());
					ms.fillHtml(obj,{"current":current-1,"pageCount":args.pageCount});
					if(typeof(args.mclick)=="function"){
						args.mclick(current-1);
					}
				});
				//下一页
				obj.on("click","a.nextPage",function(){
					var current = parseInt(obj.children("span.current").text());
					ms.fillHtml(obj,{"current":current+1,"pageCount":args.pageCount});
					if(typeof(args.mclick)=="function"){
						args.mclick(current+1);
					}
				});
			})();
		}
	}
	$.fn.Mpage = function(options){
		var args = $.extend({
			pageCount : 10,//总页数
			current : 1,//默认页
			mclick : function(){}
		},options);
		ms.init(this,args);
	}
})(jQuery);