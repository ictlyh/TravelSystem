<?php
	//定义一个数据库全局变量
	global $db;
	//绑定数据库
	$db = new mysqli("localhost","root","ustclyh","project2");
	if (mysqli_connect_errno())
	{
		printf("Database Connect Failed. Error: %s\n",mysqli_connect_error());
		exit();
	}
?>