<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}

if (($_GET["premium_acc"] == "on" && $_GET["pr_user"] && $_GET["pr_pass"]) || ($_GET["premium_acc"] == "on" && $premium_acc["share_online"]["user"] && $premium_acc["share_online"]["pass"]))
  {
	$post = Array();
	$post["user"] = ($_GET["pr_user"] ? $_GET["pr_user"] : $premium_acc["share_online"]["user"]);
	$post["pass"] = ($_GET["pr_pass"] ? $_GET["pr_pass"] : $premium_acc["share_online"]["pass"]);
	$post["act"] = "login";
	$post["location"] = "index.php";
	$post["login"] = "Log+me+in";
		
	$Url = parse_url("http://www.share-online.biz/login.php");
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, $post, 0, $_GET["proxy"],$pauth);
	is_page($page);
	//is_present($page, "Password can not be found", "Invalid username or password");
	is_present_tryagain($page, "Password can not be found", "Invalid username or password");
	
	if(preg_match_all('/Set-Cookie: *((king_passhash|king_uid|king_last_click)=[^0].*?);/i', $page, $cook))
	  {
		$cookie = 'king_logined=1;'.implode(';', $cook[1]);
		$Url = parse_url($LINK);
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, $cookie, 0, 0, $_GET["proxy"],$pauth);
		
		preg_match('%(http://.*getfile.*?)".*>Download%i', $page, $dlink);
		
		$Href = $dlink[1];
		$Url = parse_url($Href);
		$FileName = !$FileName ? basename($Url["path"]) : $FileName;
		insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&cookie=".urlencode($cookie)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
	  }
	else
	  {
		html_error("Cookie not found.", 0);		
	  }
  }
else
  {
	html_error("Set Your Premium Account in config.php.", 0);
  }
?>