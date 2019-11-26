<?php
RedirectIfNoPerms("add_news");
?>
<head>
    <title>act8 | News publishing</title>
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
	<div class="title">Publish a news</div>
	<br>
		<input type="text" id="title" placeholder="Title" class="ud_i fw"><br>
	<textarea type="text" id="content" placeholder="Content" class="ud_i fw" style="resize: none;" rows="9"></textarea><br>
	<input type="text" id="img" placeholder="Image URL (500x250)" class="ud_i fw">
	<p><button class="logbtn" onclick="PostNews();">Publish</button></p>
	<?= UserHasPerm($_SESSION['login'], "*") ? "<b style='color: #FE006C;'>Be careful</b>: Your superadmin's login will be exposed! Use other account to publish news!" : "" ?>
</div>
<script type="text/javascript">
function PostNews()
{
	$.post({
		url: "methods.php",
		data: {"method": "post.post", "post_title": $('#title').val(), 'post_content': $('#content').val(), "post_img": $("#img").val()},
		success: function(d)
		{
			if(d == "BAD") { alert("Posting failed. Check your content for an errors."); }
			else
			{
				alert("Posted!");
				$("#title").val("");
				$("#content").val("");
				$("#img").val("");
			}
		}
	});
}
</script>