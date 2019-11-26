<?php
session_start();
require("fns.php");
if(!isset($_POST['method']))
{
	die('BAD');
}
switch ($_POST['method']) {
	case 'acc.login':
		if(!isset($_POST['login']) || !isset($_POST['pswd']))
		{
			die('BAD');
		}
		if(!isUser($_POST['login']))
		{
			die('BAD');
		}
		$db = new SQLite3("users.db");
		$a = $db->prepare("SELECT * FROM users WHERE username =:un");
		$a->bindValue(':un', $_POST['login'], SQLITE3_TEXT);
		$result = $a->execute();
		$result = $result->fetchArray();
		if($result['pswd'] != estr($_POST['pswd']))
		{
			die('BAD');
		}
		session_destroy();
		session_start();
		$_SESSION['login'] = $result['username'];
		$_SESSION['pswd'] = $result['pswd'];
		die('OK');
		break;
	case 'acc.resetpswd':
		if(!isset($_POST['login']) || !isset($_POST['pswd']) || !isset($_POST['newpswd']))
		{
			die('BAD');
		}
		if(!isUser($_POST['login'])){ die('BAD'); }
		if(estr($_POST['pswd']) != GetUserParam($_POST['login'], 'pswd')){ die('BAD'); }
		if(strlen($_POST['newpswd']) < 6 || contains(" ", $_POST['newpswd'])){ die('BAD'); }
		SetUserStrParam($_POST['login'], "pswd", estr($_POST['newpswd']));
		die('OK');
	case 'build.del':
		if(!UserHasPerm($_SESSION['login'], "publish_builds")){ die('FAILURE'); }
		if(!isset($_POST['bid'])) { die('FAILURE'); }
		$builds = json_decode(file_get_contents("builds.json"), true);
		if(!isset($builds[$_POST['bid']])){ die('FAILURE'); }
		if(!is_file("builds/".$builds[$_POST['bid']]['filename'])){ die('FAILURE'); }
		unlink("builds/".$builds[$_POST['bid']]['filename']);
		unset($builds[$_POST['bid']]);
		file_put_contents("builds.json", json_encode($builds));
		die('OK');
	case 'post.post':
		if(!UserHasPerm($_SESSION['login'], 'add_news')){ die('BAD'); }
		$posts = file_get_contents("../posts.json");
		$posts = json_decode($posts, true);
		array_push($posts, array("title" => $_POST['post_title'], "content" => $_POST['post_content'], "img" => $_POST['post_img'], 'author' => $_SESSION['login']));
		file_put_contents("../posts.json", json_encode($posts));
		die('OK');
	case "acc.get":
		if(!UserHasPerm($_SESSION['login'], 'manage_users')){ die('BAD'); }
		$p = array('username' => GetUserParam($_POST['login'], 'username'),'tokens' => GetUserParam($_POST['login'], 'tokens'),'rank' => GetUserParam($_POST['login'], "permission"));
		die(json_encode($p));
	case "acc.edit":
		if(!UserHasPerm($_SESSION['login'], 'manage_users')){ die('BAD'); }
		if($_POST['oldname'] == "root" || UserHasPerm($_POST['oldname'], "*")){ die("VIOLATION"); }
		if(!isUser($_POST["oldname"])){ die('BAD'); }
		SetUserStrParam($_POST['oldname'], "username", $_POST['login']);
		SetUserIntParam($_POST['login'], "tokens", $_POST['tokens']);
		SetUserIntParam($_POST['login'], "permission", intval($_POST['perm']));
		die('OK');
	case "acc.create":
		if(!UserHasPerm($_SESSION['login'], 'manage_users')){ die('BAD'); }
		if(isUser($_POST['login'])){ die('EXISTS'); }
		if(strlen($_POST['login']) < 6 && strtolower($_POST['login']) != "aum0b"){ die('LENGTH'); }
		$db = new SQLite3("users.db");
		$a = $db->prepare("INSERT INTO users VALUES (:un, :pswd, :perm, :tokens)");
		$a->bindValue(':un', $_POST['login'], SQLITE3_TEXT);
		$a->bindValue(':pswd', estr($_POST['pswd']), SQLITE3_TEXT);
		$a->bindValue(':perm', $_POST['perm'], SQLITE3_INTEGER);
		$a->bindValue(':tokens', $_POST['tokens'], SQLITE3_INTEGER);
		$a->execute();
		die('OK');
	case "acc.delete":
		if(!UserHasPerm($_SESSION['login'], 'manage_users')){ die('BAD'); }
		if(!isUser($_POST['login'])){ die('BAD'); }
		if(UserHasPerm($_POST['login'], "*")){ die('BAD'); }
		$db = new SQLite3("users.db");
		$a = $db->prepare("DELETE FROM users WHERE username = :un");
		$a->bindValue(':un', $_POST['login'], SQLITE3_TEXT);
		$a->execute();
		die('OK');
	default:
		break;
}
function contains($needle, $haystack)
{
    return strpos($haystack, $needle) !== false;
}
die('BAD');
?>