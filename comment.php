 <?php include_once 'db.php';
	         echo"<script>
                 var RecaptchaOptions = {
                     theme : 'white'
                 };
                 </script>";

 		dbconnect();
		
		if(isset($_POST['submit']))
		{
			require_once('recaptchalib.php');
			$privatekey = "	6LeFMgIAAAAAALmCLw9y5qghe6fT2T2NZ8SKEV0i";
			$resp = recaptcha_check_answer ($privatekey,
											$_SERVER["REMOTE_ADDR"],
											$_POST["recaptcha_challenge_field"],
											$_POST["recaptcha_response_field"]);
   
			if (!$resp->is_valid) {
			  echo "The reCAPTCHA wasn't entered correctly. Please try again.";
			}
			else
			{
				
				
				//insert the comment
				dbconnect();
   				
				$poster = $_POST['name'];
				$poster = trim(html_entity_decode($poster));
				
				if(strtolower($poster) == "narayan" || strtolower($poster) == "lindsey")
				{
					$errorflag = 1;
					echo "Error: Please use a different name.";
				}

				if(strtolower($poster) == "naru")
				{
					$errorflag = 1;
					echo "Error: Spam comments from Naru have been disallowed.";
				}
				
				$comment = strip_tags($_POST['comment']);
				$comment = trim(html_entity_decode($comment));
				$comment = str_replace("\\", "\\\\", $comment);
				$quotes = array(";", '"', "'");
				$escquotes = array("\;", "\"", "\'");
				
				$comment = str_replace($quotes, $escquotes, $comment);

				if($comment == "" || $poster == "")
				{
					$errorflag = 1;
					echo "Error: one or more of the required fields below have been left blank.";
				}
				
				if(!isset($errorflag))
				{	
					$comment = htmlspecialchars($comment);

					//de-spamify e-mail addresses   				
					$chars = array("@", ".");
					$words = array("[at]", "[dot]");
					$email = str_replace($chars, $words, $_POST['email']);
   
					$id = time();
					$entryid = $_GET['n'];
   
					$sql = "INSERT INTO `bean_comments` (entryid, id, poster, email, comment) 
					VALUES ($entryid, $id, '".mysql_real_escape_string($poster)."', '".mysql_real_escape_string($email)."', '".mysql_real_escape_string($comment)."');";
					$result = mysql_query($sql);
				}
			}
		}
		
		//show all comments so far
		
		$sql = "SELECT `id` FROM `bean_comments` WHERE `entryid` = " . mysql_real_escape_string($_GET['n']) . " ORDER BY `id` ASC";

		$qry = mysql_query($sql);
		$i = 0;
		
		// get all IDs and store them in an array
		while($row = mysql_fetch_array($qry))
		{
			$carr[$i] = $row[0];
			$i++;
		}
		
		//if comments exist show them
		if(count($carr)>0)
		{
			for($i = 0; $i<count($carr); $i++)
				displaycomment($carr[$i]);
		}
 
 		
		if(isset($error_flag))
		{
		?>

		<form action="<?php echo $PHP_SELF; ?>" method="post" class="comment-form">
			<label for="name">name: </label> <input type="text" name="name" value="<?php echo $poster; ?>" size="30"/><br/>
			<label for="email">email: </label> <input type="text" name="email" value="<?php echo $_POST['email']; ?>" size="30"/><br/>
			<textarea name="comment" rows="8" cols="65"><?php echo $comment; ?></textarea><br/>


		 <?php
                	//display captcha

                	require_once('recaptchalib.php');
                	$publickey = "6Lex-QAAAAAAANQEeCijYeXWmZwKhgIAqWu8q_2M";
                	echo recaptcha_get_html($publickey);
 		?>
			<input type="submit" value="Submit" name="submit"/> <input type="reset" value="Reset" />
		</form>

		
		<?php	
		}
		else
		{
 		//show new comment form
 ?>
 
<form action="<?php echo $PHP_SELF; ?>" method="post" class="comment-form">
	<label for="name">name: </label> <input type="text" name="name" value="" size="30"/><br/>
	<label for="email">email: </label> <input type="text" name="email" value="" size="30"/><br/>
	<textarea name="comment" rows="8" cols="65"></textarea><br/>
 <?php 
 		//display captcha
 		
 		require_once('recaptchalib.php');
		$publickey = "6LeFMgIAAAAAACoyptybiG4EJO-AvhUu2dzbZsOo"; 
		echo recaptcha_get_html($publickey);
 ?>
	<input type="submit" value="Submit" name="submit"/> <input type="reset" value="Reset" />
</form>

<?php
		}

	
	function displaycomment($id)
	{
		if(getName($id) == 'Lindsey')
		{
			// admin comment
			echo '<div class="comment admin-comment"><b>' . getName($id) . '</b><br />' . getComment($id) . '</div>';
		}
		else
		{
			echo '<div class="comment"><b>' . getName($id) . '</b><br/>' . getComment($id) . '</div>'; 
		}
	}
	
	function getName($id)
	{
			$sql = "SELECT `poster` FROM `bean_comments` WHERE `id` = " . mysql_real_escape_string($id);
			$qry = mysql_query($sql);
			$name = mysql_fetch_array($qry);

			return $name[0];
	}
	
	
	function getComment($id)
	{
			$sql = "SELECT `comment` FROM `bean_comments` WHERE `id` = " . mysql_real_escape_string($id);
			$qry = mysql_query($sql);
			$comment = mysql_fetch_array($qry);

			return nl2br2($comment[0]);	
	}

?>
