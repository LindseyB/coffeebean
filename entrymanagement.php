<?php
	
	include_once 'db.php';
	include_once 'rss.php';
	include_once 'renderer.php';

	//add an entry
	function add_entry($title, $topic, $entry)
	{
		dbConnect();
		
		
		$entry = formatCode($entry);
		$entry = str_replace("\\", "\\\\", $entry);
		$quotes = array(";", '"', "'");
		$escquotes = array("\;", "\"", "\'");
		
		$title = str_replace($quotes, $escquotes, $title);
		$title = trim(html_entity_decode($title));
		
		$entry = str_replace($quotes, $escquotes, $entry);
		$entry = trim(html_entity_decode($entry));
		$entry = nl2br2($entry);
		$id =  time();
		
		$sql = "INSERT INTO `bean_entries` (id, title, topic, entry) 
		VALUES ($id, '$title', '$topic', '$entry');";
		$result = mysql_query($sql);
		
		if (!$result) 
		{
			echo 'There was a problem adding the entry: ' . mysql_error();
			echo '<br />' . $sql;
		}
		else
		{
			build_rss();

			// twitter login info
			$username = 'lindseybieda';
			$password = 'REDACTED';
			$message  = 'Caffeine Powered Automaton:' . $title . ' http://rarlindseysmash.com/index.php?n=' . $id;
			$url = 'http://twitter.com/statuses/update.xml';
			
			// setup and execute the curl process
			$curl_handle = curl_init();
			curl_setopt($curl_handle, CURLOPT_URL, "$url");
			curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl_handle, CURLOPT_POST, 1);
			curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "status=$message");
			curl_setopt($curl_handle, CURLOPT_USERPWD, "$username:$password");
			$buffer = curl_exec($curl_handle);

			if(curl_error($curl_handle))
			{
				echo curl_error($curl_handle);
			}

			curl_close($curl_handle);
			
			if ($buffer) 
			{
				echo 'Success: twitter message posted.';
			} 
			
			echo '<br/>Success: new entry added.';
		}		
		
	}
	
	//edit an entry
	function edit_entry($id, $title, $entry)
	{
		dbConnect();
		
		$title = str_replace($quotes, $escquotes, $title);
		$title = trim(html_entity_decode($title));
		
		$entry = str_replace("\\", "\\\\", $entry);
		$quotes = array(";", '"', "'");
		$escquotes = array("\;", "\"", "\'");
		$entry = trim(html_entity_decode($entry));
		$entry = str_replace($quotes, $escquotes, $entry);
		
		$entry_fix = nl2br2($entry);
				
		$sql = "UPDATE `bean_entries` SET title = '$title', entry = '$entry_fix' WHERE id = $id;";
		$result = mysql_query($sql);
		
		if (!$result) 
		{
			echo 'There was a problem editing the entry.' . mysql_error();
			echo $sql;
		}
		else
		{
			build_rss();
			echo 'Success: the entry has been edited.';
		}	
		
	}
	
	//delete an entry
	function delete_entry($id)
	{
		dbConnect();
		
		$sql = "DELETE FROM `bean_entries` WHERE id = $id;";
		$result = mysql_query($sql);
		
		$sql_c = "DELETE FROM `bean_comments` WHERE entryid = $id;";
		$result_c = mysql_query($sql_c);
		
		if (!$result || !$result_c) 
		{
			echo 'There was a problem deleting the entry.';
		}
		else
		{
			build_rss();
			echo 'Success: the entry has been removed.';
		}
	}
	
	//delete a comment
	function delete_comment($cid)
	{
		dbConnect();
		
		$sql = "DELETE FROM `bean_comments` WHERE id = $cid;";
		$result = mysql_query($sql);

		if (!$result) 
		{
			echo 'There was a problem deleting the comment.';
		}
		else
		{
			echo 'Success: the comment has been removed.';
		}		
		
	}
	
	//submit a new author comment
	function submit_comment($comment, $entryid)
	{
			$poster = 'Lindsey';
			$email = 'hazardous[dot]narayan[at]gmail[dot]com';
			
			$comment = str_replace("\\", "\\\\", $comment);
			$quotes = array(";", '"', "'");
			$escquotes = array("\;", "\"", "\'");
			$comment = trim(html_entity_decode($comment));
			$comment = str_replace($quotes, $escquotes, $comment);
		
			$entry_fix = nl2br2($comment);
			
			$id = time();
			
			dbConnect();
			
			$sql = "INSERT INTO `bean_comments` (entryid, id, poster, email, comment) 
			VALUES ($entryid, $id, '$poster', '$email', '$comment');";
			$result = mysql_query($sql);
			
		if (!$result) 
		{
			echo 'There was a problem adding the comment.';
		}
		else
		{
			echo 'Success: the comment has been added.';
		}		

	}
	
	// format all of the code 
	function formatCode($string)
	{
		return preg_replace_callback('/\<code lang="(.*?)"\>(.*?)\<\/code\>/is', 'fixCode', $line);
		
	}
	
	// helper function for the above
	function getRendered($matches)
	{
		$type = strtolower($matches[1]);
		
		switch($type){
			case 'basic':	$lang_id = 1;
							break;
			case 'java':	$lang_id = 2;
							break;
			case 'html':	$lang_id = 3;
							break;
			case 'php':		$lang_id = 4;
							break;
			default:		$lang_id = 0;
							break;
		}

		return RenderToString($matches[2], $lang_id);
		//return str_replace(array("\t", " "), array("&nbsp;&nbsp;", "&nbsp;"), $text[0]);
	}
	
	// replace all newlines to br
	function nl2br2($string) 
	{
		return str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
	}
	
	// replace all br with newlines
	function br2nl2($string)
	{
		return str_replace(array("<br>","<br/>", "<br />"), "\r\n", $string);
	}
	
?>
