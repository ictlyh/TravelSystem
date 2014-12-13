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
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	</head>

	<body>
		<div class="navbar navbar-fixed-top">
			<!--	添加导航栏	-->
			<?php include './navbar.php' ?>
		</div>
		
		<div class="container">
			<?php echo $warning ?>
			<?php
				if (isset($_POST['baction'])&&($_POST['destination']==''||$_POST['from']=='')){
			?>

					<div class="alert alert-error fade in" style="text-align: center;">
						<a class="close" data-dismiss="alert" href="#">x</a>
						<strong>错误：</strong>请输入正确的城市名称。
					</div>
			<?php
				}
			?>
				<div class="hero-unit">
					<h1>新的旅程&nbsp;&nbsp;<small><i>轻松订购，轻松旅程</i></small></h1><br><br>
					<form class="form-horizontal" method="post" action="./book.php">
						<div class="control-group">
							<label class="control-label"><h3>FROM</h3></label>
							<div class="controls">
								<input type="text" class="input-large btn-large" name="from" placeholder="出发地">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><h3>TO</h3></label>
							<div class="controls">
								<input type="text" class="input-large btn-large" name="destination" placeholder="目的地">
							</div>
						</div>
						<input type="hidden" name="baction" value="query">
						<button type="submit" class="btn btn-large btn-primary"><i class="icon-plane icon-white"></i> 启程 &raquo;</button>
					</form>
				</div>
			<br>
			<?php
				if(isset($_POST['baction'])){
					if ($_POST['baction']=='query'){//显示查询结果信息
			?>
						<h1>行程查询&nbsp;&nbsp;&nbsp;<small>出发地：<?php echo $_POST['from'] ?>，目的地：<?php echo $_POST['destination'] ?></small></h1><br>
						
						<form method="post" action="./account.php">
							<p><h3>从<?php echo $_POST['from'] ?>到<?php echo $_POST['destination'] ?>的航班情况：</h3></p>
							<?php
								$record=$db->query("SELECT * FROM FLIGHTS WHERE FromCity='".addslashes($_POST['from'])."' AND ArivCity='".addslashes($_POST['destination'])."'");
								if ($record->num_rows==0){//无直达航班
							?>
							<div class="row">
								<div class="alert alert-error fade in" style="text-align: center;"><a class="close" data-dismiss="alert" href="#">x</a>
									<h4>没有找到符合条件的航班</h4>
								</div>
							</div>
							<?php
								}else{//显示航班信息
							?>
								<table class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>航班号</th>
											<th>价格</th>
											<th>座位总数</th>
											<th>剩余座位</th>
											<th>订购</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$chk=0;
											while ($row=$record->fetch_row()) 
											{//显示航班号，价格，总座位，剩余座位，订购单选框
												echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td><input type=\"radio\" name=\"flight\" value=\"$row[0]\"".($chk==0?" checked":"")."></td></tr>";
												$chk=1;
											}
										?>
									</tbody>
								</table>
							<?php
								}
							?>
							<p><h3><?php echo $_POST['destination'] ?>的酒店信息：</h3></p>
							<?php
							//根据目的地查询酒店信息
								$record=$db->query("SELECT * FROM HOTELS WHERE location='".addslashes($_POST['destination'])."'");
								if ($record->num_rows==0){//无符号条件的酒店
							?>
									<div class="row">
										<div class="alert alert-error fade in" style="text-align: center;"><a class="close" data-dismiss="alert" href="#">x</a>
											<h4>没有找到符合条件的酒店</h4>
										</div>
									</div>
							<?php
								}else{//显示酒店信息
							?>
									<table class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>价格</th>
												<th>房间总数</th>
												<th>剩余房间</th>
												<th>预定酒店</th>
											</tr>
										</thead>
										<tbody>
										<?php
													$row=$record->fetch_row();
										
											//显示价格，房间总数，剩余房间，是否预订复选框
											echo "<tr><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td><input type=\"checkbox\" name=\"bhotel\" value=\"yes\" checked></td></tr>";
										?>
										</tbody>
									</table>
							<?php
								}
							?>
							<p><h3><?php echo $_POST['destination'] ?>的租车信息：</h3></p>
							<?php
							//根据目的地查询是否有出租车
								$record=$db->query("SELECT * FROM CARS WHERE location='".addslashes($_POST['destination'])."'");
								if ($record->num_rows==0){//无符号添加的出租车
							?>
									<div class="row">
										<div class="alert alert-error fade in" style="text-align: center;"><a class="close" data-dismiss="alert" href="#">x</a>
											<h4>没有找到符合条件的租车公司</h4>
										</div>
									</div>
							<?php
								}else{//显示租车信息
							?>
								<table class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>价格</th>
											<th>车辆总数</th>
											<th>剩余车辆</th>
											<th>租车</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$row=$record->fetch_row();
										//显示租车的价格，车辆总数，剩余车辆，是否租车复选框
										echo "<tr><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td><input type=\"checkbox\" name=\"bcar\" value=\"yes\" checked></td></tr>";
									?>
									</tbody>
								</table>
							<?php
								}
							?>
						
					<input type="hidden" name="aaction" value="book">
					<input type="hidden" name="location" value="<?php echo $_POST['destination'] ?>">
					<?php
						if(!isset($username)){//未登录，跳转至登录界面
					?>
					<a href="#login" data-toggle="modal" class="btn btn-primary btn-large disabled" >提交预定&raquo;</a>
					<?php
						}else{//已登录，显示提交按钮
					?>
					<button type="submit" class="btn btn-primary btn-large" >提交预定&raquo;</button>
					<?php
						}
					?>
					
					&nbsp;&nbsp;&nbsp;
					<a href="./book.php" class="btn btn-large ">重新查询</a>
					</form>
			<?php
					}
				}
			?>
			
		<?php include './footer.php' ?>
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
			$('.typeahead').typeahead();
		</script>
	</body>
</html>