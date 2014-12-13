<?php
require "./database.php";
require "./useraction.php";
?>
<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<title>Make Your Travel Easy</title>
		<meta name="description" content="Make Your Travel Easy">
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
			<!-- 加入导航栏	-->
			<?php include './navbar.php' ?>
		</div>
	
		<div class="container">
			<?php echo $warning ?>
			<?php
			if (!isset($username)){//未登录
			?>
				<p><h2>您尚未登录，请<a href="#login" data-toggle="modal">登录</h2></a></p>
			<?php
			}else{//已登录
				if (isset($_POST['aaction'])){
					if($_POST['aaction']=='book'){//提交订票
						if(!isset($_POST['flight'])){//没有符合条件的航班，酒店和出租车预定不能提交
			?>
							<div class="alert alert-error fade in" style="text-align: center;">
								<a class="close" data-dismiss="alert" href="#">x</a>
								<strong>请订购机票</strong>
							</div>
					<?php
						}else{//预定机票，酒店，出租车
						//addslashes(str) 在str前面添加反斜杠，否则在database中不安全
						//订购机票
							$db->query("UPDATE FLIGHTS SET numAvail=numAvail-1 WHERE flightNum='".addslashes($_POST['flight'])."' AND numAvail>0");
							//有航班符合
							if ($db->affected_rows==1)
								$db->query("INSERT INTO RESERVATIONS VALUES('$username',1,NULL,'".addslashes($_POST['flight'])."')");
							//无航班符合（机票已售完）
							else $ferr=1;
							if(!isset($ferr)){
								if(isset($_POST['bcar'])){
									//订购出租车
									$db->query("UPDATE CARS SET numAvail=numAvail-1 WHERE location='".addslashes($_POST['location'])."' AND numAvail>0");
									if ($db->affected_rows==1)
										$db->query("INSERT INTO RESERVATIONS VALUES('$username',3,NULL,'".addslashes($_POST['location'])."')");
									else $cerr=1;
								}
								if(isset($_POST['bhotel'])){
									//订购酒店
									$db->query("UPDATE HOTELS SET numAvail=numAvail-1 WHERE location='".addslashes($_POST['location'])."' AND numAvail>0");
									if ($db->affected_rows==1)
										$db->query("INSERT INTO RESERVATIONS VALUES('$username',2,NULL,'".addslashes($_POST['location'])."')");
									else $herr=1;
								}
							}
							if (!isset($ferr)&&!isset($herr)&&!isset($cerr)){//成功订购机票，酒店，出租车
					?>
								<div class="alert alert-success fade in" style="text-align: center;">
									<a class="close" data-dismiss="alert" href="#">x</a>
									<strong>恭喜！您已成功完成预约。</strong>
								</div>
					<?php
						}else{
							if (isset($ferr)){//无机票
					?>
								<div class="alert alert-error fade in" style="text-align: center;">
									<a class="close" data-dismiss="alert" href="#">x</a>
									<strong>所选航班已无剩余座位，无法订购机票。<strong>
								</div>
						<?php
							}else{//机票预定成功，出租车和酒店预定失败
										?>
												<div class="alert alert-error fade in" style="text-align: center;">
													<a class="close" data-dismiss="alert" href="#">x</a>
													<strong>航班已预定。但<?php if(isset($herr)) echo "已无剩余房间"?>
																									<?php if(isset($cerr)&&isset($herr)) echo "，"?>
																									<?php if(isset($cerr)) echo "已无剩余车辆"?></strong>
												</div>
				<?php
								}
							}
						}//预定机票，酒店，出租车结束
					}else
					//退订操作，根据del退订机票，酒店或者出租车
					if ($_POST['aaction']=='remove'&&isset($_POST['del'])){
						foreach ($_POST['del'] as $item){
							$record=$db->query("SELECT * FROM RESERVATIONS WHERE resvKey='$item'");
							if ($record->num_rows==0) {//退订不存在的预定，出错
								echo "此预定不存在，无法退订";
								exit; 
							}
							$row=$record->fetch_row();
							switch($row[1]){//根据resrType更新数据库
								case 1:$db->query("UPDATE FLIGHTS SET numAvail=numAvail+1 WHERE flightNum='$row[3]'");break;//退订机票
								case 2:$db->query("UPDATE HOTELS SET numAvail=numAvail+1 WHERE location='$row[3]'");break;//退订酒店
								case 3:$db->query("UPDATE CARS SET numAvail=numAvail+1 WHERE location='$row[3]'");break;//退订出租车
								default:	echo "不存在此类型预定，无法退订";
												exit; 
												break;
							}
							//删除预定条目
							$db->query("DELETE FROM RESERVATIONS WHERE resvKey='$item'");
						}
				?>
					<div class="alert alert-success fade in" style="text-align: center;">
						<a class="close" data-dismiss="alert" href="#">x</a>
						<strong>删除预定成功</strong>
					</div>
		<?php
					}
				}
		?>
		
		
		<!--	显示预定信息	-->
			<br>
			<h1>欢迎您，<?php echo $username ?>&nbsp;&nbsp;&nbsp;</h1><br>

			<p><h3>您的预定：</h3></p>
		<?php
			$record=$db->query("SELECT * FROM RESERVATIONS WHERE custName='$username'");
			if ($record->num_rows==0){//无预定
		?>
			<div class="alert alert-error fade in" style="text-align: center;">
				<a class="close" data-dismiss="alert" href="#">x</a>
				<strong>您目前没有任何预定</strong>
			</div>
		<?php
			}else{//显示预定列表
		?>
				<form method="post" action="./account.php">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>预定号</th>
								<th>预定类型</th>
								<th>航班号/地点</th>
								<th>删除</th>
							</tr>
						</thead>
						
						<tbody>
					<?php
							while ($row=$record->fetch_row()){
								switch($row[1]){
								case 1:
									$type="航班";
									break;
								case 2:
									$type="酒店";
									break;
								case 3:
									$type="租车";
									break;
								default:break;
								}
								echo "<tr><td>";
								printf("%05d",$row[2]);//显示预定号
								//显示预定类型，预定相关信息，删除复选框
								echo "</td><td>$type</td><td>$row[3]</td><td><input type=\"checkbox\" name=\"del[]\" value=\"$row[2]\"></td></tr>";
						}
					?>
						</tbody>
					</table>
					<input type="hidden" name="aaction" value="remove">
					<button type="submit" class="btn btn-large btn-danger">删除预定</button>
				</form>
	<?php
				}
			}
	?>
		<?php include './footer.php' ?>
		</div>
		
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
			$("a[rel=popover]").popover()
		</script>
	</body>
</html>