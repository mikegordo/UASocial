<?php
$a = session_id();
if(empty($a)) session_start();
include ("top/header2.php");
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}

	
 	$uid = $_SESSION['uid'];	//my info
	$name = $_SESSION['name'];
	$myname = $name;
	$picture = $_SESSION['picture'];
	
	$to = $_GET['id'];				// display other user's pictures
	$upload = $_GET['upload'];		// uploading new picture
	if (!$upload) {$upload = '0';}
	$upload_picture = $_POST['upload_picture'];		// uploading new picture
	if ($upload_picture=='yes'){$upload = '2';}
	
	$comment = $_GET['comment'];
	if (!$comment) $comment = $_POST['comment'];
	$like = $_GET['like'];
	?>
    

<?php
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	
	include_once("utils/online.php");		// UPDATE ONLINE STATUS

	//if we updating some picture
	$pic_id = $_POST['pic_id'];
	$cap = addslashes(strip_tags($_POST['cap']));
	$delete	= $_POST['delete'];
	$cmt = addslashes(strip_tags($_POST['cmt']));	

	//deleting OR editing the picture
	if ($pic_id):
	$profile = $_POST['profile'];
		if ($delete == '1') {
			$result = mysql_query("SELECT `picture` FROM `picture` WHERE `uid` = '$uid' AND `pic_id` = '$pic_id' ", $mysql_link);
			if (!$result) {
				echo "<br /><p class=\"error\">Cannot delete the picture at this time.</p>";
			 } else {
				$row = mysql_fetch_array($result); 
				$myFile[0] = getcwd()."/".$row['picture']."_sml.jpg";
				unlink($myFile[0]);
				$myFile[1] = getcwd()."/".$row['picture'].".jpg";
				unlink($myFile[1]);
			 }
			$result = mysql_query("DELETE FROM picture WHERE uid = '$uid' AND pic_id = '$pic_id' ", $mysql_link);
			if (!$result) {
				echo "<br /><p class=\"error\">Cannot delete the picture at this time.</p>";
			 }
		} elseif(!$profile)  {
			$result = mysql_query("UPDATE picture SET text = '$cap' WHERE uid = '$uid' AND pic_id = '$pic_id' ", $mysql_link) or die(mysql_error());
			if (!$result) {
				echo "<br /><p class=\"error\">Cannot update the picture at this time.</p>";
			 }
		}
		if ($profile) {
			
			// MAKE PROFILE PICTURE
			
			$result = mysql_query("SELECT picture FROM picture WHERE uid = '$uid' AND pic_id = '$pic_id' ", $mysql_link);
			if (!$result) {
				echo "<br /><p class=\"error\">Cannot change profile picture at this time.</p>";
			 } else {
				$row = mysql_fetch_array($result); 
				//$myFile  = getcwd()."/".$row['picture'].".jpg";			 

			$picture = substr($row['picture'],9);
			$_SESSION['picture'] = $picture;
			
			$result = mysql_query("UPDATE profile SET picture = '$picture' WHERE uid = '$uid'", $mysql_link) or die(mysql_error());
			
			$src = getcwd()."/".$row['picture'].".jpg";
			$image = imagecreatefromjpeg($src);
			list($width,$height) = getimagesize($src);
						$new_size_x = 60;
						$new_size_y = 60;
						$image_p = imagecreatetruecolor($new_size_x,$new_size_y);		
						if ($width >= $height) { 		//horizontal
							$src_top = 0;
							$koef = $height/$new_size_y;
							$src_left = ($width-$new_size_x*$koef)/2;
							$src_h = $height;
							$src_w = $src_h*($new_size_x/$new_size_y);		
						}
						if ($width < $height) { 		//vertical
							$src_left = 0;
							$koef = $width/$new_size_x;
							$src_top = ($height-$new_size_y*$koef)/2;
							$src_w = $width;
							$src_h = $src_w*($new_size_y/$new_size_x);		
						}
						imagecopyresampled($image_p,$image,0,0,$src_left,$src_top,$new_size_x,$new_size_y,$src_w,$src_h);
						imagejpeg($image_p,$row['picture']."60.jpg");
						imagedestroy($image_p);
						$new_size_x = 30;
						$new_size_y = 30;
						$image_p = imagecreatetruecolor($new_size_x,$new_size_y);		
						if ($width >= $height) { 	//horizontal
							$src_top = 0;
							$koef = $height/$new_size_y;
							$src_left = ($width-$new_size_x*$koef)/2;
							$src_h = $height;
							$src_w = $src_h*($new_size_x/$new_size_y);		
						}
						if ($width < $height) {		//vertical
							$src_left = 0;
							$koef = $width/$new_size_x;
							$src_top = ($height-$new_size_y*$koef)/2;
							$src_w = $width;
							$src_h = $src_w*($new_size_y/$new_size_x);		
						}
						imagecopyresampled($image_p,$image,0,0,$src_left,$src_top,$new_size_x,$new_size_y,$src_w,$src_h);
						imagejpeg($image_p,$row['picture']."30.jpg");
						imagedestroy($image_p);
						$new_size_x = 170;
						$new_size_y = 220;
						$image_p = imagecreatetruecolor($new_size_x,$new_size_y);		
						if ($width >= $height) { 	//horizontal
							$src_top = 0;
							$koef = $height/$new_size_y;
							$src_left = ($width-$new_size_x*$koef)/2;
							$src_h = $height;
							$src_w = $src_h*($new_size_x/$new_size_y);		
						}
						if ($width < $height) {		//vertical
							$src_left = 0;
							$koef = $width/$new_size_x;
							$src_top = ($height-$new_size_y*$koef)/2;
							$src_w = $width;
							$src_h = $src_w*($new_size_y/$new_size_x);		
						}
						imagecopyresampled($image_p,$image,0,0,$src_left,$src_top,$new_size_x,$new_size_y,$src_w,$src_h);
						imagejpeg($image_p,$row['picture']."170.jpg");
						imagedestroy($image_p);
						imagedestroy($image);			
			
			echo "<form method=\"post\" name=\"prof_pic\" action=\"settings.php?update=3\">";
			echo "</form>";	
			
			echo "<script type=\"text/javascript\">";
			echo "function submitform(){\r\n";
			echo "document.forms[\"prof_pic\"].submit();\r\n";
			echo "}\r\n submitform();\r\n</script>";
			 }
		}
	endif; ////deleted or edited


	
	?>
        
    <div id="left" style="min-height:750px;">
        <!-- empty field -->
        
        <?php
			if ((!$to || $to == $uid) && !$comment) :	//my profile
				include("utils/leftmenu.php");
			else :
			
				include_once("utils/checkonline.php");	// GET ONLINE STATUS
				$query = "SELECT name, picture FROM profile WHERE uid='$to'";
				$result = mysql_query($query, $mysql_link);
				$row = mysql_fetch_array($result);
				if ( (!$result) || (!mysql_num_rows($result)) ) {
					// no  such user
					echo '<META HTTP-EQUIV="Refresh" Content="0; URL=pictures.php">';
					exit;		
				 }
				
				$uname = $row['name'];
				$upicture = $row['picture'];
				$css = $_SESSION['css'];
			?>
            <script type="text/javascript">
			document.title = "<?php echo $uname; ?>";
			</script>
            	<h2 class="name"><?php echo $uname; ?><?php if($to && $to != $uid) echo $online; ?></h2>
                  <?php 
			//See if added to contacts already
			$get_contact = mysql_query("SELECT * FROM friends WHERE (user1='$uid' AND user2='$to' AND type='0')", $mysql_link);	//is he in your contacts
			$row3 = mysql_fetch_row($get_contact); 
			$contact = $row3[0];
			//They are Contacts
			if ($contact > 0) {
				echo '<p class="incontact">In your contacts</p>';
			}else{ //Display add Contacts 
				echo '<a href="javascript: addToContacts()" id = "contact_link">Add to Contacts</a>';
				echo '<p class="incontact" id="incontact" style="display:none;">In your contacts</p>';
			}
				?>
                <a href="profile.php?id=<?php echo $to; ?>">
				<img src="<?php echo "pictures/".$upicture."170.jpg"; ?>" alt="My Face" width="170" height="220" class="profile_picture" />
                </a>

                <ul class="leftmenu">
                    <li class="one"><a href="profile.php?id=<?php echo $to; ?>"><img src="images/<?php if ($css=='2')echo "alt/"; ?>profile_icon.png" class="icon">Profile</a></li>
                    <li class="three"><a href="messages.php?to=<?php echo $to; ?>"><img src="images/<?php if ($css=='2')echo "alt/"; ?>message_icon.png" class="icon">Send message</a></li>
                    <li class="two"><a href="smile.php?to=<?php echo $to; ?>"><img src="images/<?php if ($css=='2')echo "alt/"; ?>smile_icon.png" class="icon">Send smile</a></li>
                    <li class="four"><a href="pictures.php?id=<?php echo $to; ?>"><img src="images/<?php if ($css=='2')echo "alt/"; ?>picture_icon.png" class="icon">Pictures</a></li>
                </ul>

		<?php
			endif;
		?>
        
    
    </div> <!-- left -->
    
    <div id="right" style="min-height:750px;">
	<div id="profile">
    <h2>Pictures</h2>


