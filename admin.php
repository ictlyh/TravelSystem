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
		
		<div class="container">
		<?php
			if(!isset($_POST['admin-action']))
			{//管理员未登录时不再显示导航栏
		?>
				<div class="hero-unit">
					<h1>管理员登录&nbsp;&nbsp;</h1><br><br>
					<form class="form-horizontal" method="post" action="./admin.php">
						<div class="control-group">
							<label class="control-label"><h3>用户名</h3></label>
							<div class="controls">
								<!--	为了方便他人查看管理员界面，提供缺省管理员和密码,管理员在ADMIN表中预先插入，不提供注册管理员-->
								<input type="text" class="input-large btn-large" name="admin-user" value="root">
							</div>
						</div>
							<div class="control-group">
							<label class="control-label"><h3>密码</h3></label>
							<div class="controls">
								<input type="password" class="input-large btn-large" name="admin-password" value="root">
							</div>
					</div>
						<input type="hidden" name="admin-action" value="login">
						<button type="submit" class="btn btn-large btn-primary"><i class="icon-book icon-white"></i> 登录 &raquo;</button>
					</form>
				</div>
		<?php
			}else
			{//管理员登录后显示导航栏，以便退出
		?>
				<div class="navbar navbar-fixed-top">
					<?php include './navbar.php' ?>
				</div>
				
			<?php
				if(isset($adminname))
				{
			?>
					<div class="row">
							<div class="hero-unit">
								<h2>顾客订购信息查询</h2>
								<form class="form-horizonal" method="post" action="./admin.php">
									<div class="control-group"><br><br>
										<div class="controls">
											<input type="text" class="input-large btn-large" name="custom" placeholder="顾客姓名">
										</div>
									</div>
									<input type="hidden" name="admin-action" value="adminquery">
									<button type="submit" class="btn btn-large btn-primary"><i class="icon-book icon-white"></i> 查询 &raquo;</button>
								</form>
							</div>
							
					<?php
						if(isset($_POST['admin-action']) && isset($_POST['custom']) && ($_POST['admin-action']=='adminquery'||$_POST['admin-action']=='adminremove'))
						{
							//查询顾客预订信息	
							$record=$db->query("SELECT * FROM RESERVATIONS WHERE custName='".$_POST['custom']."'");
							if($_POST['admin-action']=='adminremove'&&isset($_POST['del']))
							{
								//删除顾客预定
								foreach ($_POST['del'] as $item)
								{
									$record=$db->query("SELECT * FROM RESERVATIONS WHERE resvKey='$item'");
									if ($record->num_rows==0) {
										//顾客预订不存在
										echo "database error";
										exit; 
									}
									$row=$record->fetch_row();
									switch($row[1])
									{
										case 1:$db->query("UPDATE FLIGHTS SET numAvail=numAvail+1 WHERE flightNum='$row[3]'");break;//退订机票
										case 2:$db->query("UPDATE HOTELS SET numAvail=numAvail+1 WHERE location='$row[3]'");break;//退订酒店
										case 3:$db->query("UPDATE CARS SET numAvail=numAvail+1 WHERE location='$row[3]'");break;//退订出租车
										default:	echo "database error";
														exit; 
														break;
									}
									//删除预定条目
									$db->query("DELETE FROM RESERVATIONS WHERE resvKey='$item'");
								}
							}//显示顾客预订信息
							$record=$db->query("SELECT * FROM RESERVATIONS WHERE custName='".$_POST['custom']."'");
							if($record->num_rows == 0)
								echo '此顾客没有预定';
							else
							{
					?>
									<form method="post" action="./admin.php">
										<p><h3><?php echo $_POST['custom'] ?>的预定信息：</h3></p>
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
													printf("%05d",$row[2]);
													echo "</td><td>$type</td><td>$row[3]</td><td><input type=\"checkbox\" name=\"del[]\" value=\"$row[2]\"></td></tr>";
											}
										?>
											</tbody>
										</table>
										<input type="hidden" name="admin-action" value="adminremove">
										<input type="hidden" name="custom" value=<?php echo $_POST['custom'] ?>>
										<button type="submit" class="btn btn-large btn-danger">删除预定</button>
									</form>
					<?php
							}
						}
					?>
				</div>
				<div class="row">
						<div class="hero-unit">
								<h2>数据库信息更新</h2>
								<form class="form-horizonal" method="post" action="./admin.php">
									<br><br><br><br><br>
									<input type="hidden" name="admin-action" value="dbrefresh">
									<button type="submit" class="btn btn-large btn-primary"><i class="icon-book icon-white"></i> 显示数据库信息 &raquo;</button>
								</form>
						</div>
							
							<?php
								/*如何在php中使用mysql 的 source 命令？？？
								if(isset($_POST['admin_action'])&&($_POST['admin_action']=='dbreload'))
								{
									$query="source E:/Program Files/Apache Software Foundation/Apache2.2/htdocs/project2/peoject2.sql";
									echo $query;
									$db=$db->query($query);
									//$db=$db->query("source E:/Program Files/Apache Software Foundation/Apache2.2/htdocs/project2/peoject2.sql");
								}*/
								if(isset($_POST['admin-action'])&&($_POST['admin-action']=='dbrefresh'))
								{
									if(isset($_POST['f_del']))
									{
										//删除航班信息
										foreach($_POST['f_del'] as $item)
										{
											$record=$db->query("SELECT * FROM FLIGHTS WHERE flightNum = '$item'");
											if($record->num_rows == 0)
											{
												//航班不存在
												echo "航班不存在";
												exit;
											}
											//删除此航班
											$db->query("DELETE FROM FLIGHTS WHERE flightNum = '$item'");
											//删除此航班的预定
											$db->query("DELETE FROM RESERVATIONS WHERE aditInfo = '$item'");
										}
									}
									if(isset($_POST['c_del']))
									{
										//删除租车信息
										foreach($_POST['c_del'] as $item)
										{
											$record=$db->query("SELECT * FROM CARS WHERE location = '$item'");
											if($record->num_rows == 0)
											{
												//租车不存在
												echo "出租车不存在";
												exit;
											}
											//删除此租车
											$db->query("DELETE FROM CARS WHERE location = '$item'");
											//删除此租车预定
											$db->query("DELETE FROM RESERVATIONS WHERE aditInfo = '$item' AND resvType = 3");
										}
									}
									if(isset($_POST['h_del']))
									{
										//删除酒店信息
										foreach($_POST['h_del'] as $item)
										{
											$record=$db->query("SELECT * FROM HOTELS WHERE location = '$item'");
											if($record->num_rows == 0)
											{
												//酒店不存在
												echo "酒店不存在";
												exit;
											}
											//删除此酒店
											$db->query("DELETE FROM HOTELS WHERE location = '$item'");
											//删除此酒店的预定
											$db->query("DELETE FROM RESERVATIONS WHERE aditInfo = '$item' AND resvType = 2");
										}
									}
									if(isset($_POST['u_del']))
									{
										//删除顾客
										foreach($_POST['u_del'] as $item)
										{
											$record=$db->query("SELECT * FROM CUSTOMERS WHERE custName = '$item'");
											if($record->num_rows == 0)
											{
												//顾客不存在
												echo "顾客不存在";
												exit;
											}
											$db->query("DELETE FROM CUSTOMERS WHERE custName = '$item'");
											//删除此顾客的预定
											$result = $db->query("SELECT * FROM RESERVATIONS WHERE custName = '$item'");
											while(list($name,$type,$key,$info) = $result->fetch_row())
											{
												switch($type)
												{
													case 1:$db->query("UPDATE FLIGHTS SET numAvail=numAvail+1 WHERE flightNum='$info'");break;//退订机票
													case 2:$db->query("UPDATE HOTELS SET numAvail=numAvail+1 WHERE location='$info'");break;//退订酒店
													case 3:$db->query("UPDATE CARS SET numAvail=numAvail+1 WHERE location='$info'");break;//退订出租车
													default:	echo "database error";exit;
												}
												//删除预定条目
												$db->query("DELETE FROM RESERVATIONS WHERE resvKey='$key'");	
											}				
										}
									}
									
									if(isset($_POST['f_num']) && isset($_POST['f_price']) && isset($_POST['f_total']) && isset($_POST['f_avail']) && isset($_POST['f_start']) && isset($_POST['f_dest'])) 															
										$dbquery=$db->query("INSERT INTO FLIGHTS VALUES('".addslashes($_POST['f_num'])."',".addslashes($_POST['f_price']).",".addslashes($_POST['f_total']).",".addslashes($_POST['f_avail']).",'".addslashes($_POST['f_start'])."','".addslashes($_POST['f_dest'])."')");
									if(isset($_POST['c_location']) && isset($_POST['c_price']) && isset($_POST['c_total']) && isset($_POST['c_avail'])) 									
										$dbquery=$db->query("INSERT INTO CARS VALUES('".addslashes($_POST['c_location'])."',".addslashes($_POST['c_price']).",".addslashes($_POST['c_total']).",".addslashes($_POST['c_avail']).")");
									if(isset($_POST['h_location']) && isset($_POST['h_price']) && isset($_POST['h_total']) && isset($_POST['h_avail'])) 
										$dbquery=$db->query("INSERT INTO HOTELS VALUES('".addslashes($_POST['h_location'])."',".addslashes($_POST['h_price']).",".addslashes($_POST['h_total']).",".addslashes($_POST['h_avail']).")");
									
									$record=$db->query("SELECT * FROM FLIGHTS");
							?>
									<form method="post" action="./admin.php">
										<p><h3>航班信息：</h3></p>
										<table class="table table-striped table-bordered">
											<thead>
												<tr>
													<th>航班号</th>
													<th>价格</th>
													<th>座位总数</th>
													<th>剩余座位</th>
													<th>出发地</th>
													<th>目的地</th>
													<th>删除</th>
													</tr>
												</thead>
												
												<tbody>
						<?php
													while($row=$record->fetch_row())
														echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td><td><input type=\"checkbox\" name=\"f_del[]\" value=\"$row[0]\"></td></tr>";				
												echo"<tr><td width=20px><input type=\"text\" name=\"f_num\"></td><td><input type=\"text\" name=\"f_price\"></td><td><input type=\"text\" name=\"f_total\"></td><td><input type=\"text\" name=\"f_avail\"></td><td><input type=\"text\" name=\"f_start\"></td><td><input type=\"text\" name=\"f_dest\"></td><td></td></tr>";
						?>
													</tbody>
											</table>
						<?php
									$record=$db->query("SELECT * FROM CARS");
							?>		
										<p><h3>租车信息：</h3></p>
										<table class="table table-striped table-bordered">
											<thead>
												<tr>
													<th>地点</th>
													<th>价格</th>
													<th>车辆总数</th>
													<th>剩余车辆</th>
													<th>删除</th>
													</tr>
												</thead>
												
												<tbody>
						<?php
													while($row=$record->fetch_row())
														echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td><input type=\"checkbox\" name=\"c_del[]\" value=\"$row[0]\"></td></tr>";				
													echo"<tr><td><input type=\"text\" name=\"c_location\"></td><td><input type=\"text\" name=\"c_price\"></td><td><input type=\"text\" name=\"c_total\"></td><td><input type=\"text\" name=\"c_avail\"></td><td></td></tr>";
							?>					
												</tbody>
										</table>
						<?php			
									$record=$db->query("SELECT * FROM HOTELS");
							?>		
										<p><h3>酒店信息：</h3></p>
										<table class="table table-striped table-bordered">
											<thead>
												<tr>
													<th>地点</th>
													<th>价格</th>
													<th>房间总数</th>
													<th>剩余房间</th>
													<th>删除</th>
													</tr>
												</thead>
												
												<tbody>
						<?php
												while($row=$record->fetch_row())
													echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td><input type=\"checkbox\" name=\"h_del[]\" value=\"$row[0]\"></td></tr>";				
						  					echo"<tr><td><input type=\"text\" name=\"h_location\"></td><td><input type=\"text\" name=\"h_price\"></td><td><input type=\"text\" name=\"h_total\"></td><td><input type=\"text\" name=\"h_avail\"></td><td></td></tr>";
						  ?>
						  					</tbody>
						  			</table>
						<?php			
									$record=$db->query("SELECT * FROM CUSTOMERS");
							?>
						  			<p><h3>顾客信息：</h3></p>
										<table class="table table-striped table-bordered">
											<thead>
												<tr>
													<th>姓名</th>
													<th>密码</th>
													<th>删除</th>
													</tr>
												</thead>
												
												<tbody>
						<?php
													while($row=$record->fetch_row())
														echo "<tr><td>$row[0]</td><td>$row[1]</td><td><input type=\"checkbox\" name=\"u_del[]\" value=\"$row[0]\"></td></tr>";

						?>
													</tbody>
											</table>
						<?php
									$record=$db->query("SELECT * FROM RESERVATIONS");
							?>
										<p><h3>预定信息：</h3></p>
										<table class="table table-striped table-bordered">
											<thead>
												<tr>
													<th>预订人</th>
													<th>预定号</th>
													<th>预定类型</th>
													<th>航班号/地点</th>
												</tr>
											</thead>
									
											<tbody>
						<?php
											while ($row=$record->fetch_row())
											{
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
												echo "<tr><td>$row[0]</td><td>";
												printf("%05d",$row[2]);
												echo "</td><td>$type</td><td>$row[3]</td></tr>";
											}
							?>
									</tbody>
								</table>

									<input type="hidden" name="admin-action" value="dbrefresh">
									<button type="submit" class="btn btn-large btn-danger">刷新数据库</button>
                                    <button type="button" class="btn btn-large btn-danger"><a href="http://localhost/phpmyadmin" target="_blank">管理数据库</a></button>
							  <!--	<input type="hidden" name="admin_action" value="dbreload">
									<button type="submit" class="btn btn-large btn-danger">加载初始数据库</button>-->
									</form>
						<?php	
								}
							?>		
				</div>
			<?php
				}
			?>			
		<?php
			}
		?>
		</div>
		
		<!--	在此页面中不会触发#login或者#register，故可以不添加隐藏的登录注册对话框	-->
		<?php include './footer.php' ?>
		
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
			$('.typeahead').typeahead();
		</script>
	</body>
</html>