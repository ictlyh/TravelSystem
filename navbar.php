<div class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="index.php"><h3><font color="blue"><h3>Make Your Travel Easy</h3></font></a>
			
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			
			<div class="nav-collapse">
				<ul class="nav">
				<!-- 导航栏  根据用户是否登录，是否为管理员 显示不同 -->
					<?php
						if (isset($username)){	//客户已登录，显示我的订购，新订购，退出
					?>
						<li><p class="navbar-text">Welcome!  <?php echo $username; ?></p></li>
						<li class="divider-vertical"></li>
						<li <?php if($_SERVER['PHP_SELF']=='./account.php') echo ' class="active"'?>><a href="account.php"><h3>我的订购</h3></a></li>
						<li <?php if($_SERVER['PHP_SELF']=='./book.php') echo ' class="active"'?>><a href="book.php"><h3>新订购</h3></a></li>
						<li><a href="index.php?action=logout"><h3>退出</h3></a></li>
					<?php
						}else if(!isset($_POST['admin-action'])){	//用户未登录且不是管理员，显示管理员登录，客户登录，注册，查询
					?>
							<li><p class="navbar-text">欢迎来到 Make Your Travel Easy</p></li>
							<li class="divider-vertical"></li>
							<li><a href="./admin.php"><h3>管理员登录</h3></a></li>
							<li><a href="#login" data-toggle="modal"><h3>客户登录</h3></a></li>
							<li><a href="#register" data-toggle="modal"><h3>客户注册</h3></a></li>
							<li><a href="./book.php"><h3>查询</h3></a></li>
					<?php
						}else{//管理员已登录，显示退出
					?>
							<li><a href="index.php?action=logout"><h3>退出</h3></a></li>
					<?php
						}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>