<!doctype html>
<head>
	<meta charset="UTF-8">
	<title></title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<script src="//image.buslive.cn/standard/js/chama/jquery-1.10.2.min_chama2.js"></script>
	<script src="//image.buslive.cn/standard/js/fontSize.js"></script>
	<link rel="stylesheet" href="//image.buslive.cn/front/plug-in/css/base.css?v=2">
	<link rel="stylesheet" type="text/css" href="//image.buslive.cn/zm_nmg/css/vote.css?v=2">
	
	<script src="//image.buslive.cn/front/plug-in/js/function.js?v=1"></script>
	<link rel="stylesheet" href="//image.buslive.cn/zm_nmg/css/swiper.css">
	<meta name="WYGather-channel" content="A06163605812"> 
	<meta name="WYGather-actcode" content="2017121500462400"> 
	<meta name="WYGather-pgnm" content="详情页"> 

	
	<style>
		.wrapVide{
			width: 90%;
		    height: 3.47rem;
		    margin: 0 auto;
		   display: none;

		}
		.swiper-container{
			width: 90%;
		    height: 3.47rem;
			  margin: 0 auto;

		}
		.swiper-slide{
			width: 100%;
			height: 100%;
			
		}
		.swiper-slide img{
			width: 100%;
			height: 100%;
		}
		.video{
			display: block;
			width: 100%;
			height: 100%;
			background: red;

		}
	</style>
</head>
<body>
	<div class="banner">
		<div class="bantit">呼和浩特市 —— XXX营业厅 </div>
	</div>
	<div class="color">
		
	</div>
	<div class="main clearfix Nviedo">
			<div class="wrapVide">

				<div class="swiper-container">
			        <div class="swiper-wrapper">
			            
			        </div>
			        <!-- Add Pagination -->
			        <div class="swiper-pagination"></div>
			        <!-- Add Arrows -->
			        <div class="swiper-button-next"></div>
			        <div class="swiper-button-prev"></div>
			    </div>
			</div>

			
		
		<div class="idcode">ID<span class="idCode"></span></div>


		<ul class="listnav">
			<li>
				<span style = "color: #7a1d1d;font-size: 0.33rem;font-weight: bolder;" class="numB"></span>
				票
			</li>
			<li>
			<a href="javascript:;" class="regin WYGather-click" WYGather-page-location="详情页"  WYGather-data-content="给我投票" style = "font-weight: bolder;">给我投票</a>

			<!-- <a href="javascript:;" class="regin" style = "font-weight: bolder;">给我投票</a> -->
			</li>
		</ul>
	</div>

	<div class="mb">
		<div class="wrap1">
			<div class="rulead"></div>
			<div class="titlesa">

			</div>
			<a href="javascript:;" class="off"></a>
		</div>


		<div class="wrap2">
			<a href="javascript:;" class="off1"></a>
			<p class="pink">您今天还剩<span class="titleBS"></span>次投票机会</p>
		</div>
	

		<div class="wrap3 clearfix">
			<img src="//image.buslive.cn/zm_nmg/images/hj.png" alt="">
			<div class="box">
				您的5次投票已完成，点击右上角按钮分享给朋友，让大家评评谁最美！
			</div>
		</div>
	</div>


</body>
<script src = "//image.buslive.cn/zm_nmg/css/swiper.js"></script>

<script type="text/javascript">

// 次数
	var number = "<?php echo \yii\helpers\Url::to(['myvotenumapi'])?>";//次数get
// 點擊消失
$(".wrap3").click(function(){
	$(this).hide();
	$(this).parent().hide();
})
$(".off,.off1").click(function(){
	$(this).parent().hide();
	$(this).parent().parent().hide();
	window.location.reload();
})

$(".wrapVide").show();
// 接收过来的字符串
var sni = '<?= $video ?>';

var obj=JSON.parse(sni);

$(".idCode").html(obj.hall_id);


// 总投票数
$(".numB").html(obj.num);

// 判断有没有视频1是有视频其他为图片
var prcs = obj.is_photo;

// 轮播的图片数组
var paren = obj.video_url;

console.log(obj.video_url);
$(".bantit").html(obj.full_name);
if(prcs.toString() == 1){
	// 视频
	// $(".wrapVide").html("<video src=" + obj.video_url  + " controls='controls' poster = "+  obj.photo_url  +"></video>");
	$(".wrapVide").html('<a href="' + obj.video_url+'" class="video" style  = "background: url('+ obj.video_photo  +');background-size: 100% 100%;"></a>');
	$(".swiper-container").hide();
	$("video").show();
	
}else{
	// 轮播图
	$("video").hide();
	$(".swiper-container").show();
	$(paren).each(function(i){
		var str = "<div class='swiper-slide'><img src='"+ paren[i]   +"'></div>"
		$(".swiper-wrapper").append(str);
	})
	
}

// 投票接口
var voteaApi =  "<?php echo \yii\helpers\Url::to(['voteapi'])?>";
var token = "<?php echo \Yii::$app->request->getCsrfToken()?>";
var idCode = $(".idCode").text()
$.ajax({
	url: number,
	type: 'GET',
	dataType: 'json',
	success:function(data){
		if(data.state == 1){
			
			$(".titleBS").html(data.num-1)
		}
	}
	
})

$(".regin").click(function(){
	$.ajax({
		url: voteaApi,
		type: 'post',
		dataType: 'json',
		data:{
			_csrf:token,
			id:idCode
		},
		success:function(data){
			
			if(data.state == 1){
				$(".mb,.wrap2").show();
			}
			if(data.state == 2){
				
				$.msg({msg:"投票失败"});
			}
			if(data.state == 3){
				$(".wrap3,.mb").show()
			}
			if(data.state == 4){
				$(".mb,.wrap2").show();
				$(".wrap2").css({
					"background":"pink"
				})
				$(".pink").html("您今天已经为TA投过票<br>了,明天再来吧！")
			}
			if(data.state == 5){
				
				$.msg({msg:"活动已经结束"});
			}

		}
		
	})
})




</script>
<script type="text/javascript">
	 var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
    });

</script>
</html>