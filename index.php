<?php
	include_once 'accesscontrol.php';
	include_once 'entrymanagement.php';
	include_once 'common.php';
	include_once 'db.php';	
	include_once 'frontend.php';
?>
<html>
<head>
	<title>CoffeeBean</title>
	<link rel="stylesheet" type="text/css" href="coffeebean.css" />
</head>

<body>

<div class="content">
	  	<p>
	  		<a href="index.php" class="header"><h1>CoffeeBean</h1></a>
<?php
	
	//display new entry form
	if(isset($_GET['new']))
	{
		if(isset($_GET['sub']))
		{
			add_entry($_POST['title'], $_POST['topic'], $_POST['entry']);
		}
		else
		{
			display_new();
		}
	}
	
	//display entries to add comments to
	elseif(isset($_GET['comment']))
	{
		//show comment form
		if(isset($_GET['cpage']))
		{
			display_comment_form($_GET['n']);
		}
		elseif(isset($_GET['csub']))
		{	
			submit_comment($_POST['comment'], $_GET['n']);
		}
		else
		{
			show_entries("comment");
		}
	}
	
	//display edit
	elseif(isset($_GET['edit']))
	{	
		//edit form
		if(isset($_GET['epage']))
		{
			dbConnect();
			
			if(isset($_GET['com']))
			{
				delete_comment($_GET['com']);
			}
		
			$sqlt = "SELECT `title` FROM `bean_entries` WHERE `id` = " . $_GET['n'];
			$qryt = mysql_query($sqlt);
			$title = mysql_fetch_array($qryt);

			$sqle = "SELECT `entry` FROM `bean_entries` WHERE `id` = " . $_GET['n'];
			$qrye = mysql_query($sqle);
			$entry = mysql_fetch_array($qrye);
   			
   			display_edit($_GET['n'], $title[0], $entry[0]);
			
			display_comments($_GET['n']);
		}
		
		elseif(isset($_GET['esub']))
		{
			edit_entry($_GET['n'], $_POST['title'], $_POST['entry']);
		}
		
		else
		{
			show_entries();
		}
	}
	
	//display delete
	elseif(isset($_GET['delete']))
	{
		//delete entry
		if(isset($_GET['dsub']))
		{
			delete_entry($_GET['n']);
		}
		else
		{
			show_entries();
		}
	}
	
	//display home
	else
	{
		show_entries();
	}

?>
		</p>
</div>
</body>
</html>
