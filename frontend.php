<?php

	function display_new()
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
	
	function display_comment_form($n)
	{
			echo'
			<form action="index.php?comment=1&csub=1&n='.$n.'" method="post">
 			<textarea name="comment" rows="8" cols="65"></textarea><br/>
			<input type="submit" value="Submit" name="submit"/>
			<input type="reset" value="Reset" />
			</form>';
	}
	
	function display_edit($n, $title, $entry)
	{
			echo '
			<form method="post" action="index.php?edit=1&esub=1&n='.$n.'" name="edit" />
			title: <input type="text" name="title" value="'.$title.'" size="30"/><br/>
			<textarea name="entry" rows="15" cols="80">'.br2nl2($entry).'</textarea><br/>
			<input type="submit" name="submit" value="Submit" />
			<input type="reset" value="Reset" />
			</form>
			';
	}

	function display_comments($n)
	{
		$sql = "SELECT * FROM `bean_comments` WHERE `entryid` = " . $n;
		$result = mysql_query($sql);
		
		echo '<table>';
		echo '<tr><th>poster</th><th>comment</th><th>Delete?</th></tr>';
		
		while($row = mysql_fetch_object($result))
		{
			$comment = $row->comment;
		
			$comment = str_replace("<br/>", "", $comment);
			
			if(strlen($comment) > 40)
			{
				$comment = substr($comment, 0, 40) . "...";
			} 
			
			echo '<tr><td>' . $row->poster . '</td><td>' . $comment . '</td><td class="action"><a href="index.php?edit=1&epage=1&n='. $_GET['n'] .'&com='. $row->id .'"><img src="delete.png" alt="delete"/></a></td></tr>';

		}
		
		echo '</table>';
		
		mysql_free_result($result);
		
	}
	
	function display_detail($id, $color)
	{
		$date = date('m.d.Y', $id);
		
		$sqlt = "SELECT `title` FROM `bean_entries` WHERE `id` = " . $id;
		$qryt = mysql_query($sqlt);
		$title = mysql_fetch_array($qryt);
		
		if($color == 1)
			$style = ' class="altbg"';

		echo '
				<tr '.$style.'>
					<td class="date">'.$date.'</td>
					<td>'.$title[0].'</td>
					<td class="action">
						<a href="index.php?delete=1&dsub=1&n='.$id.'"><img src="delete.png" alt="delete"/></a><a href="index.php?edit=1&epage=1&n='.$id.'"><img src="edit.png" alt="edit"/></a><a href="index.php?comment=1&cpage=1&n='.$id.'"><img src="comment.png" alt="comment"/></a>
					</td>
				</tr>
		';
	}

	function show_entries()
	{

		$p = $_GET['p'];
	
		echo '<img src="new.png" alt="new"/> <a href="index.php?new=1">Add a new blog entry</a>';
		echo '<br/><img src="logout.gif" alt="logout"/> <a href="logout.php">Logout</a>';
	
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

		$limit = 10;
		$max = ceil(count($arr)/$limit);

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
			
			echo '<table>';
			echo '<tr>
					<th class="date">Date</th>
					<th>Title</th>
					<th class="action">Action</th>
				</tr>';
		
			for($i=($limit*($p-1)); $i<count($arr); $i++)
			{
				if($i>=($limit*($p-1))+$limit)
				{
					break;
				}

				display_detail($arr[$i], $i%2);
			}
			
			echo '</table>';
		}
		
		if(count($arr)>=$limit)
		{	

			$prev = $p-1;
			$next = $p+1;

			echo '
			<div class="paginate"><a href="' . $PHP_SELF . '?p=' . $next . '">Next »</a><a href="' . $PHP_SELF . '?p=' . $prev . '">« Prev</a></div>
			<br style="clear: right;"/>
			';
		}		
	}
?>
