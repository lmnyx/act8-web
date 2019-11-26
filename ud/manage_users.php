<?php
RedirectIfNoPerms("manage_users");
?>
<head>
    <title>act8 | User Manager</title>
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
	<div class="title">User Manager <button class="logbtn" onclick="AddUserWin();">Add new</button></div>
	<br>
	
	<?php
	foreach (GetAllUsers() as $user) {
		$realPerm = $user['perm'] == -1 ? "<qeq class='rainbow rainbow_text_animated'>".$_string_perms[$user['perm']]."</qeq>" : $_string_perms[$user['perm']];
		$ifRoot = $user['perm'] == -1 ? "huerotator" : "";
		$ifrootimg = $user['perm'] == -1 ? "/uploads/elvexiconhd2.png" : "/uploads/user.jpg";
		print('<div class="_user" onclick="EditUser(&quot;'.$user['username'].'&quot;);">
		<img src="'.$ifrootimg.'" class="'.$ifRoot.'"> <span>'.$user['username'].' | '.$user['tokens'].' <i class="fas fa-coins"></i> | '.$realPerm.'</span>
	</div>');
	}
	?>
</div>
<div class="edituser_box hidden" id="EditUser">
	<div class="edituser_title">
		<span>Edit User</span></div>
	<div class="edituser_content">
		Username:<br>
		<input type="text" id="username" placeholder="Username" class="ud_i fw"><br>
		Tokens:<br>
		<input type="text" id="tokens" placeholder="Tokens" class="ud_i fw"><br>
		Permission: <br>
		<select class="ud_i fw" id="perm">
			<?php
			foreach ($_string_perms as $id => $v) {
				if($id == -1)
				{
					print("<option value='".$id."' disabled>".$v."</option>");
				}
				else
				{
					print("<option value='".$id."'>".$v."</option>");
				}
			}
			?>
		</select><br>
		<button class="logbtn" onclick="SubmitEdition();">Confirm</button><button class="logbtn" onclick="DeleteUser();">Delete</button>
	</div>
</div>
<div class="edituser_box hidden" id="AddUser">
	<div class="edituser_title">
		<span>Add User</span></div>
	<div class="edituser_content">
		Username:<br>
		<input type="text" id="addusername" placeholder="Username" class="ud_i fw"><br>
		Password:<br>
		<input type="password" id="addpswd" placeholder="Password" class="ud_i fw"><br>
		Tokens:<br>
		<input type="number" id="addtokens" placeholder="Tokens" class="ud_i fw"><br>
		Permission: <br>
		<select class="ud_i fw" id="addperm">
			<?php
			foreach ($_string_perms as $id => $v) {
				if($id == -1)
				{
					print("<option value='".$id."' disabled>".$v."</option>");
				}
				else
				{
					print("<option value='".$id."'>".$v."</option>");
				}
			}
			?>
		</select><br>
		<button class="logbtn" onclick="SubmitAdd();">Confirm</button>
	</div>
</div>
<script type="text/javascript">
var _currentEditingID = "";
function AddUserWin()
{
	var eu = document.getElementById("AddUser").style.display = "block";
	$("body").addClass("bluredv2");
}
function DeleteUser()
{
	if(confirm("Are you sure you want delete this user?"))
	{
		$.post(
			{
				url: "methods.php",
				data: {"method": "acc.delete", "login": _currentEditingID},
				success: function(d)
				{
					if(d == "OK"){ alert("User deleted!"); window.location.reload(); return; }
					alert("You cannot delete this user.");
				}
			});
	}
}
function SubmitAdd()
{
	$.post(
		{
			url: "methods.php",
			data: {"method": "acc.create", "login": $("#addusername").val(), "pswd": $("#addpswd").val(), "tokens": $("#addtokens").val(), "perm": $("#addperm").val()},
			success: function(d)
			{
				if(d == "EXISTS"){ alert('User with that name already exists!'); }
				else if(d == "BAD"){ alert("Unknown error appeared. Try again later or check the errors."); }
				else if(d == "LENGTH"){ alert("Length of username is too small."); }
				else if(d == "OK") { alert("User Added!"); }
				else { alert(d); }
				$("#addusername").val("");
				$("#addpswd").val("");
				$("#addtokens").val("");
				$("#addperm").val(0);
				var eu = document.getElementById("AddUser").style.display = "none";
				$("body").removeClass("bluredv2");
			}
		});
}
function EditUser(id)
{
	console.log(id);
	window._currentEditingID = id;
	$.post(
		{
			url: "methods.php",
			dataType: "json",
			data: {"method": "acc.get", "login": id},
			success: function(d)
			{
				console.log(d);
				if(d == "BAD"){ alert("User doesn't exists or you don't have a permission to use this."); return; }
				$("#username").val(d['username']);
				$("#tokens").val(d['tokens']);
				$("#perm").val(d['rank']);
				var eu = document.getElementById("EditUser").style.display = "block";
				$("body").addClass("blured");
			}
		});
}
function SubmitEdition()
{
	$.post(
		{
			url: "methods.php",
			data: {"method": "acc.edit", "oldname": _currentEditingID, "login": $("#username").val(), "tokens": $("#tokens").val(), "perm": $("#perm").val()},
			success: function(d)
			{
				if(d=="BAD"){ alert("Changing failed!"); }
				else if (d == "VIOLATION") { alert("You cannot edit this user."); }
				else { alert("Updated!"); }
				$("body").removeClass("blured");
				var eu = document.getElementById("EditUser").style.display = "none";
			}
		});
}
</script>