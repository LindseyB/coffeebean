<?php
	
	include 'renderer.php';
	
	echo'
	<html>
	<head>
	<title>CoffeeBean : code insert</title>
	
	</head>
	<body>
	<form method="post" action="codeinsert.php" name="coderender"/>
	<select name="language">
		<option value="0" selected="selected">C/C++</option>
	    <option value="1" selected="selected">BASIC</option>
		<option value="2" selected="selected">Java</option>
		<option value="3" selected="selected">HTML</option>
		<option value="4" selected="selected">PHP</option>
	</select>
	<textarea name="code" rows="10" cols="40"></textarea>
	<input type="submit" name="submit" value="Submit" />
	<input type="reset" value="Reset" />
	</form>';
	
	if(isset($_POST))
	{
		echo '<textarea name="output" rows="10" cols="40">';
		echo remove_bs(RenderToString(stripslashes($_POST['code']), $_POST['language']));
		echo '</textarea>';
	}
	
	echo'
	</body>
	</html>
	';
	
	function remove_bs($string)
	{
		$string = str_replace(array("\r\n", "\r", "\n"), "", $string);
		return $string;
	}
?>