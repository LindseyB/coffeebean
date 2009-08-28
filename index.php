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
	<style type="text/css">
	body
	{
		font-family: "Calibri", Helvetica, Geneva, Tahoma, Verdana, sans-serif;
	}

	img
	{
		vertical-align: bottom;
		border: 0;
	}

	/* pagination */
	.paginate
	{
		width: 15%;
		margin: auto;
	}
	
	.paginate a
	{
		display: block;
		border: solid 1px  #818181;
		margin: 5px;
		padding: 5px;
		height: 15px;
		line-height: 15px;
		width: 50px;
		text-align: center;
		float: right;
		background-color: #fff;
	}

	/* pagination */
	.paginate a:hover
	{
		background-color: #e2e2e2;
	}


	/* link color */
	a:link {color: #ad45cf; text-decoration: none; font-weight: bold; font-size: 14px;}
	a:visited {color: #ad45cf; text-decoration: none; font-weight: bold;font-size: 14px;}
	a:hover {color: #51a8f9}

	a.header {color: black;}
	
	/********* Form CSS ***********************/
	form
	{
		padding: 5px;
		margin: 5px;
		font-family: "Calibri", Helvetica, Geneva, Tahoma, Verdana, sans-serif;
		margin: auto;
	}
	
	input
	{
		font-family: "Calibri", Helvetica, Geneva, Tahoma, Verdana, sans-serif;
		margin: 5px;
		padding: 5px;
	}
	
	textarea
	{
		font-family: "Calibri", Helvetica, Geneva, Tahoma, Verdana, sans-serif;
	}

	/********* Table CSS **********************/
	table
	{
		width: 75%;
		border-collapse: collapse;
		margin: auto;
		margin-top: 20px;
		margin-bottom: 5px;
		background-color: white;
	}

	tr, td, th
	{
		border: solid 1px #51a8f9;
		padding: 5px;
	}

	tr.altbg
	{
		background-color: #f8e1ff; 
	}

	th.date
	{
		width: 100px;
	}

	td.date
	{
		text-align: center;
	}

	th.action
	{
		width: 50px;
	}

	td.action
	{
		text-align: center;
	}

	th
	{
		color: white;
		background-color: #51a8f9;
	}

	/********* Rounded box CSS ****************/
	.roundedcornr_box
	{
		background: url(roundedcornr_tl.png) no-repeat top left;
		max-width: 1024px;
		max-height: 1171px;
		margin: auto;
	}

	.roundedcornr_top
	{
		background: url(roundedcornr_tr.png) no-repeat top right;
		overflow: hidden;
	}

	.roundedcornr_bottom
	{
		background: url(roundedcornr_bl.png) no-repeat bottom left;
		overflow: hidden;
	}

	.roundedcornr_bottom div
	{
		background: url(roundedcornr_br.png) no-repeat bottom right;
		overflow: hidden;
	}

	.roundedcornr_content
	{
		background: url(roundedcornr_r.png) top right repeat-y;
		overflow: hidden;
	}

	.roundedcornr_top div,.roundedcornr_top,
	.roundedcornr_bottom div, .roundedcornr_bottom
	{
		width: 100%;
		height: 15px;
		font-size: 1px;
		overflow: hidden;
	}

	.roundedcornr_content, .roundedcornr_bottom
	{
		margin-top: -19px;
	}

	.roundedcornr_content { padding: 0 15px; }

	</style>
</head>

<body>

<div class="roundedcornr_box">
   <div class="roundedcornr_top"><div></div></div>
	  <div class="roundedcornr_content">
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
			
			echo '
				<form method="post" action="index.php?new=1&sub=1" name="new" />
				title: <input type="text" name="title" value="" size="30"/><br/>
				topic: <input type="text" name="topic" value="" size="30"/><br/>
				<textarea name="entry" rows="15" cols="80"></textarea><br/>
				<input type="submit" name="submit" value="Submit" />
				<input type="reset" value="Reset" />
				</form>
				';
		}
	}
	
	//display entries to add comments to
	elseif(isset($_GET['comment']))
	{
		//show comment form
		if(isset($_GET['cpage']))
		{
			echo'<form action="index.php?comment=1&csub=1&n='.$_GET['n'].'" method="post">
 			<textarea name="comment" rows="8" cols="65"></textarea><br/>
			 <input type="submit" value="Submit" name="submit"/>
			 <input type="reset" value="Reset" />
			</form>';
			
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
   
			echo '
			<form method="post" action="index.php?edit=1&esub=1&n='.$_GET['n'].'" name="edit" />
			title: <input type="text" name="title" value="'.$title[0].'" size="30"/><br/>
			<textarea name="entry" rows="15" cols="80">'.br2nl2($entry[0]).'</textarea><br/>
			<input type="submit" name="submit" value="Submit" />
			<input type="reset" value="Reset" />
			</form>
			';	
			
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
   <div class="roundedcornr_bottom"><div></div></div>
</div>
</body>
</html>
