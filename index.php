<?php

if($_SERVER['REQUEST_URI'] != "/")
{
	http_response_code(200);
	switch ($_SERVER['REQUEST_URI']) {
		case '/games':
			require('games.php');
			break;
		case '/contact':
			require('contact.php');
			break;
		case '/license':
			require("license.php");
			break;
		
		default:
			require("404.php");
			exit();
			break;
	}
	exit();
}

?>
<head>
    <title>act8</title>
    <script type="text/javascript" src="/public/core.js"></script>
    <link rel="stylesheet" type="text/css" href="/public/core.css">
    <link rel="icon" href="/public/a8_t.png" />
</head>
<div class="navbar" id="_navbar">
	<a href="/">home</a>
	<a href="/games">games</a>
	<a href="/contact">contact</a>
	<a href="#" class="navbar_ico" onclick="openNavbar(); return false;">
		<i class="fa fa-bars"></i>
	</a>
</div>
<br>
<div class="newspaper">
	<br>
	<?php
	$posts = json_decode(file_get_contents("posts.json"), true);
	$posts = array_reverse($posts);
	foreach($posts as $post)
	{
		$img = $post['img'] == "" ? "" : '<img src="'.$post['img'].'" class="image">';
		print('<div class="news">
		<div class="title">'.$post['title'].'</div>
		'.$img.'
		<div class="content">'.$post['content'].'
		</div>
		<div class="additional">posted by <a href="#" onclick="return false;" class="author">'.$post['author'].'</a>.</div></div>');
	}
	?>
</div>

<br>
<br>
<div class="_bottominfo">created by <span style="color: #FE006C;">act8 team</span>!<br>
	<a href="/license" class="blank">legal information</a></div>