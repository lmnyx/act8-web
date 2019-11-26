<?php
RedirectIfNoPerms("visit");
?>
<head>
    <title>act8 | Publishing Builds</title>
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
	<div class="title">Manage Builds <form id="upFileForm" style="display: none;"><input type="file" name="_buildUpload" id="_buildUpload" style="display: none;" allow="*.zip,*.rar"><input type="text" name="build_name" id="build_name" style="display: none;"></form><button class="logbtn" onclick="PrepareUpload();" id="upBtn">Upload new</button></div>
	<br>
	<?php

	$builds = json_decode(file_get_contents("builds.json"), true);
	if(count($builds) < 1){ print('<p>No builds was found. Be first to upload one!</p>'); }
	foreach ($builds as $BID => $binfo) {
		print('<div class="_build wh">
		<img src="/uploads/download_icon.png" onclick="DlBuild(&quot;'.$BID.'&quot;);"><span>'.$binfo['name'].'</span>
		<button onclick="DeleteBuild(&quot;'.$BID.'&quot;);return false;">&times;</button>
	</div>');
	}

	?>
	
</div>

<script type="text/javascript">
function DlBuild(id)
{
	window.location.href = "dl_build.php?id="+id;
}
function DeleteBuild(id)
{
	if(confirm("Are you sure you want delete this build?"))
	{
		$.post(
			{
				url: "methods.php",
				data: {"method": "build.del", 'bid': id},
				success: function(d)
				{
					if(d == "FAILURE")
					{
						alert('Uh-oh.. Something went wrong.. Maybe someone already deleted this build?');
					}
					else
					{
						alert("Build was deleted!");
						window.location.reload();
					}
				}
			});
	}
	else
	{
		return;
	}
}
function PrepareUpload()
{
	var BuildName = prompt("Enter build name: ", '');
	$("#build_name").val(BuildName);
	if(BuildName==null){return false;}
	$('#_buildUpload').click();

}
$("#_buildUpload").change(function(){
	if($("#_buildUpload").val() == "" || $("#_buildUpload").val() == null)
	{
		return;
	}
	else
	{
		$.ajax(
			{
				url: "upload_build.php",
				type: "POST",
				data: new FormData($("#upFileForm")[0]),
				cache: false,
				contentType: false,
				processData: false,
				xhr: function()
				{
					var myXhr = $.ajaxSettings.xhr();
					if(myXhr.upload)
					{
						$("#upBtn").attr("disabled", true);
						myXhr.upload.addEventListener('progress', function(e)
							{
								if(e.lengthComputable)
								{
									$("#upBtn").html("Uploading..."+((e.loaded/e.total*100).toFixed(2))+"%");
								}
							}, false);
					}
					return myXhr;
				}
			});
		$("#upBtn").attr("disabled", false);
		$("#upBtn").html("Upload new");
	}
 });
</script>