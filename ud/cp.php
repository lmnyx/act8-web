<?php
RedirectIfNoPerms("visit");
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
	<a href="#" onclick="openNav();return false;"><i class="fas fa-bars"></i></a>
	<a href="/">back to front</a>
	<a href="/ud/">home</a>
	<a href="/ud/logout">logout</a>
	<a href="#" class="navbar_ico" onclick="openNavbar(); return false;">
		<i class="fa fa-bars"></i>
	</a>
</div>
	<?php require('sidebar.php'); ?>
<br>
<div class="pagecontent">
	<div class="title">User Dashboard</div>
	<p>Welcome to act8 User Dashboard, <span style="color: #FE006C;"><?= GET_NAME(); ?></span>!</p>
	<p>You currently have access as <span style="color: #FE006C;"><?= $_string_perms[GetUserParam($_SESSION['login'], "permission")]; ?></span>!</p>
	<p>You have <span style="color: #FE006C;"><?= GetUserParam($_SESSION['login'], "tokens"); ?></span> <i class="fas fa-coins"></i>.</p>
	<br>
	<p><span class="title_small">Change your password</span></p>
	<p><input type="password" name="curpswd" class="ud_i fw" id="curpswd" placeholder="Current password"><br>
	<input type="password" name="newpswd" class="ud_i fw" id="newpswd" placeholder="New password"><br>
	<button class="logbtn" onclick="RequestPasswordChange();">Change password</button></p>
</div>
<script type="text/javascript">
function RequestPasswordChange()
{
	var u = "<?= $_SESSION['login']; ?>";
	var c = document.getElementById("curpswd").value;
	var n = document.getElementById("newpswd").value;
	$.post(
		{
			url: "/ud/methods.php",
			data: {"method": "acc.resetpswd","login": u, "pswd": c, "newpswd": n},
			success: function(d)
			{
				if(d == "BAD")
				{
					alert("Umm... Something is wrong...\nMaybe you spelled your current password wrong or your new password is too small in size?");
				}
				else
				{
					alert('Password changed!\nYou will be redirected to login page.');
					window.location.href = "/ud/";
					window.location.reload();
				}
			}
		});
}
</script>