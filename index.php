<?php
require "./database.php";
require "./useraction.php";
?>
<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<title></title>
		<meta name="description" content="">
		<meta name="author" content="Yuanhao Luo">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link href="./css/bootstrap.min.css" rel="stylesheet">
		<link href="./css/docs.css" rel="stylesheet">
		<style type="text/css">
			body {
				padding-top: 60px;
			}
		</style>
		<link href="./css/bootstrap-responsive.min.css" rel="stylesheet">

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	
	<body>
		<div class="navbar navbar-fixed-top">
			<!--	加入导航栏	-->
			<?php include './navbar.php' ?>
		</div>
		<br>
		
		<div class="row">
			<div class="span6 offset2"><br>
				<h1>  提供出门旅游一键订购服务</h1><br><br>
				<h3><font color="green">全面</font>  : 提供机票，宾馆，出租车等服务订购功能</h2><br>
				<h3><font color="green">方便</font>  : 顾客可快速找到所需的服务，并且一键订购 </h2><br><br>
				<button class="btn btn-info btn-large"><a href="#register" data-toggle="modal"><font color="white">马上注册 &raquo;</font></a></button>
			</div>
			
			<div class="span4">
				<div class="my-logo">
					<h1><font color="blue">启程</font></h1>
					<br>
				</div>
				<form class="well" method="post" action="./book.php">
					<p>轻松订购，轻松旅行<br><br>心动不如行动，出发吧！<br></p>
					<input name="from" style="width: 88%;" class="btn-large" placeholder="出发地">
					<input name="destination" style="width: 88%;" class="btn-large" placeholder="目的地">
					<input name="baction" type="hidden" value="query">
					<button class="btn btn-primary btn-large" type="submit"><i class="icon-plane icon-white"></i> 启程 &raquo;</button>
				</form>
			</div>
				
		</div>
		<br>
		
		<div class="row">
			<div class="span3 offset1"><img src="img/sea1.png" alt=""></div>
			<div class="span3"><img src="img/leisure.png" alt=""></div>
			<div class="span3"><img src="img/kaixuan.jpg" alt=""></div>
			<div class="span3"><img src="img/sea2.jpg" alt=""></div>
		</div>
	
		<div class="span12" style="text-align:center">
			<br><?php echo $warning ?><br>
		</div>
		
		<?php include './footer.php' ?>
		<br>
		
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
			$('.carousel').carousel();
			$('.typeahead').typeahead();
		</script>
		
	</body>
</html>