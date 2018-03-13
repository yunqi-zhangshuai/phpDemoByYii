<?php
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<script src="//image.buslive.cn/standard/js/chama/jquery-1.10.2.min_chama2.js"></script>
	<script src="//image.buslive.cn/standard/js/fontSize.js"></script>
	<link rel="stylesheet" href="//image.buslive.cn/front/plug-in/css/base.css?v=2">
	<link rel="stylesheet" type="text/css" href="//image.buslive.cn/zm_nmg/css/index.css?=12">
	<script src="//image.buslive.cn/front/plug-in/js/function.js?v=1"></script>

</head>
<body>
	<div class="banner">
		
	</div>
	<div class="title">
		您今天有0次投票机会
	</div>

	<div class="nav">


		<div class="list clearfix">
			<a href="javascript:;" class="one WYGather-click" WYGather-page-location="首页"  WYGather-data-content="我要投票"><span class = "black1">我要投票</span></a>
			<a href="javascript:;" class="two WYGather-click" WYGather-page-location="首页"  WYGather-data-content="查找城市"><span class = "black2">查找城市</span></a>


			<!-- <a href="javascript:;" class="one"><span class = "black1">我要投票</span></a>
			<a href="javascript:;" class="two"><span class = "black2">查找城市</span></a> -->
			
		</div>
	</div>

	<div class="main show1">
		<div class="main1">
			<div class="main2 clearfix mainviod">

			</div>
		</div>
	</div>
	


	<div class="main show2">
		<div class="main1" style  = "background:#8fc31f">
			<div class="main2 clearfix main2SA">
			</div>
		</div>
	</div>

	


	

	

</body>
	<script type="text/javascript">
	// 次数
	var number = "<?php echo \yii\helpers\Url::to(['myvotenumapi'])?>";//次数get
	// 获取营业厅详情页
	var content = "<?php echo \yii\helpers\Url::to(['oneinfoapi'])?>";//get
	// 图片列表api
	var proto =  "<?php echo \yii\helpers\Url::to(['photosapi'])?>";//get
	// 投票接口
	var voteaApi =  "<?php echo \yii\helpers\Url::to(['voteapi'])?>"//post





	$.ajax({
		url: number,
		type: 'GET',
		dataType: 'json',
		success:function(data){
			if(data.state == 1){
				
				$(".title").html("您今天有" + data.num +"次投票机会")
			}
		}
		
	});


	

	$.ajax({
		url:  proto,
		type: 'GET',
		dataType: 'json',
		success:function(data){
			if(data.state == 1){
				var html = data.photos;
				
				$(html).each(function(i){
					var str = "";
					str += '<a href="javascript:;" class = "oneClick">'
					str += '<span class = "id">ID<span class = "idNum">' +  html[i].hall_id + '</span></span>'
					str += '<div class="pric">'
					str += '<img src="' + html[i].photo_url  +'" >'
					str += '</div>'
					str += '<span class = "span1" style= "width:95%">' + html[i].num +  '票</span>'
					str += '<span class = "span2">进厅看看》》</span>'
					str += '</a>'
					$(".mainviod").append(str);

				})
				$(".oneClick").each(function(i){
					$(".oneClick").eq(i).click(function(){
						var id = $('.idNum').eq(i).text();
						$.ajax({
							url: content,
							type: 'GET',
							dataType: 'json',
							data:{id:id},
							success:function(data){
								if(data.state == 1){
									window.location.href = "<?php echo \yii\helpers\Url::to(['info'])?>" 
								}
							}
							
						})
					})
				})

			}
		}
	})



	$.ajax({
		url:  proto,
		type: 'GET',
		dataType: 'json',
		data:{sign:1},
		success:function(data){
			if(data.state == 1){
				var html = data.photos;
				$(html).each(function(i){
					
					var str = "";
					str +='<ul class="nblist clearfix">'
					str +=	'<li class="lis1"><div class="dit"></div>'+ html[i].address +'</li>'
					str +=	'<li class="lis2">'
					str +=		'<a href="javascript:;" class = "towClick"><img src="'+ html[i].hall[0].photo_url + '"><b>' +html[i].hall[0].hall_id+ '</b></a>'
					str +=		'<a href="javascript:;" class = "towClick"><img src="'+ html[i].hall[1].photo_url + '"><b>' +html[i].hall[1].hall_id+ '</b></a>'
					str +=		'<a href="javascript:;" class = "towClick"><img src="'+ html[i].hall[2].photo_url + '"><b>' +html[i].hall[2].hall_id+ '</b></a>'
					str +=	'</li>'
					str +='</ul>'
					$(".main2SA").append(str)
					$('b').hide();
				})


				$(".towClick").each(function(i){
					$(".towClick").eq(i).click(function(){
						var id = $('b').eq(i).text();
						
						$.ajax({
							url: content,
							type: 'GET',
							dataType: 'json',
							data:{id:id},
							success:function(data){
								if(data.state == 1){
									window.location.href = "<?php echo \yii\helpers\Url::to(['info'])?>" 

								}
							}
							
						})
					})
				})
				
				
			}
		}
		
	})












    $(".two").css("backgroundImage","none")
    $(".noe").css("backgroundImage","url(//image.buslive.cn/zm_nmg/images/f.png)");
    $(".black2").css("background","none");
    $(".show2").hide();

    $(".one").click(function(){
        $(".one").css("backgroundImage","url(//image.buslive.cn/zm_nmg/images/f.png)");
        $(".two").css("backgroundImage","none");
        $(".list").css("background","#8fc31f")
        $(".black1").css("background","rgba(0,0,0,0.5)");
        $(".black2").css("background","none");
        $(".show2").hide();
        $(".show1").show();
    })
    $(".two").click(function(){
        $(".one").css("backgroundImage","none");
        $(".two").css("backgroundImage","url(//image.buslive.cn/zm_nmg/images/gyu.png)");
        $(".list").css("background","#00a0e9");
        $(".black2").css("background","rgba(0,0,0,0.5)");
        $(".black1").css("background","none");
        $(".show2").show();
        $(".show1").hide();
    })



	</script>


</html>