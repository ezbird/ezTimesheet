<?php
session_start();
include 'variables.php';

$connection = mysql_connect("localhost", "ezra087_1", "primary") or die(mysql_error());
mysql_select_db($database_name, $connection) or die(mysql_error());

if ($_GET['logout'] != "") {
	session_destroy();
	header("Location: index.php");
	exit;
}

if (isset($_POST['password']))
{
	$query = mysql_query("
	 SELECT * FROM ".$table_employees." WHERE EmployeeID = '".$_POST['employeeid']."' AND Password = '".$_POST['password']."'
	 ") or die(mysql_error()); $q=0;
	while($row = mysql_fetch_array( $query )) { 
		$_SESSION['EmployeeID'] = $row['EmployeeID'];
		$_SESSION['TimesheetLoggedIn'] = "yes";
		$_SESSION['FirstName'] = $row['FirstName'];
		$_SESSION['LastName'] = $row['LastName'];
		$_SESSION['AccountType'] = $row['AccountType'];
		$_SESSION['PayRate'] = $row['PayRatePerHour'];
		$q++;
		mysql_query("
		 UPDATE ".$table_employees." SET LastLoggedIn = '".date("M j y h:ia")."' WHERE EmployeeID = '".$row['EmployeeID']."'
		 ") or die(mysql_error());
	}
}

if(isset($_SESSION['EmployeeID']) && isset($_SESSION['TimesheetLoggedIn'])) {	
	header("Location: home.php");
	exit;
}

include '_header.php';

if(isset($_POST['password'])) echo '<span style="color:brown">Login incorrect! Please try again.</span><br>';
?>
<div style='background-color:#EEE;padding:8px;'>Timesheet for <?php echo $organization; ?></div>
<br /><br />
<form action="index.php" method="post">
<table align="center" style='background-color:#EEE;width:380px;border:1px solid #AAA;padding:15px;border-radius:18px;font-size:13px;'>
<tr><td align='center'>
<?php
$getNames = mysql_query("SELECT * FROM ".$table_employees." WHERE Archive <> 'Y' ORDER BY FirstName asc"); ?>
<select name="employeeid" class="input" style='width:150px;'>
<option value="">-- choose name --</option>
	<?php while($row8 = mysql_fetch_array( $getNames )) { 
	$temp1 = $row8['FirstName'] . " " . $row8['LastName'];
	?> <option value="<?php echo $row8['EmployeeID']; ?>" <?php if ($_GET['id'] == $row8['EmployeeID']) echo 'selected="selected"'; ?>><?php echo $temp1; ?></option>
	<?php } ?>
</select>
<br />
<input type='password' name='password' class='input' size='14' style='width:140px;' placeholder="Password" autofocus /><br />
<br />
<input type='submit' value='Login' class='button' />
</td></tr>
</table>
</form>

<?php
include '_footer.php';
?>
