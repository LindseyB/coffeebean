<?php
	
	include_once 'db.php';
	include_once 'rss.php';
	include_once 'renderer.php';

	//add an entry
	function add_entry($title, $topic, $entry)
	{
		dbConnect();
		
		$entry = str_replace("\\", "\\\\", $entry);
		$quotes = array(";", '"', "'");
		$escquotes = array("\;", "\"", "\'");
		
		$title = str_replace($quotes, $escquotes, $title);
		$title = trim(html_entity_decode($title));
		
		$entry = str_replace($quotes, $escquotes, $entry);
		$entry = trim(html_entity_decode($entry));
		$id =  time();
		
		$sql = "INSERT INTO `bean_entries` (id, title, topic, entry) 
		VALUES ($id, '".mysql_real_escape_string($title)."', '".mysql_real_escape_string($topic)."', '".mysql_real_escape_string($entry)."');";
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
			/*
			$username = 'lindseybieda';
			$password = '';
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
			} */
			
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
				
		$sql = "UPDATE `bean_entries` SET title = '".mysql_real_escape_string($title)."', entry = '".mysql_real_escape_string($entry)."' WHERE id = $id;";
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
			VALUES ($entryid, $id, '$poster', '$email', '".mysql_real_escape_string($comment)."');";
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
	
?>
