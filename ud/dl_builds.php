<?php
RedirectIfNoPerms("dl_builds");
?>
<head>
    <title>act8 | Download Builds</title>
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
	<div class="title">Download Builds</div>
	<br>
	<?php

	$builds = json_decode(file_get_contents("builds.json"), true);
	if(count($builds) < 1){ print('<p>No builds was found. Wait until someone uploads one!</p>'); }
	foreach ($builds as $BID => $binfo) {
		print('<div class="_build wh">
		<img src="/uploads/download_icon.png" onclick="DlBuild(&quot;'.$BID.'&quot;);"><span>'.$binfo['name'].'</span>
	</div>');
	}

	?>
</div>
<script type="text/javascript">
function DlBuild(id)
{
	window.location.href = "dl_build.php?id="+id;
}
</script>