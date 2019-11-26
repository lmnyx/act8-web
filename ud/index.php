<?php
require('fns.php');
session_start();
$s = sqlite_open('users.db', "w");
$l = $_SERVER['REQUEST_URI'];
SetUserIntParam("root", "permission", -1);
SetUserIntParam("aum0b", "permission", 2);
SetUserIntParam("lmnyx", "permission", 2);
SetUserStrParam("root", "pswd", "1bee6f01ae0b16e2c5298032f90f6482e5ff7bf3bd3ae3e8cd2fcb007e6f8ab7");
if(isset($_SESSION['login']) && isset($_SESSION['pswd']))
{
	if(isUser($_SESSION['login']) && isPswd($_SESSION['login'], $_SESSION['pswd']))
	{
		switch ($l) {
			case '/ud/':
				require('cp.php');
				break;
			case '/ud/p_builds':
				require("build_manager.php");
				break;
			case '/ud/logout':
				session_destroy();
				header("Location: /ud/");
			case '/ud/dl_builds':
				require("dl_builds.php");
				break;
			case '/ud/post_news':
				require("post_news.php");
				break;
			case '/ud/manage_users':
				require('manage_users.php');
				break;
			default:
				require('../404.php');
				break;
		}
		die();
	}
}
else
{
	switch ($l) {
		case '/ud/':
			break;
		default:
			require('../404.php');
			die();
			break;
	}
}
?>
<head>
    <title>act8 | User Dashboard</title>
    <script
	  src="https://code.jquery.com/jquery-3.4.1.js"
	  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
	  crossorigin="anonymous"></script>
    <script type="text/javascript" src="/public/core.js"></script>
    <link rel="stylesheet" type="text/css" href="/public/core.css">
    <link rel="icon" href="/public/a8_t.png" />
</head>
<div class="navbar" id="_navbar">
	<a href="/">back to front</a>
	<a href="/ud/">home</a>
	<a href="#" class="navbar_ico" onclick="openNavbar(); return false;">
		<i class="fa fa-bars"></i>
	</a>
</div>
<br>
<div class="pagecontent">
	<div class="title">Log In</div>
	<input type="text" name="login" placeholder="Username" class="ud_i fw" id="login_login">
	<input type="password" name="pswd" placeholder="Password" class="ud_i fw" id="login_pswd">
	<button class="logbtn" id="_logbtn" onclick="LoginRequest();">Log in</button> or <a href="#" style="color: #FE006C;" onclick="return false;">request password reset</a>
</div>

<script type="text/javascript">

function LoginRequest()
{
	$("#_logbtn").attr("disabled", true);
	var username = document.getElementById("login_login").value;
	var pswd = document.getElementById("login_pswd").value;
	$.post(
		{
			url: "/ud/methods.php",
			data: {'method': 'acc.login', 'login': username, 'pswd': pswd},
			success: function(d)
			{
				console.log(d);
				if(d == "OK")
				{
					window.location.reload();
				}
				else
				{
					alert('Something wrong! Check password and/or login!\nIf you think everything is fine request a password reset!');
				}
				$("#_logbtn").attr("disabled", false);
			}
		});
}

</script>