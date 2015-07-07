var requestUrl = 'http://mrm.msup.com.cn/admin.php/';
function dataRequest(url, option, callback) {
	$("#loading").show();
	stype = 0;
	$.ajax({
		type:"get",
		url:url,
		data:{data:option},
		dataType:"jsonp",
		success:function (data) {
			if (data.errno ==  0) {
				callback(data.data);
				stype = 1;
			} else if (data.errno == 5){
				$("#loading img").attr("src", "/statics/css/top100/img/end.png").css("height", "70px")
				$("#loading span").text("");
				return false;
			}
			$("#loading").css("position", "static")
			$("#loading").hide();
		}
	})
}