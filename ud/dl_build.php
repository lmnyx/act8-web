<?php
require("fns.php");
session_start();
if(!UserHasPerm($_SESSION['login'], "dl_builds")){ die('no'); }
if(!isset($_GET['id']))
{
	die('no');
}
$builds = json_decode(file_get_contents("builds.json"), true);
if(!isset($builds[$_GET['id']])){ die('NO'); }
$filePath = $builds[$_GET['id']]['filename'];
header("Content-type: ".mime_content_type("builds/".$filePath));
header('Content-Description: File Transfer');
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download"); 
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=".$filePath);
header("Content-Transfer-Encoding: binary");
header("Content-Length", filesize("builds/".$filePath));
flush();
readfile("builds/".$filePath);
exit();
?>