<?php
session_start();

if(!isset($_SESSION['uid'])) :

$email = $_COOKIE['eml'];
$pwd = $_COOKIE['pwd'];

include_once("utils/settings.php");
$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);
if (($email) AND ($pwd)) {
	
  // you should inspect these variables before passing off to mySQL
   $query = "SELECT active, uid, sex, email, password, name, picture, look_for, look_age_from, look_age_to, css, chat_color FROM profile ";
   $query .= "WHERE email='$email'";// AND password='$pwd'";
   $result = mysql_query($query, $mysql_link);
   unset($row, $query);
   $row = mysql_fetch_array($result);
   if(!$result) {
		mysql_close($mysql_link);
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../index.php?error=password">';	//user not found
	   	exit;
   }
	if($row['password'] == $pwd) {
			//update LAST LOGIN and LAST ONLINE
				$today = date("Y-m-d");
				$uid = $row['uid'];
				$today_ip = $_SERVER["REMOTE_ADDR"];
				
				$query = "UPDATE profile SET last_login = '$today', last_login_ip = '$today_ip' WHERE email='$email'";
				$result = mysql_query($query, $mysql_link);

				$time = time();
				$query = "UPDATE `online` SET `time` = '$time' WHERE uid='$uid'";
				$result = mysql_query($query, $mysql_link);
			unset($result, $query, $time);

			//account is active

			include_once("utils/functions.php");		
				
			if(!session_is_registered("uadnses")) session_register("uadnses");
		
			$_SESSION['uid'] = $uid;
			$_SESSION['name'] = $row['name'];
			$_SESSION['picture'] = $row['picture'];
			$_SESSION['sex'] = $row['sex'];
			$_SESSION['active'] = $row['active'];
			$_SESSION['looking_for'] = $row['look_for'];
			$_SESSION['looking_for_age_from'] = $row['look_age_from'];
			$_SESSION['looking_for_age_to'] = $row['look_age_to'];
			$_SESSION['chat_color'] = $row['chat_color'];
			
			$_SESSION['online'] = addminutes($time,1);
			$_SESSION['css'] = $row['css'];
			
			//$_SESSION['sql'] = $mysql_link;
			
			//$_SESSION['pwd']=$row['password'];
			mysql_close($mysql_link);
			unset($row);
			echo "<div style=\"display:none;\">";
			include (inner().'contacts.php');	// JUST TO FIND NUMBER OF CONTACTS
			echo "</div>";
		//	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=messages.php">';  
		//	exit;		
		} else {
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/utils/logout.php">';
		exit;
		}
}
endif;
?>