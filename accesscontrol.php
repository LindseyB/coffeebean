<?php // accesscontrol.php
include_once 'common.php';
include_once 'db.php';

session_start();

$uid = isset($_POST['uid']) ? $_POST['uid'] : $_SESSION['uid'];
$pwd = isset($_POST['pwd']) ? $_POST['pwd'] : $_SESSION['pwd'];



if(!isset($uid)) {
  ?>
  <!DOCTYPE html PUBLIC "-//W3C/DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title> CoffeeBean: Please Log In for Access </title>
    <meta http-equiv="Content-Type"
      content="text/html; charset=iso-8859-1" />
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
		max-height: 600px;
		margin: auto;
		overflow: hidden;
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
    
	</style>
  </head>
  <body>
 <div class="roundedcornr_box">
   <div class="roundedcornr_top"><div></div></div>
	  <div class="roundedcornr_content">
	  	<p>
			<h1>CoffeeBean: Login</h1>
			  <form method="post" action="index.php" name="login">
			    user: <input type="text" name="uid"><br/>
			    pass: <input type="password" name="pwd"><br/>
			    <input type="submit" value="Submit">
			    <input type="reset" value="Reset">
			  </form>
 		</p>
	  </div>
   <div class="roundedcornr_bottom"><div></div></div>
</div>
</body>
</html>
  <?php
  exit;
}

$_SESSION['uid'] = $uid;
$_SESSION['uid'] = $uid;
$_SESSION['pwd'] = $pwd;

//pass the salt
$passsalt = md5(md5($pass).$uid);

dbConnect();
$sql = "SELECT * FROM bean_login WHERE
        username = '$uid' AND password = '$passsalt' ";
$result = mysql_query($sql);
if (!$result) {
  error('A database error occurred while checking your '.
        'login details.');
}

if (mysql_num_rows($result) === 0) {
  unset($_SESSION['uid']);
  unset($_SESSION['pwd']);
  ?>
  <!DOCTYPE html PUBLIC "-//W3C/DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title> Access Denied </title>
    <meta http-equiv="Content-Type"
      content="text/html; charset=iso-8859-1" />
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
		max-height: 600px;
		margin: auto;
		overflow: hidden;
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
			  <h1> Access Denied </h1>
			    Your user ID or password is incorrect, or you are not a
			     registered user on this site. To try logging in again, click
			     <a href="<?=$_SERVER['PHP_SELF']?>">here</a>.
 		</p>
	  </div>
   <div class="roundedcornr_bottom"><div></div></div>
</div>
</body>
</html>
  <?php
  exit;
}

?>

