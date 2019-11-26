<?php
require("fns.php");
if(count($_FILES) < 1 || !isset($_FILES['_buildUpload']))
{
	die('FAILURE');
}
session_start();
if(!UserHasPerm($_SESSION['login'], "publish_builds")) { die('FAILURE'); }
if($_FILES['_buildUpload']['type'] != 'application/x-zip-compressed'){ die('FAILURE'); }
$builds = json_decode(file_get_contents("builds.json"), true);
$builds[gnes(12)] = array('name' => $_POST['build_name'], "filename" => $_FILES['_buildUpload']['name'], "users"=>[]);
file_put_contents("builds.json", json_encode($builds));
move_uploaded_file($_FILES['_buildUpload']['tmp_name'], './builds/'.$_FILES['_buildUpload']['name']);
die('OK');
?>