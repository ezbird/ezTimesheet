<?php
session_start();
include 'variables.php';
include 'includes/class.phpmailer.php';

$actual_link = "$_SERVER[REQUEST_URI]";
if (!isset($_SESSION['EmployeeID']) && !isset($_SESSION['TimesheetLoggedIn']) && strpos($actual_link, 'home.php') !== FALSE) { 
	header("Location: index.php");
}

$connection = mysql_connect("localhost", $database_user, $database_pass) or die(mysql_error());
mysql_select_db($database_name, $connection) or die(mysql_error());
?>

<html>
    <head>
    <title><?php echo $organization; ?> Timesheet</title>
    <link href="../images/favicon.ico" rel="icon" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Ubuntu|Roboto" rel="stylesheet">
    <link href='style.css' rel='stylesheet' type='text/css' />
    <link href='nav.css' rel='stylesheet' type='text/css' />
    <script>
	function toggle(source) {
	  checkboxes = document.getElementsByName('check_list[]');
	  for(var i=0, n=checkboxes.length;i<n;i++) {
	    checkboxes[i].checked = source.checked;
	  }
	}
    </script>
    </head>
<body>

<br />
<div class='box'>
<div style='float:left;margin-top:-17px;'>
<a href="<?php echo $primary_URL; ?>timesheet/"><img src='images/timesheet_icon.jpg' alt="timesheet icon" width='60' /></a><br />
</div>
<div style='float:right;margin-top:-7px;opacity:0.4'>
<a href="http://hyperspaceweb.net/software/eztimesheet" target="new"><img src="http://hyperspaceweb.net/software/eztimesheet/images/ezTimesheetLogo.png" width='130'></a>
</div>