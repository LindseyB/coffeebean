<?
	include_once 'common.php';
	include_once 'db.php';	
	
	dbconnect();
	
	//if its a specified entry
	if(isset($_GET['n']))
	{
		displayentry($_GET['n']);
		
		if(isset($_GET['comments']))
		{
			include 'comment.php';
		}
	}
	else
	{
	
		$sql = "SELECT `id` FROM `bean_entries`";
		$qry = mysql_query($sql);
		$i = 0;
		// get all IDs and store them in an array
		while($row = mysql_fetch_array($qry))
		{
			$arr[$i] = $row[0];
			$i++;
		}
		
		$arr = array_reverse($arr);
   
		$limit = 5;
		$max = ceil(count($arr)/$limit);
		
		if(isset($_GET['p']))
		{
			$p = $_GET['p'];
		}
		else
		{
			$p = 1;
		}
   
		if($p<=0)
		{
			$p=1;
		}
		if($p>$max)
		{
			$p=$max;
		}
   
		//display entries if they exist
		if(count($arr)>0)
		{
			for($i=($limit*($p-1)); $i<count($arr); $i++)
			{
				if($i>=($limit*($p-1))+$limit)
				{
					break;
				}
   
				displayentry($arr[$i]);
			}
		}
   
		// show paging if more than one page exists
		if(count($arr)>=$limit)
		{	
   
			$prev = $p-1;
			$next = $p+1;
   
			echo '	<div class="paginate">';
			
			if($p < $max)
			{
					echo '<a href="index.php?p=' . $next . '">Next »</a>';	
			}
			if($p > 1)
			{ 
				echo '<a href="index.php?p=' . $prev . '">« Prev</a>';
			}

			echo '</div><br style="clear: right;"/>';
		}
   
	}

	
	function displayentry($id)
	{
		$day = date("d", $id);
		$month = date("m", $id);
		$year = date("y", $id);
	
		echo '
				<h1>' . gettitle($id) . '</h1>
				<h2>' . $month . '.' . $day . '.' . $year . '</h2>
				<div class="entry">' . getentry($id) . '</div>
				<div class="entryfoot">';
				
				if(isset($_GET['n']) && !isset($_GET['comments']))
				{
					echo '<img src="images/cpa_link.png" alt="link"/>';
				}
				else
				{
					echo '<a href="index.php?n='.$id.'"><img src="images/cpa_link.png" alt="link"/></a> '; 
				}
				
				if(isset($_GET['comments']))
				{
					echo 'comments (' . getCommentNum($id) . ')';
				}
				else
				{
					echo '<a href="index.php?n='.$id.'&amp;comments=1">comments ('. getCommentNum($id) .')</a>';
				}
				
				echo '</div>
				';
		
	}
	
	function gettitle($id)
	{
			$sqlt = "SELECT `title` FROM `bean_entries` WHERE `id` = " . $id;
			$qryt = mysql_query($sqlt);
			$title = mysql_fetch_array($qryt);
			
			return $title[0];
	}
	
	function getentry($id)
	{
			$sqle = "SELECT `entry` FROM `bean_entries` WHERE `id` = " . $id;
			$qrye = mysql_query($sqle);
			$entry = mysql_fetch_array($qrye);

			return $entry[0];
	}
	
	function getCommentNum($id)
	{
	
		$sql = "SELECT `id` FROM `bean_comments` WHERE `entryid` = " . $id;
		$qry = mysql_query($sql);
		$i = 0;

		$carr = array();

		// get all IDs and store them in an array
		while($row = mysql_fetch_array($qry))
		{
			$carr[$i] = $row[0];
			$i++;
		}
		
		if(isset($_POST['submit']))
		{
			return count($carr) + 1;
		}

			return count($carr);
	}
?>