<?php
	if (!$to && $upload=='0') :		//if i'm looking at my own pictures

		echo "<span class=\"upper_bar\"><a href=\"pictures.php?upload=1\">Upload new picture</a></span><br />\r\n\r\n";

		$query = "SELECT * FROM picture WHERE uid='$uid' ORDER BY pic_id ASC ";
		$result = mysql_query($query, $mysql_link);
		if ( (!$result) || (!mysql_num_rows($result)) ) {
			//cant find pictures
			 mysql_close($mysql_link);
			 echo "<br /><p class=\"error\">Your don't have any pictures in your album.</p>";
		 } else {
			 // if I have pictures
			 
			while($row = mysql_fetch_array($result)) {				//go through the dataset
				echo "<div class=\"pic\">\r\n";
				echo "<a href=\"pictures.php?id=".$uid."&display=".$row['pic_id']."\">";
				echo "<img src=\"".$row['picture']."_sml.jpg\" border=\"1px\" align=\"left\">";
				echo "</a>\r\n";
				echo "<form method=\"post\" action=\"pictures.php\">";
				echo "<input type=\"hidden\" value=\"".$row['pic_id']."\" name=\"pic_id\"/>";
        		echo "<input type=\"text\" class=\"tpic\" name=\"cap\" maxlength=\"255\" style=\"width:300px;\" value=\"".$row['text']."\" /> <br/>";
				echo "<input type=\"checkbox\" class=\"styled3\" value=\"1\" name=\"delete\"/> Delete &nbsp;&nbsp;&nbsp;";
				echo "<input type=\"checkbox\" class=\"styled3\" value=\"1\" name=\"profile\"/> Make profile picture <br />";
				echo "<button type=\"submit\" class=\"bpicture\" value=\"Update\" />Update</button>";
				echo "</form>";
				echo "</div>\r\n";
				
			}// WHILE LOOP
			mysql_close($mysql_link);
		 }
		

 	elseif ( strlen($to)>0 && $upload=='0') :		//if i'm looking at somebody's pictures
	
		if ($to == $uid)
			echo "<span class=\"upper_bar\"><a href=\"pictures.php?upload=1\">Upload new picture</a><a href=\"pictures.php\">My pictures</a><a href=\"myprofile.php\">My profile</a></span><br />\r\n\r\n";
	
		$query = "SELECT * FROM picture WHERE uid='$to' ORDER BY pic_id ASC  ";
		$result = mysql_query($query, $mysql_link);
		if ( (!$result) || (!mysql_num_rows($result)) ) {
			 mysql_close($mysql_link);
			 echo "<p class=\"error\">This person doesn't have any pictures in her album.</p>";
		 } else {
			 // if he has pictures
			$display = $_GET['display'];
			$num = 1;
			echo "<div id=\"beautiful_pic2\">\r\n";
			while($row = mysql_fetch_array($result)) {				//go through the dataset
				if (!$display) {$display = $row['pic_id'];}
				if (is_numeric($display) && $display == $row['pic_id']) {
					$pic_id = $display;
					$catchnext=1;
					$display = $row['picture'];
					$cap = $row['text'];
					if ($num == 1) $dt = 1;
					$dn = $num;
					echo "<a class=\"act\" href=\"pictures.php?id=".$to."&display=".$row['pic_id']."\">".$num++."</a>";
				} else {
				echo "<a href=\"pictures.php?id=".$to."&display=".$row['pic_id']."\">".$num++."</a>";
					if (is_numeric($display)) $lastimg = $row['pic_id'];
					if ($catchnext) {$nextimg = $row['pic_id']; $catchnext=0;}
				}
			}// WHILE LOOP
			echo "<span style=\"clear:both;\">&nbsp;</span>";
			echo "</div>\r\n";
			if (($num - 1) == $dn) $dt += 2;	//if dt==1 no prev picture, if dt==2 no next, if dt == 3 no next, no prev
			echo "<div id=\"big_picture\">";
			list($width,$height) = getimagesize($display.".jpg");	
			?>
            
            <script type="text/javascript">
			function next() {
			window.location = "<?php echo "pictures.php?id=".$to."&display=".$nextimg;?>";
			}
			function prev() {
			window.location = "<?php echo "pictures.php?id=".$to."&display=".$lastimg;?>";
			}
			</script>
            
            <center>
			<table width="<?php echo $width;?>px" height="<?php echo $height;?>px" cellspacing="0" cellpadding="0" style="margin-top:10px;background-image: url('<?php echo $display.".jpg"?>');" >
			<tr><td width="50px" <?php if($dt==0 || $dt==2) echo "onMouseOver=\"document.getElementById('leftimg').src='images/left.png';\"";?> onMouseOut="document.getElementById('leftimg').src='images/transparent.png';" onclick="javascipt:prev();" align="center" valign="middle">
            <img src="images/transparent.png" width="50" height="50" border="0" id="leftimg" />
			</td><td><a href="javascript:next();"><img src="images/transparent.png" width="<?php if(($width-100)>0) echo $width-100; else echo "0";?>" height="<?php echo $height;?>" border="0" id="leftimg" /></a></td>
            <td width="50px"  <?php if($dt==0 || $dt==1) echo "onMouseOver=\"document.getElementById('rightimg').src='images/right.png';\"";?> onMouseOut="document.getElementById('rightimg').src='images/transparent.png';" onclick="javascipt:next();" align="center" valign="middle">
            <img src="images/transparent.png" width="50" height="50" border="0" id="rightimg" />
			</td></tr>
			</table>
            </center>
			<?php
			echo "<br /><span>".$cap."</span></div>\r\n";			
			
			// COMMENTS THING
			
			?>
            
            <script type="text/javascript">
				function displayIt() {
					document.getElementById("comment").style.display = "inline";					
				}		
			</script>
            
           
            <a class="fb" href="javascript:displayIt();"><img src="images/leavecomment.png" class="icon">Leave a comment&nbsp;</a> <a class="fb" href="pictures.php?like=<?php echo $uid;?>&id=<?php echo $to;?>&display=<?php echo "$pic_id"; ?>"><img src="images/like.png" class="icon">Like&nbsp;</a><br />
            <div id="comment" style="display:none;">
            	<form method="post" id="comment_form" action="pictures.php?id=<?php echo "$to"; ?>&display=<?php echo "$pic_id"; ?>">
                <input type="hidden" name="comment" value="say" />
            	<textarea class="tpic" name="cmt" id="cmt" maxlength="255" style="width:460px;" rows="3"></textarea>
                <button class="bpicture" type="submit" style="position:relative; top:-13px; margin-left:10px;">Say</button>
            </div>
			
            <?php

			if ($like) :
				//check if i liked it before
					$ismypicture = mysql_query("SELECT COUNT(cid) FROM pic_com WHERE uid='$uid' AND type='2' AND pic_id='$pic_id' ", $mysql_link) or die(mysql_error());
					$row = mysql_fetch_row($ismypicture); 
					$ismypicture = $row[0];
					if ($ismypicture==0) {// LIKE	
						$result = mysql_query("INSERT INTO `pic_com`(`pic_id`, `uid`, `type`) VALUES ('$pic_id','$uid','2') ", $mysql_link) or die(mysql_error());
					} else { //UNLIKE						
						 $result = mysql_query("DELETE FROM `pic_com` WHERE type='2' AND uid='$uid' AND pic_id='$pic_id' ", $mysql_link) or die(mysql_error());
						}
			endif; //like

			
			if ($comment == "say") :
				$today = date("Y-m-d H:m:s");
				$result = mysql_query("INSERT INTO `pic_com`(`pic_id`, `uid`, `type`, `comment`, `datetime`) VALUES ('$pic_id','$uid','1','$cmt','$today') ", $mysql_link) or die(mysql_error());
				
				if ( strlen($to)>0 && $to != $uid) { // not my own picture
				
					//send notification email
					$today = date("Y-m-d H:i:s");
					$subject = "Comment notification";
					$text = "Authomatic notification:<br /><br />$myname has just commented on your photo.<br />";
					$text .= "To see the comment, please follow the ";
					$text .= "<a href=\"pictures.php?id=$to&display=$pic_id\">link</a><br /><br />";
					$query =  mysql_query("INSERT INTO message (mid, from_uid, to_uid, didread, subject, text, type, disp_s, datetime) VALUES  ('0','$uid','$to','0','$subject','$text', '1', '0', '$today')", $mysql_link) or die(mysql_error());				
				}
			endif; //say

			if ($comment == "erase" && (!$to || $to == $uid)) :
				$cid = $_GET['cid'];
				
				//check if i have rights to delete this comment
					$ismypicture = mysql_query("SELECT COUNT(pic_id) FROM picture WHERE uid='$uid' AND pic_id='$pic_id' ", $mysql_link) or die(mysql_error());
					$row = mysql_fetch_row($ismypicture); 
					$ismypicture = $row[0];
					if ($ismypicture>0)				
						$result = mysql_query("DELETE FROM `pic_com` WHERE cid = '$cid' ", $mysql_link) or die(mysql_error());
			endif; //erase

				// display like
				$query = mysql_query("SELECT profile.name, profile.uid, pic_com.* FROM profile, pic_com WHERE pic_com.pic_id='$pic_id' AND profile.uid = pic_com.uid AND pic_com.type = '2'", $mysql_link) or die(mysql_error());
				echo "<div id=\"like\">";
				$i = 0;
				while ($row = mysql_fetch_array($query)) {
					if ($i>0)echo ", ";
					echo "<a class=\"like\" href=\"profile.php?id=".$row['uid']."\">".$row['name']."</a>";
					$i++;
				}
				if($i>0)echo " likes this.";
				echo "</div>\r\n";
			
				// display comments
				$query = mysql_query("SELECT profile.name, profile.uid, profile.picture, pic_com.* FROM profile, pic_com WHERE pic_com.pic_id='$pic_id' AND profile.uid = pic_com.uid AND pic_com.type = '1' ORDER BY pic_com.datetime ASC ", $mysql_link) or die(mysql_error());
				echo "<br />";
				while ($row = mysql_fetch_array($query)) {
					$today = date('M, j h:i A', strtotime($row['datetime']));	
					echo "<div class=\"comment\">";
					echo "<img src=\"pictures/".$row['picture']."30.jpg\" align=\"left\"/>";
					echo "<a class=\"fb\" href=\"profile.php?id=".$row['uid']."\">".$row['name']."</a>";
					echo "<span class=\"date\">".$today.(($uid==$to)?" <a href=\"pictures.php?id=$to&display=$pic_id&comment=erase&cid=".$row['cid']."\">remove</a>":"")."</span>";
					echo "<br /><span class=\"uname\">".nl2br($row['comment'])."</span><br>";
					echo "</div>\r\n";
					echo "<br />";
				}

			mysql_close($mysql_link);			
		 }
	
	
	
	endif;
	
	if ($upload == '1' || $upload == '2'):			//if going to upload
		
	?>
    <div id="beautiful_pic">
		<form method="POST" enctype="multipart/form-data" name="image_upload_form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<label class="long"><strong>Please choose the picture</strong><br /><small>(Maximum size: 4Mb)</small></label><br /><br />
			<input type="hidden" name="upload_picture" value="yes"/>
			<input type="hidden" name="MAX_FILE_SIZE" value="4000000">
			<input type="file" name="image_file" style="width:400px;"><br /><br />
        <p>By uploading your picture, you expressly agree to the <a href="terms.php">Terms and Conditions</a>.<br />
        Pictures violating Terms and Conditions will be expelled from the site. Corresponding user account will be blocked.</p>      
			<button class="bpicture" type="submit" value="Upload picture" name="action">Upload picture</button><br />
		</form> 
        
     </div>
	<?php		
		
	endif;

	if ($upload == '2'):			//if uploading the picture
		unset($imagename);
		if(!isset($_FILES) && isset($HTTP_POST_FILES))
			$_FILES = $HTTP_POST_FILES;
		if(!isset($_FILES['image_file']))
			$error["image_file"] = "An image was not found.";
		$imagename = basename($_FILES['image_file']['name']);
		
		if (end(explode(".", strtolower($_FILES['image_file']['name']))) != 'jpg')
			$error["ext"] = "Invalid file type. Only jpeg files allowed.";		
		//echo "a. ".$imagename."<br>";													//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!111
		if(empty($imagename))
			$error["imagename"] = "The name of the image was not found.";
		if(empty($error))
			{
				$newimage = getcwd()."/pictures/".$uid."/";
				$thisdir = $newimage; 
				if (!file_exists($newimage) || !is_dir($newimage)) {					
					if(!mkdir($thisdir, 0777))
						$error["directory"] = "Unable to create user directory.";
				}
				$gen_name = genRandomString();				
				$newimage = $thisdir.$gen_name.".jpg";
				//echo "b. ".$newimage."<br>";												//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!111
				//echo "c. ".$_FILES['image_file']['tmp_name']."<br>";						//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!111
				$result = @move_uploaded_file($_FILES['image_file']['tmp_name'], $newimage);
				if(empty($result))
					$error["result"] = "There was an error moving the uploaded file.";
			}
		   if (!empty($error)) {
			  echo "<p class=\"error\">".$error["image_file"].$error["imagename"].$error["directory"].$error["result"].$error["ext"]."</p>";
			  echo "</div></div>";
			  include ("footer.php");
			  exit;
		   }

		$src =	$newimage;
		if (file_exists($src)){
			ini_set('memory_limit','64M');
			$image = imagecreatefromjpeg($src);
			if(!$image)
				echo "<p class=\"error\">File is too big, or unknown error.</p>";
			list($width,$height) = getimagesize($src);	
					
			//create the large picture
			$new_size_x = 560;
			$new_size_y = 700;
			if($width>$new_size_x || $height>$new_size_y) {	// do we actually need to make the file smaller
				$koef = ($width/$height);
				if ($width >= $height) { 		//horizontal
					$new_size_x = 560;					
					$new_size_y = $new_size_x / $koef;		
				} else {
					//vertical
					$new_size_y = 700;
					$new_size_x = $new_size_y * $koef;	
				}
				$image_p = imagecreatetruecolor($new_size_x,$new_size_y);
				$white = imagecolorallocate($image_p, 255, 255, 255);
				imagefilledrectangle($image_p, 0, 0, $new_size_x, $new_size_y, $white);			
				imagecopyresampled($image_p,$image,0,0,0,0,$new_size_x,$new_size_y,$width,$height);
				imagejpeg($image_p,$src);	// save file
				imagedestroy($image_p);	
			}		
		}						
			// let user cut the small picture
			// 140 x 100
			?>
            
   		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.Jcrop.js"></script>
		<link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />
		
		<script language="Javascript">
			$(function(){
				$('#cropbox').Jcrop({
					aspectRatio: 1.4,
					bgColor:     'black',
					setSelect:   [ 0, 0, 550, 550 ],
					onSelect: updateCoords
				});
			});
			function updateCoords(c)
			{
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#w').val(c.w);
				$('#h').val(c.h);
			};
			function checkCoords()
			{
				if (parseInt($('#w').val())) return true;
				alert('Please select a crop region then press save.');
				return false;
			};
		</script>
		
        <p>Please choose a region to make a thumbnail.</p>
        <center>
		<img class="prof" src="<?php echo $urladdress."/pictures/".$uid."/".$gen_name.".jpg?".time();?>" id="cropbox" />
        </center>
            <form method="post" action="pictures.php?upload=4"  onSubmit="return checkCoords();">
            <input type="hidden" name="src" value="<?php echo $gen_name;?>" />
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
            <button class="bpicture" value="Save">Save</button>
            </form>
            <br />
			<br />

            <?php	
			endif;
		
		if ($upload=='4') { //user finished cutting the picture	
				
				$targ_w = 140;
				$targ_h = 100;
				$jpeg_quality = 90;
			
				$newimage = getcwd()."/pictures/".$uid."/";
				$gen_name = $_POST['src'];
				$src = $newimage.$gen_name.".jpg";
				$img_r = imagecreatefromjpeg($src);
				$dst_r = imagecreatetruecolor($targ_w, $targ_h );
			
				imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
				$targ_w,$targ_h,$_POST['w'],$_POST['h']);
			
				//header('Content-type: image/jpeg');
				imagejpeg($dst_r,$newimage.$gen_name."_sml.jpg",$jpeg_quality);
				
		$query="INSERT INTO picture (`pic_id`, `uid`, `picture`, `text`, `type`)  VALUES ('0','$uid','pictures/$uid/$gen_name','', '0')";
 		$result = mysql_query($query, $mysql_link);
		mysql_close($mysql_link);
			if (!$result) {
				echo "<p class=\"error\">Unable to save picture! <a href=\"pictures.php\">Return</a></p>";			
			} else {
				echo "<p class=\"error\">Picture was uploaded successfully. <a href=\"pictures.php\">Return</a></p>";
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=pictures.php">';
			}
			
		}
 ?>
 
</div> <!-- profile -->
</div> <!-- right -->

<script type="text/javascript">
function addToContacts(){
	$.post("utils/addtocontacts.php", {myid: <?php echo $uid;?>, id: <?php echo $to;?>});
	 $('#contact_link').fadeOut('slow', function() {
        document.getElementById('contact_link').style.display = "none";
		document.getElementById('incontact').style.display = "inline";
		$('#incontact').fadeIn('slow');
      });
}
</script>
<?php
include ("footer.php");
?>
