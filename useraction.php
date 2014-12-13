<?php
	if (isset($_POST['action'])){
		//用户提交动作
		if ($_POST['action']=='login'){//用户登录
			//查询用户密码
			$dbquery=$db->query("SELECT password FROM CUSTOMERS WHERE custName='".addslashes($_POST['user'])."'");
			if ($dbquery->num_rows!=0){//用户存在
				$record=$dbquery->fetch_row();
				if ($record[0]==md5($_POST['password'])){//密码正确
					global $username;
					$username=$_POST['user'];
					//向客户机发送cookie，包括cookie的名称，值，有效期2h
					setcookie('user',$_POST['user'],time()+7200);
					setcookie('password',md5($_POST['password']),time()+7200);
				}else 
					$error="密码错误";
			}else
				$error="用户不存在";
		}else if ($_POST['action']=='register'){//用户注册
			//用户名符合命名规则且两个密码相同
			if($_POST['password']==$_POST['password-confirm']&&preg_match('/^[a-z\d]{7,16}$/i',$_POST['user'])){
				//插入用户
				$dbquery=$db->query("INSERT INTO CUSTOMERS VALUES('".addslashes($_POST['user'])."','".md5($_POST['password'])."')");
				if ($dbquery){//插入用户成功
						global $username;
						$username=$_POST['user'];
						setcookie('user',$_POST['user'],time()+7200);
						setcookie('password',md5($_POST['password']),time()+7200);
				}else//插入失败 
					$error="用户名已存在";
			}else
				$error="用户名不符合规范或密码不一致";
		}
	}else if (isset($_GET['action'])&&$_GET['action']=='logout'){//已登录，退出时使cookie失效
		setcookie('user','',time());
		setcookie('password','',time());
	}else if (isset($_COOKIE['user'])){//未登录，但存在cookie，自动登录
		$dbquery=$db->query("SELECT password FROM CUSTOMERS WHERE custName='".addslashes($_COOKIE['user'])."'");
		$record=$dbquery->fetch_row();
		//数据库中的密码和cookie中的密码相同
		if ($record[0]==$_COOKIE['password']){
			global $username;
			$username=$_COOKIE['user'];
		}
	}else if(isset($_POST['admin-action'])&&$_POST['admin-action']=='login'){//管理员登录
			$dbquery=$db->query("SELECT password FROM ADMIN WHERE adminName='".addslashes($_POST['admin-user'])."'");
			if ($dbquery->num_rows!=0){
				$record=$dbquery->fetch_row();
				if ($record[0]==md5($_POST['admin-password'])){
					global $adminname;
					$adminname=$_POST['admin-user'];
					setcookie('admin-user',$_POST['admin-user'],time()+7200);
					setcookie('admin-password',md5($_POST['admin-password']),time()+7200);
				}
		}
	}else if(isset($_GET['admin-action'])&&$_GET['admin-action']=='logout'){//管理员退出
		setcookie('admin-user',time());
		setcookie('admin-password',time());
	}else if (isset($_COOKIE['admin-user'])){//cookie存在，自动登录
		$dbquery=$db->query("SELECT password FROM ADMIN WHERE adminName='".addslashes($_COOKIE['admin-user'])."'");
		$record=$dbquery->fetch_row();
		if ($record[0]==$_COOKIE['admin-password']){
			global $adminname;
			$adminname=$_COOKIE['admin-user'];
		}
	}

	global $warning;
	if (isset($_POST['action'])&&!isset($username)){
		$warning='<div class="alert alert-error fade in" style="text-align: center;"><a class="close" data-dismiss="alert" href="#">x</a>';
		switch($_POST['action']){
			case "login":$warning.="<h4><strong>登录失败：</strong>".$error."</h4>";break;
			case "register":$warning.="<h4><strong>注册失败：</strong>".$error."</h4>";break;
			default: break;
		}
		$warning.='</div>';
	}
?>