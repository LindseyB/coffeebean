<?php
	//header("Content-Type: application/xml; charset=ISO-8859-1"); 
	include_once 'common.php';
	include_once 'db.php';	
	
	dbconnect();	

	$handle = fopen("rss.xml", "w");

	// builds the rss file with the 10 latest entries.
	function build_rss()
	{
		global $handle;

		fwrite($handle, '<?xml version="1.0" encoding="ISO-8859-1" ?>
		<rss version="2.0">
		<link type="application/atom+xml" rel="self" href="http://rarlindseysmash.com/rss.xml"/>

		<channel>
  		<title>Caffeine Powered Automaton</title>
  		<link>http://rarlindseysmash.com</link>
  		<description>Caffeine Powered Automation News Feed</description>');
	
		$sql = "SELECT `id` FROM `bean_entries` ORDER BY `bean_entries`.`id` DESC";
		$qry = mysql_query($sql);
		// get 10 latest IDs and store them in an array
		for($i=0; $i<10; $i++)
		{
			$row = mysql_fetch_array($qry);
			$id_arr[$i] = $row[0];
		}
	
		foreach($id_arr as $id)
		{
			display_item(gettitle($id), $id, getentry($id));
		}


		fwrite($handle, '</channel>

		</rss>');
	}

	function display_item($title, $id, $descrip)
	{
		global $handle;

		fwrite($handle, '<item>
    		<title>' . $title . '</title>
  		<guid isPermaLink="true">http://rarlindseysmash.com/index.php?n=' . $id . '</guid>
  		<link>http://rarlindseysmash.com/index.php?n=' . $id . '</link>
    		<description>' . $descrip . '</description>
		<pubDate>'.date("D, d M Y G:i:s T",$id).'</pubDate>
  		</item>');
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
			
			$entry = htmlentities($entry[0]);
			$entry = str_replace('&nbsp;', '', $entry);  
			//$entry = strip_tags($entry[0]);

			/*if(strlen($entry) >= 300)
			{
				$entry = substr($entry, 0, 300) . "...";
			}*/

			return $entry;
	}
?>
