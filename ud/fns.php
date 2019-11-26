<?php
class UserPerms
{
	const Perms = array(
		'visit' => [0,1,2],
		'dl_builds' => [0,2],
		'publish_builds' => [2],
		'add_news' => [2],
		'manage_users' => [],
		'shop' => [0,1,2],
		'manage_shop' => [2],
		'elvex_manage_social' => [2],
		'elvex_users_social' => [1,2],
		'report_bug' => [0],
		'manage_reports' => [2]
	);
	const hiddenNames = array(
		'root' => 'ok boomer');
}
$_string_perms = array(
	-2 => 'Guest',
	-1 => 'God of Dashboard',
	0 => 'Tester',
	1 => 'Moderator',
	2 => 'Developer',
);

function GET_NAME()
{
	if(isset(UserPerms::hiddenNames[$_SESSION['login']]))
	{
		return UserPerms::hiddenNames[$_SESSION['login']];
	}
	else
	{
		return $_SESSION['login'];
	}
}
function sqlite_open($location,$mode) 
{ 
    $handle = new SQLite3($location); 
    return $handle; 
} 
function gnes($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function sqlite_query($dbhandle,$query) 
{ 
    $array['dbhandle'] = $dbhandle; 
    $array['query'] = $query; 
    $result = $dbhandle->query($query); 
    return $result; 
} 
function sqlite_fetch_array(&$result,$type) 
{ 
    #Get Columns 
    $i = 0; 
    while ($result->columnName($i)) 
    { 
        $columns[ ] = $result->columnName($i); 
        $i++; 
    } 
    
    $resx = $result->fetchArray(SQLITE3_ASSOC); 
    return $resx; 
}
function estr($str)
{
	return hash_hmac("sha256", $str, "r0rkkr0kr0ek0ke0w2220kaaaaaa");
}

function isUser($str)
{
	$db = new SQLite3("users.db");
	$a = $db->prepare("SELECT * FROM users WHERE username =:un");
	$a->bindValue(':un', $str, SQLITE3_TEXT);
	$result = $a->execute();
	if(gettype($result->fetchArray()) == "boolean")
	{
		return false;
	}
	else{ return true; }
}
function isPswd($l, $p)
{
	if(!isUser($l)){ return false; }
	$db = new SQLite3("users.db");
	$a = $db->prepare("SELECT * FROM users WHERE username =:un");
	$a->bindValue(':un', $l, SQLITE3_TEXT);
	$result = $a->execute()->fetchArray();
	if($result['pswd'] != $p)
	{
		return false;
	}
	else { return true; }
}
function GetAllUsers()
{
	$db = new SQLite3("users.db");
	$result = $db->query("SELECT * FROM users");
	$row = array();
	$i = 0;
	while($res = $result->fetchArray(SQLITE3_ASSOC)){ 

             if(!isset($res['username'])) continue; 

              $row[$i]['username'] = $res['username']; 
              $row[$i]['tokens'] = $res['tokens']; 
              $row[$i]['perm'] = $res['permission']; 
              $i++; 

    } 

    return $row;
}

function GetUserParam($l, $par)
{
	if(!isUser($l)){ return false; }
	$db = new SQLite3("users.db");
	$a = $db->prepare("SELECT * FROM users WHERE username =:un");
	$a->bindValue(':un', $l, SQLITE3_TEXT);
	$result = $a->execute()->fetchArray();
	if(isset($result[$par]))
	{
		return $result[$par];
	}
	else
	{
		return "";
	}
}
function SetUserStrParam($l, $p, $n)
{
	if(!isUser($l)){ return false; }
	$db = new SQLite3("users.db");
	$a = $db->prepare("UPDATE users SET ".$p." = :val WHERE username =:un");
	$a->bindValue(':un', $l, SQLITE3_TEXT);
	$a->bindValue(':val', $n, SQLITE3_TEXT);
	$a->execute();
	return true;
}
function SetUserIntParam($l, $p, $n)
{
	if(!isUser($l)){ return false; }
	$db = new SQLite3("users.db");
	$a = $db->prepare("UPDATE users SET ".$p." = :val WHERE username =:un");
	$a->bindValue(':un', $l, SQLITE3_TEXT);
	$a->bindValue(':val', $n, SQLITE3_INTEGER);
	$a->execute();
	return true;
}
function UserHasPerm($u, $permis)
{
	$perms = UserPerms::Perms;
	if(intval(GetUserParam($u, "permission")) == -1){ return true; }
	if(!isset($perms[$permis])) { return false; }
	if(in_array(intval(GetUserParam($u, "permission")), $perms[$permis])) { return true; }
	else { return false; }
}
function genlnk($t, $u)
{
	return '<a href="'.$u.'">'.$t.'</a>';
}
function PermLink($p, $t, $ur)
{
	$u = $_SESSION['login'];
	if(UserHasPerm($u, $p))
	{
		return genlnk($t, $ur);
	}
	return;
}
function RedirectIfNoPerms($pm)
{
	if(!UserHasPerm($_SESSION['login'], $pm))
	{
		require('no_access.php');
		exit();
	}
}
?>