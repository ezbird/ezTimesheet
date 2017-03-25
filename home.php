<?php 
// This is the primary Timesheet module (timesheet.php)
// --- In conjuction with _header.php and _footer.php, this provides a dynamic, re-usable timesheet tool
//
// All variables that need to be edited are in the variables.php file.

$times = array("",
	"12:00 AM", "12:15 AM","12:30 AM","12:45 AM",
	"1:00 AM", "1:15 AM","1:30 AM","1:45 AM",
	"2:00 AM", "2:15 AM","2:30 AM","2:45 AM",
	"3:00 AM", "3:15 AM","3:30 AM","3:45 AM",
	"4:00 AM", "4:15 AM","4:30 AM","4:45 AM",
	"5:00 AM", "5:15 AM","5:30 AM","5:45 AM",
	"6:00 AM", "6:15 AM","6:30 AM","6:45 AM",
	"7:00 AM", "7:15 AM","7:30 AM","7:45 AM",
	"8:00 AM", "8:15 AM","8:30 AM","8:45 AM",
	"9:00 AM", "9:15 AM","9:30 AM","9:45 AM",
	"10:00 AM","10:15 AM","10:30 AM","10:45 AM",
	"11:00 AM","11:15 AM","11:30 AM","11:45 AM",
	"12:00 PM","12:15 PM","12:30 PM","12:45 PM",
	"1:00 PM", "1:15 PM","1:30 PM","1:45 PM",
	"2:00 PM", "2:15 PM","2:30 PM","2:45 PM",
	"3:00 PM", "3:15 PM","3:30 PM","3:45 PM",
	"4:00 PM", "4:15 PM","4:30 PM","4:45 PM",
	"5:00 PM", "5:15 PM","5:30 PM","5:45 PM",
	"6:00 PM", "6:15 PM","6:30 PM","6:45 PM",
	"7:00 PM", "7:15 PM","7:30 PM","7:45 PM",
	"8:00 PM", "8:15 PM","8:30 PM","8:45 PM",
	"9:00 PM", "9:15 PM","9:30 PM","9:45 PM",
	"10:00 PM", "10:15 PM","10:30 PM","10:45 PM",
	"11:00 PM", "11:15 PM","11:30 PM","11:45 PM",
	);

include '_header.php';

$Hour = date('G');
?>

<div class="nav">
<ul>
<?php if ($_SESSION['AccountType'] == 'Accountant') { ?>
	<li><a href="home.php?paychecks">Paychecks</a></li>
<?php } else { ?>
	<li><a href="home.php?add">Add Shift</a></li>
	<li><a href="home.php?archive">Shift History</a></li>

<?php if ($_SESSION['AccountType'] == 'Administrator') { ?>
	<li><a href="home.php?pending">Pending Shifts</a></li>
	<li><a href="home.php?employees">Employees</a></li>
	<li><a href="home.php?paychecks">Paychecks</a></li>
<?php }
      else { ?>
      	<li><a href="home.php?paychecks&employee=<?php echo $_SESSION['EmployeeID']; ?>">Paychecks</a></li>	
      	
<?php } 
}
?>
<li><a href="index.php?logout=yes">Log Out</a></li>
</ul>
</div>

<?php
if(isset($_GET['update_employee'])) {
	if ($_SESSION['AccountType'] != 'Administrator') { echo "<br />Hey, where did you come from?"; exit; }
	
		$getNames = mysql_query("SELECT * FROM ".$table_employees." WHERE EmployeeID = '".$_GET['update_employee']."' ORDER BY AccountType asc");
		$w=0;
		while($row = mysql_fetch_array( $getNames )) {
	?>
<h2>Employees</h2>
<br />
<table align='center'>
<tr><td>
<h3>Update Employee:</h3>
<form action="home.php?update_employee2=<?php echo $_GET['update_employee']; ?>" method="post">
<table>
<tr><td valign='top'>First Name:</td><td><input type="text" name="firstname" class="input" size="16" value="<?php echo $row['FirstName']; ?>" /></td></tr>
<tr><td valign='top'>Last Name:</td><td><input type="text" name="lastname" class="input" size="16" value="<?php echo $row['LastName']; ?>" /></td></tr>
<tr><td valign='top'>Address:</td><td><input type="text" name="address" class="input" size="14" value="<?php echo $row['Address']; ?>" /></td></tr>
<tr><td valign='top'>City:</td><td><input type="text" name="city" class="input" size="14" value="<?php echo $row['City']; ?>" /></td></tr>
<tr><td valign='top'>State:</td><td><input type="text" name="state" class="input" size="14" value="<?php echo $row['State']; ?>" /></td></tr>
<tr><td valign='top'>Zip:</td><td><input type="text" name="zip" class="input" size="14" value="<?php echo $row['Zip']; ?>" /></td></tr>
<tr><td valign='top'>SSN:</td><td><input type="text" name="SSN" class="input" size="14" value="<?php echo $row['SSN']; ?>" /></td></tr>
<tr><td valign='top'>Email:</td><td><input type="text" name="email" class="input" size="16" value="<?php echo $row['Email']; ?>" /></td></tr>
<tr><td valign='top'>Password:</td><td><input type="text" name="password" class="input" size="16" value="<?php echo $row['Password']; ?>" /></td></tr>
<tr><td valign='top'>Hourly rate:</td><td>
    <select name="rate" class="input">
	    <?php
	    for ($x = 7.25; $x <= 50; $x+=0.25) {
		   if ($row['PayRatePerHour'] == $x) echo '<option value="'.$x.'" selected="selected">'.sprintf('%0.2f', $x).'</option>';
		   else echo '<option value="'.$x.'">'.sprintf('%0.2f', $x).'</option>';
	    }
	    ?>
    </select>
</td></tr>
<tr><td valign='top'>Employee Type:</td><td>
    <select name="AccountType" class="input">
	    <option>Hourly</option>
	    <option <?php if($row['AccountType'] == 'Accountant') echo 'selected="selected"'; ?>>Accountant</option>
	    <option <?php if($row['AccountType'] == 'Administrator') echo 'selected="selected"'; ?>>Administrator</option>
    </select>
</td></tr>
<tr><td valign='top'>Email paycheck receipts:</td><td>
    <select name="EmailPaycheck" class="input">
	    <option <?php if($row['EmailPaycheck'] == 'Yes') echo 'selected="selected"'; ?>>Yes</option>
	    <option <?php if($row['EmailPaycheck'] == 'No') echo 'selected="selected"'; ?>>No</option>
    </select>
</td></tr>
<tr><td style='font-size:11px;'>- <i>Optional</i> -</td></tr>
<tr><td style='font-size:11px;'>Monthly insurance deduction:</td><td>
<input type="text" name="MonthlyInsuranceDeduct" class="input" size="8" value="<?php echo $row['MonthlyInsuranceDeduct']; ?>" />
</td></tr>
<tr><td style='font-size:11px;'>Addt'l Fed deduction per paycheck:</td><td>
<input type="text" name="AddFedDeduct" class="input" size="8" value="<?php echo $row['AddFedDeduct']; ?>" />
</td></tr>
<tr><td style='font-size:11px;'>Addt'l State deduction per paycheck:</td><td>
<input type="text" name="AddStateDeduct" class="input" size="8" value="<?php echo $row['AddStateDeduct']; ?>" />
</td></tr>
<tr><td></td><td><input type="submit" value="Update" class="button" /></td></tr>
</table>
</form>		
	<?php } ?>
</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td valign='top'>


</td></tr>
</table>

	<?php
	exit;
}
if(isset($_GET['employees'])) {
	
	if ($_SESSION['AccountType'] != 'Administrator') { echo "<br />Hey, where did you come from? You are not logged in with proper privileges."; exit; }
	?>
	
<h2>Employees</h2>
<br />
<table align='center'>
<tr><td>
<h4>Add Employee:</h4>
<form action="home.php?add_employee2" method="post">
<table>
<tr><td valign='top'>First Name:</td><td><input type="text" name="firstname" class="input" size="14" /></td></tr>
<tr><td valign='top'>Last Name:</td><td><input type="text" name="lastname" class="input" size="14" /></td></tr>
<tr><td valign='top'>Address:</td><td><input type="text" name="address" class="input" size="14" /></td></tr>
<tr><td valign='top'>City:</td><td><input type="text" name="city" class="input" size="14" /></td></tr>
<tr><td valign='top'>State:</td><td><input type="text" name="state" class="input" size="14" /></td></tr>
<tr><td valign='top'>Zip:</td><td><input type="text" name="zip" class="input" size="14" /></td></tr>
<tr><td valign='top'>SSN:</td><td><input type="text" name="SSN" class="input" size="14" /></td></tr>
<tr><td valign='top'>Email:</td><td><input type="text" name="email" class="input" size="14" /></td></tr>
<tr><td valign='top'>Password:</td><td><input type="text" name="password" class="input" size="14" /></td></tr>
<tr><td valign='top'>Hourly rate:</td><td>
    <select name="rate" class="input">
	    <?php
	    for ($x = 7.25; $x <= 50; $x+=0.25) {
		   if ($x==10) echo '<option value="'.$x.'" selected="selected">'.sprintf('%0.2f', $x).'</option>';
		   else echo '<option value="'.$x.'">'.sprintf('%0.2f', $x).'</option>';
	    }
	    ?>
    </select>
</td></tr>
<tr><td valign='top'>Employee Type:</td><td>
    <select name="AccountType" class="input">
	    <option>Hourly</option>
	    <option>Accountant</option>
	    <option>Administrator</option>
    </select>
</td></tr>
<tr><td valign='top'>Email paycheck receipts:</td><td>
    <select name="EmailPaycheck" class="input">
	    <option <?php if($row['EmailPaycheck'] == 'Yes') echo 'selected="selected"'; ?>>Yes</option>
	    <option <?php if($row['EmailPaycheck'] == 'No') echo 'selected="selected"'; ?>>No</option>
    </select>
</td></tr>
<tr><td style='font-size:11px;'>- <i>Optional</i> -</td></tr>
<tr><td style='font-size:11px;'>Monthly insurance deduction:</td><td>
<input type="text" name="MonthlyInsuranceDeduct" class="input" size="8" value="0.00" />
</td></tr>
<tr><td style='font-size:11px;'>Addt'l Fed deduction per paycheck:</td><td>
<input type="text" name="AddFedDeduct" class="input" size="8" value="0.00" />
</td></tr>
<tr><td style='font-size:11px;'>Addt'l State deduction per paycheck:</td><td>
<input type="text" name="AddStateDeduct" class="input" size="8" value="0.00" />
</td></tr>
<tr><td></td><td><input type="submit" value="Add" class="button" /></td></tr>
</table>
</form>		
	
</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td valign='top'>
<h4>Current Employees:</h4>
<table cellspacing="0">
<?php
	$getNames = mysql_query("SELECT * FROM ".$table_employees." WHERE Archive <> 'Y' ORDER BY AccountType asc,FirstName asc");
		$w=0;
		while($row = mysql_fetch_array( $getNames )) { $w++;
			if($w%2) $bgcolor="#EEEEEE"; else $bgcolor="#FFFFFF";
			echo '<tr bgcolor='.$bgcolor.'><td>'.$row['FirstName'].'</td><td>&nbsp;&nbsp;&nbsp;'.$row['AccountType'].'</td>
			<td align="center">&nbsp;<a href="home.php?update_employee='.$row['EmployeeID'].'" style="font-size:11px;" title="edit">edit</a></td><td>&nbsp;<a href="home.php?remove_employee='.$row['EmployeeID'].'" style="font-size:11px;" title="remove" onclick="return confirm(\'Are you sure you want to remove this employee?\')">remove</a></td></tr>';
		}
?>
</table>

</td></tr>
</table>
<?php
exit;
}

else if(isset($_GET['add_employee2'])) {
	if ($_SESSION['AccountType'] != 'Administrator') { echo "<br />Hey, where did you come from? You are not logged in with proper privileges."; exit; }
	if ($_POST['firstname'] == '' AND $_POST['lastname'] == '') { echo "<br />Please enter a name for the employee."; exit; }
	mysql_query("
       	       INSERT INTO ".$table_employees." (FirstName,Lastname,Address,City,State,Zip,SSN,Email,EmailPaycheck,Password,PayRatePerHour,AccountType,MonthlyInsuranceDeduct,AddFedDeduct,AddStateDeduct) 
       	       VALUES ('".$_POST['firstname']."','".$_POST['lastname']."','".$_POST['address']."','".$_POST['city']."','".$_POST['state']."','".$_POST['zip']."','".$_POST['SSN']."','".$_POST['email']."','".$_POST['EmailPaycheck']."','".$_POST['password']."','".$_POST['rate']."','".$_POST['AccountType']."','".$_POST['MonthlyInsuranceDeduct']."','".$_POST['AddFedDeduct']."','".$_POST['AddStateDeduct']."')
       	   ") or die(mysql_error());
       echo '<div class="messagetouser">'.$_POST['AccountType'].' added successfully!</div>';
}

else if (isset($_GET['update_employee2'])) { 
	if ($_SESSION['AccountType'] != 'Administrator') { echo "<br />Hey, where did you come from? You are not logged in with proper privileges."; exit; }
	mysql_query("
	 UPDATE ".$table_employees."
	 SET FirstName = '".$_POST['firstname']."',LastName = '".$_POST['lastname']."',Address = '".$_POST['address']."',City = '".$_POST['city']."',State = '".$_POST['state']."',Zip = '".$_POST['zip']."',SSN = '".$_POST['SSN']."',Email = '".$_POST['email']."',EmailPaycheck = '".$_POST['EmailPaycheck']."',Password = '".$_POST['password']."',PayRatePerHour = '".$_POST['rate']."',AccountType = '".$_POST['AccountType']."',MonthlyInsuranceDeduct = '".$_POST['MonthlyInsuranceDeduct']."',AddFedDeduct = '".$_POST['AddFedDeduct']."',AddStateDeduct = '".$_POST['AddStateDeduct']."'
	 WHERE EmployeeID = '".$_GET['update_employee2']."'
	 ") or die(mysql_error());
	echo '<div class="messagetouser">Employee updated successfully!</div>';
}

else if (isset($_GET['remove_employee'])) { 
	if ($_SESSION['AccountType'] != 'Administrator') { echo "<br />Hey, where did you come from? You are not logged in with proper privileges."; exit; }
	mysql_query("
	 UPDATE ".$table_employees."
	 SET Archive = 'Y' 
	 WHERE EmployeeID = '".$_GET['remove_employee']."'
	 ") or die(mysql_error());
	echo '<div class="messagetouser">Employee removed successfully!</div>';
}

else if(isset($_GET['update_pending'])) {
	if ($_SESSION['AccountType'] != 'Administrator') { echo "<br />Hey, where did you come from? You are not logged in with proper privileges."; exit; }
	$checked_count = count($_POST['shiftids']);

	// Loop to store and display values of individual checked checkbox.	
	$t=0;
	foreach($_POST['shiftids'] as $selected) { $t++;
		// Update this shift to "paid"
		$sql="
		 UPDATE ".$table_timesheet."
		 SET Paid = 'y'";
		 
		if (isset($_POST['Tax1'][$t])) $sql .= ",Tax1='".$_POST['Tax1'][$t]."'";
		if (isset($_POST['Tax2'][$t])) $sql .= ",Tax2='".$_POST['Tax2'][$t]."'";
		if (isset($_POST['Tax3'][$t])) $sql .= ",Tax3='".$_POST['Tax3'][$t]."'";
		if (isset($_POST['Tax4'][$t])) $sql .= ",Tax4='".$_POST['Tax4'][$t]."'";
		
		$sql .= "
		 WHERE EntryID = '".$selected."'
		 ";
		// echo $sql;
		 mysql_query($sql) or die(mysql_error());
		}
			
	       mysql_query("
       	       INSERT INTO ".$table_paychecks." (EmployeeID,Date,TotalHours,PayRatePerHour,OvertimePay,FedTax,StTax,FICA,Med,MonthlyInsuranceDeduct,FedDeduct,StateDeduct,TotalAfterTaxes,CheckNum) 
       	       VALUES ('".$_POST['EmployeeID']."','".$_POST['Date']."','".$_POST['TotalHours']."','".$_POST['PayRatePerHour']."','".$_POST['OvertimePay']."','".$_POST['FedTax']."','".$_POST['StTax']."','".$_POST['FICA']."','".$_POST['Med']."','".$_POST['MonthlyInsuranceDeduct']."','".$_POST['FedDeduct']."','".$_POST['StateDeduct']."','".$_POST['TotalAfterTaxes']."','".$_POST['checknum']."')
       	       ") or die(mysql_error());
       	       
     /* ONLY USED FOR ALWAYS HOME */  	       
     $InsertExpense = mysql_query("
	 INSERT INTO petsitting_expenses(Date,PaidTo,Total,Category,Description)
	 VALUES('".mysql_real_escape_string(date("m/d/Y"))."',
	 '".mysql_real_escape_string($_POST['EmployeeID'])."',
	 '".mysql_real_escape_string($_POST['TotalAfterTaxes'])."',
	 '".mysql_real_escape_string('3')."',
	 '".mysql_real_escape_string($_POST['checknum'])."')
     ") or die(mysql_error());
     /* ONLY USED FOR ALWAYS HOME 
     ------------------------------------
     !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     ------------------------------------
     */
     
     /* Email Employee a Paycheck Receipt */
     
	$getNames = mysql_query("SELECT * FROM ".$table_employees." WHERE EmployeeID = '".$_POST['EmployeeID']."'");
		while($row = mysql_fetch_array( $getNames )) {
			$emailpaycheck = $row['EmailPaycheck'];$emailaddress = $row['Email'];$first = $row['FirstName'];$last = $row['LastName'];
		}
     
	if ($emailpaycheck == 'Yes' AND $emailaddress != "") {
     	 $body = "	
			<table width='400' style='border:4px solid darkgreen;padding:25px;box-shadow: -10px 10px 5px #888888;'>
			<tr>
			<td width='200'><img src='".$path_to_logo."' width='100'/></td>
			<td align='right' valign='bottom'>".date('F d, Y')."</td>
			</tr>
			<tr>
			<td colspan='2'>
			<hr>
			";
		if ($_POST['OvertimePay'] == "") $_POST['OvertimePay'] = "0.00";
		$body .= "
		<table>
		<tr><td><strong>Paycheck Details</strong><br /><br /></td></tr>
		<tr><td>Name:</td><td>".$first." ".$last."</td></tr>
		<tr><td>Paid on:</td><td>".$_POST['Date']."</td></tr>
		<tr><td>Hours worked:</td><td>".$_POST['TotalHours']."</td></tr>
		<tr><td>Fed Tax:</td><td> ".money_format('%.2n', $_POST['FedTax'])."</td></tr>
		<tr><td>State Tax: </td><td>".money_format('%.2n', $_POST['StTax'])."</td></tr>
		<tr><td>FICA:</td><td> ".money_format('%.2n', $_POST['FICA'])."</td></tr>
		<tr><td>Medicare: </td><td>".money_format('%.2n', $_POST['Med'])."</td></tr>
		<tr><td>Insurance deduction: </td><td>".money_format('%.2n', $_POST['MonthlyInsuranceDeduct'])." </td></tr>
		<tr><td>Federal deduction: </td><td>".money_format('%.2n', $_POST['FedDeduct'])."</td></tr>
		<tr><td>State deduction: </td><td>".money_format('%.2n', $_POST['StateDeduct'])."</td></tr>
		<tr><td>Overtime pay: </td><td>".money_format('%.2n', $_POST['OvertimePay'])."</td></tr>
		<tr><td>Total after taxes: </td><td>".money_format('%.2n', $_POST['TotalAfterTaxes'])."</td></tr>
		</table>
		";

		$body .= "
		<br />
		</td></tr>
		<tr><td colspan='3' align='center'>
		<hr>
		Thank you! Store this email for your records.
		</td></tr>
		</table>";
			
		$email = new PHPMailer();
		$email->IsSMTP();
		$email->Host = $host;
		$email->Port = $port;
		$email->Username = $user;
		$email->Password = $pass; 
		$email->From = $fromemail;
		$email->FromName  = $organization;
		$email->Subject   = "Paycheck Information";
		$email->Body      = $body;     
		$email->AddAddress( $emailaddress );
		$email->AddReplyTo( $adminemail );
		$email->AddBCC( 'waterspring@gmail.com' );
		$email->isHTML(true);
		
		if(!$email->send()) {
		   echo 'Message could not be sent.';
		   echo 'Mailer Error: ' . $email->ErrorInfo;
		   exit;
		}
     	  
     }
     
	echo '<div class="messagetouser">Paycheck created. '.$checked_count.' shifts set as paid.</div>';
	
}
   
if(isset($_POST['goPending'])) {
if(!empty($_POST['check_list'])) {  //some checkboxes are checked
	
	$sql3 = mysql_query("SELECT * FROM ".$table_employees." WHERE EmployeeID = '".$_GET['employee']."'");
		  	  while($row4 = mysql_fetch_array( $sql3 )) { $name = $row4['FirstName']." ".$row4['LastName'];$fed_deduct = $row4['AddFedDeduct']; $st_deduct = $row4['AddStateDeduct']; $insur_deduct = $row4['MonthlyInsuranceDeduct']; }
		  
	
	echo '<form action="home.php?update_pending" method="post">';
	
	echo '<div class="messagetouser" style="color:black;background-color:#EEF">
	<br>You have selected '.sizeof($_POST['check_list']).' shifts for '.$name.'.<br /><br />Click Save Paycheck to set these shifts as paid<br />and create a paycheck for the "Final Paycheck" amount below.<br><br>
	<span style="font-size:11px;font-weight:normal;">Enter check no. <input type="text" name="checknum" size="7" /> (optional; for tax records)</span><br /><br />
	';
	
	$z=0;
	foreach($_POST['check_list'] as $value) { $z++;
		echo '<input type="hidden" name="shiftids[]" value="'.$value.'">';
		if ($_POST['deduction1'][$z-1] == "") { $s = $_POST['Tax1'][$z]."%"; } else {$s =$_POST['deduction1'][$z-1];}echo '<input type="hidden" name="Tax1['.$z.']" value="'.$s.'">';
		if ($_POST['deduction2'][$z-1] == "") { $s = $_POST['Tax2'][$z]."%"; } else { $s =$_POST['deduction2'][$z-1];}echo '<input type="hidden" name="Tax2['.$z.']" value="'.$s.'">';
		if ($_POST['deduction3'][$z-1] == "") { $s = $_POST['Tax3'][$z]."%"; } else {$s =$_POST['deduction3'][$z-1];}echo '<input type="hidden" name="Tax3['.$z.']" value="'.$s.'">';
		if ($_POST['deduction4'][$z-1] == "") { $s = $_POST['Tax4'][$z]."%"; } else { $s =$_POST['deduction4'][$z-1];}echo '<input type="hidden" name="Tax4['.$z.']" value="'.$s.'">';

	}
	echo '<input type="button" value="Go Back" class="button" onclick="javascript:history.go(-1);" /> <input type="submit" value="Save Paycheck" class="button" /><br /><br />
	</div><br />';
	
	// show display of these shifts 
						?>
		<table cellspacing="0" class="nicetable" align="center">
		<tr><td>Date</td><td><u>Name</td><td style="white-space:nowrap"><u>Start Time</td>
		<td style="white-space:nowrap"><u>End Time</td><td><u>Hours</td><td style="white-space:nowrap"><u>Rate</td>
		<td><u>Fed Tax</td><td><u>St Tax</td><td><u>FICA</td><td><u>Med</td></tr>
		<?php
		$q = 0;$addup = 0;$addupFed = 0;$addupState = 0;
		
		  foreach($_POST['check_list'] as $value) {
		  	  $sql2 = mysql_query("SELECT * FROM ".$table_timesheet." WHERE Paid = '' AND EntryID = '".$value."'");
		  	  
		  	  while($row = mysql_fetch_array( $sql2 )) {
		  	  	  $employeeid = $row['EmployeeID'];
			  $q++; if ($q % 2) $bg = "#FFFFFF"; else $bg = "#DDDDDD"; 

			//Calculate Taxes (tax rates set in variables.php)
			if ($global_tax1 == 0)  $deduction1 = $_POST['deduction1'][$q-1];
			else { $deduction1=($row['TotalHours']*$row['PayRate']*$global_tax1)/100;  }
			
			if ($global_tax2 == 0) $deduction2 = $_POST['deduction2'][$q-1];
			else { $deduction2=($row['TotalHours']*$row['PayRate']*$global_tax2)/100;  }
			
			if ($global_tax3 == 0) $deduction3 = $_POST['deduction3'][$q-1];
			else { $deduction3=($row['TotalHours']*$row['PayRate']*$global_tax3)/100;  }
			
			if ($global_tax4 == 0) $deduction4 = $_POST['deduction4'][$q-1];
			else { $deduction4=($row['TotalHours']*$row['PayRate']*$global_tax4)/100;  }
			  
			  // -----------------
			//  $sql3 = mysql_query("SELECT * FROM ".$table_employees." WHERE EmployeeID = '".$row['EmployeeID']."'");
			  
		  	  if ($deduction1 == "") $deduction1 = 0; 
		  	  if ($deduction2 == "") $deduction2 = 0; 
		  	  if ($deduction3 == "") $deduction3 = 0; 
		  	  if ($deduction4 == "") $deduction4 = 0; 
			echo '
			<tr bgcolor='.$bg.'>
			<td nowrap="nowrap">'.$row['Date'].'</td>
			<td nowrap="nowrap"><a href="home.php?pending&employee='.$row['EmployeeID'].'">'.$name.'</a></td>
			<td>'.$times[$row['StartTime']].'</td>
			<td>'.$times[$row['EndTime']].'</td>
			<td>'.$row['TotalHours'].'</td>
			<td>'.$row['PayRate'].'</td>
			<td>'.money_format('%.2n', $deduction1).'</td>
			<td>'.money_format('%.2n', $deduction2).'</td>
			<td>'.money_format('%.2n', $deduction3).'</td>
			<td>'.money_format('%.2n', $deduction4).'</td>';
			?>
		</tr>
		<?php
		$deduct1total += $deduction1;
		$deduct2total += $deduction2;
		$deduct3total += $deduction3;
		$deduct4total += $deduction4;
		$addup += $row['TotalHours'];
		$rate = $row['PayRate'];

		}
		  }
		if ($addup > 40 && $overtime_multiplier != 0) $netpay = 40*$rate;
		else $netpay = $addup*$rate;
		?>
		
		
	<?php if ($addup > 40) {
				$deduct1total = 0;
				$deduct2total = 0;
				$deduct3total = 0;
				$deduct4total = 0;
			$overtimepay = money_format("%.2n",($rate*$overtime_multiplier) * ($addup-40));
			$netpay = 40*$rate;
			$netpay += $overtimepay;
			$deduct1total+=($netpay*$global_tax1)/100;
			$deduct2total+=($netpay*$global_tax2)/100;
			$deduct3total+=($netpay*$global_tax3)/100;
			$deduct4total+=($netpay*$global_tax4)/100;
			?>
		<tr><td colspan="3"></td><td><strong>Overtime</strong>:</td>
		<td><?php echo ($addup-40); ?></td>
		<td><?php echo money_format('%.2n', $overtimepay); ?></td>
		<td>$<?php echo money_format('%.2n', ($overtimepay*$global_tax1)/100); ?></td><td>$<?php echo money_format('%.2n', ($overtimepay*$global_tax2)/100); ?></td>
		<td>$<?php echo money_format('%.2n', ($overtimepay*$global_tax3)/100); ?></td><td>$<?php echo money_format('%.2n', ($overtimepay*$global_tax4)/100); ?></td>
		</tr>
		<?php } ?>
		
		
		<tr><td colspan="3"></td><td>Totals:</td><td><?php echo $addup; ?></td>
		<td>$<?php echo money_format('%.2n', $netpay); ?></td>
		<td>$<?php echo money_format('%.2n', $deduct1total); ?></td>
		<td>$<?php echo money_format('%.2n', $deduct2total); ?></td>
		<td>$<?php echo money_format('%.2n', $deduct3total); ?></td>
		<td>$<?php echo money_format('%.2n', $deduct4total); ?></td>
		</tr>
		<?php	  
		  	  
		if ($_POST['doInsurance'] != "on") $insur_deduct = 0;
		
		echo "<tr><td colspan='4'></td><td colspan='5' align='right'>Subtotal:</td><td>".money_format('%.2n',round($netpay-$deduct1total-$deduct2total-$deduct3total-$deduct4total,2))."</td></tr>";
		
		echo "<tr><td colspan='4'></td><td colspan='5' align='right'>Monthly insurance deduction:</td><td>-".money_format('%.2n',$insur_deduct)."</td></tr>";
		if ($fed_deduct != "" AND $fed_deduct != "0.00") { echo "<tr><td colspan='4'></td><td colspan='5' align='right'>Additional federal deduction:</td><td>-".$fed_deduct."</td></tr>"; } else $fed_deduct = 0;
		if ($st_deduct != "" AND $st_deduct != "0.00") { echo "<tr><td colspan='4'></td><td colspan='5' align='right'>Additional state deduction:</td><td>-".$st_deduct."</td></tr>"; } else $st_deduct = 0;
		
		// Calculate final paycheck total
		$finaltotal = money_format('%.2n',round($netpay-$deduct1total-$deduct2total-$deduct3total-$deduct4total-$fed_deduct-$st_deduct-$insur_deduct,2));
		
		if (isset($_GET['employee'])) echo '<tr><td colspan="4"></td><td colspan="6" style="font-size:18px;text-align:right;">Final Paycheck: $'.$finaltotal.'</td></tr>';
		?>
		</table>
		<?php  
		
	echo '<input type="hidden" name="EmployeeID" value="'.$employeeid.'">';
	echo '<input type="hidden" name="Date" value="'.date("m-d-Y").'">';
	echo '<input type="hidden" name="TotalHours" value="'.$addup.'">';
	echo '<input type="hidden" name="PayRatePerHour" value="'.$rate.'">';
	echo '<input type="hidden" name="OvertimePay" value="'.$overtimepay.'">';
	echo '<input type="hidden" name="FedTax" value="'.money_format('%.2n', $deduct1total).'">';
	echo '<input type="hidden" name="StTax" value="'.money_format('%.2n', $deduct2total).'">';
	echo '<input type="hidden" name="FICA" value="'.money_format('%.2n', $deduct3total).'">';
	echo '<input type="hidden" name="Med" value="'.money_format('%.2n', $deduct4total).'">';
	echo '<input type="hidden" name="MonthlyInsuranceDeduct" value="'.money_format('%.2n', $insur_deduct).'">';
	echo '<input type="hidden" name="FedDeduct" value="'.money_format('%.2n', $fed_deduct).'">';
	echo '<input type="hidden" name="StateDeduct" value="'.money_format('%.2n', $st_deduct).'">';
	echo '<input type="hidden" name="TotalAfterTaxes" value="'.$finaltotal.'">';

	
	echo '</form>';
		
	exit;
}
else { echo "Please select at least one checkbox."; exit; }

}

if (isset($_GET['delete'])) { 
mysql_query("
	 DELETE FROM ".$table_timesheet."
	 WHERE EntryID = '".$_GET['delete']."'
	 ") or die(mysql_error());
	echo '<div class="messagetouser">Shift deleted successfully.</div>';
}
else if (isset($_GET['add2'])) {
	$entereddate = $_POST['theMonth'] . "-" . $_POST['theDay'] . "-" . $_POST['theYear'];

	// PREVENT SHIFTS IN FUTURES DATES
	if ($entereddate > date("m-d-Y")) { echo "<br />The shift's date cannot be later than today.<br /><br /><a href='javascript:history.go(-1)'>Go back</a>"; exit; }
	// PREVENT LATER START TIME THAN END TIME
	if ($_POST['StartTime'] >= $_POST['EndTime']) { echo "<br />The shift's end time must be later than the start time.<br /><br /><a href='javascript:history.go(-1)'>Go back</a>"; exit; }

	if ($_SESSION['AccountType'] == 'Administrator') $theID = $_POST['theID'];
	else $theID = $_SESSION['EmployeeID'];
	
	// CHECK FOR AND PREVENT DUPLICATE SHIFTS
	$checkDupe = mysql_query("SELECT * FROM ".$table_timesheet." WHERE EmployeeID = '".$theID."' AND StartTime = '".$_POST['StartTime']."' AND EndTime = '".$_POST['EndTime']."' AND Date = '".$entereddate."'");
		while($row8 = mysql_fetch_array( $checkDupe )) {
			echo "<br />Duplicate shift already entered for this date.<br /><br /><a href='javascript:history.go(-1)'>Go back</a>"; exit;
		}	
	// ----------- //
	
	if ($theID == 0) 
	{
		echo '<div align="center" style="color:red;">Error occurred! Please <a href="index.php?logout=yes">login</a> again.</div>';
		exit;
	}
	$totalhours = ($_POST['EndTime'] - $_POST['StartTime']) / 4;
	
	if (!isset($_SESSION['PayRate']) OR $_SESSION['PayRate'] == "") {
			$getPayrate = mysql_query("SELECT PayRatePerHour FROM ".$table_employees." WHERE EmployeeID = '".$theID."'");
		while($row8 = mysql_fetch_array( $getPayrate )) {
			$payrate = $row8['PayRatePerHour'];
		}
	}
	else {
		$payrate = $_SESSION['PayRate'];
	}
	
	//echo $_POST['StartTime']."<br />";
	//echo $_POST['EndTime'];
	//echo "_SESSION: ".$_SESSION['PayRate'];
	//echo "payrate: ".$payrate;
	//echo "theID: ".$theID;
	
       mysql_query("
       	       INSERT INTO ".$table_timesheet." (EmployeeID,Date,StartTime,EndTime,TotalHours,PayRate,Description) 
       	       VALUES ('".$theID."','".$entereddate."','".$_POST['StartTime']."','".$_POST['EndTime']."','".$totalhours."','".$payrate."','".$_POST['Description']."')
       	   ") or die(mysql_error());
       echo '<div class="messagetouser">Shift added successfully!</div>';
}





else if (isset($_GET['add'])) {
?>
<h2>Add Shift</h2>
<br />
<form action="home.php?add2" method="post">
<table align='center'>
<?php if ($_SESSION['AccountType'] == 'Administrator') { ?>
<tr><td valign='top'>Name:</td><td>
	<?php
	$getNames = mysql_query("SELECT * FROM ".$table_employees." WHERE Archive <> 'Y' ORDER BY FirstName asc"); ?>
	<select name="theID" class="input">
	<option value="">-- select --</option>
		<?php while($row8 = mysql_fetch_array( $getNames )) { 
			$temp1 = $row8['FirstName'] . " " . $row8['LastName'];
			?> <option value="<?php echo $row8['EmployeeID']; ?>"><?php echo $temp1; ?></option>
		<?php } ?>
	</select></td></tr>
<?php } else { ?>
	<tr><td valign='top'><u>Name:</td><td><?php echo $_SESSION['FirstName']." ".$_SESSION['LastName']; ?></td></tr>
<?php } ?>
<tr><td valign='top'><u>Date:</td><td>

    <select name="theMonth" class="input">
    	<option value="01" <?php if (date("m") == "01") echo 'selected="selected"'; ?>>January</option>
    	<option value="02" <?php if (date("m") == "02") echo 'selected="selected"'; ?>>February</option>
    	<option value="03" <?php if (date("m") == "03") echo 'selected="selected"'; ?>>March</option>
    	<option value="04" <?php if (date("m") == "04") echo 'selected="selected"'; ?>>April</option>
    	<option value="05" <?php if (date("m") == "05") echo 'selected="selected"'; ?>>May</option>
    	<option value="06" <?php if (date("m") == "06") echo 'selected="selected"'; ?>>June</option>
    	<option value="07" <?php if (date("m") == "07") echo 'selected="selected"'; ?>>July</option>
    	<option value="08" <?php if (date("m") == "08") echo 'selected="selected"'; ?>>August</option>
    	<option value="09" <?php if (date("m") == "09") echo 'selected="selected"'; ?>>September</option>
    	<option value="10" <?php if (date("m") == "10") echo 'selected="selected"'; ?>>October</option>
    	<option value="11" <?php if (date("m") == "11") echo 'selected="selected"'; ?>>November</option>
    	<option value="12" <?php if (date("m") == "12") echo 'selected="selected"'; ?>>December</option>
    </select>
    
    <select name="theDay" class="input">
    <?php for ($x = 1; $x <= 31; $x++) { ?>
    	    <option <?php if (date("d") == sprintf("%02d", $x)) echo 'selected="selected"'; ?>><?php echo sprintf("%02d", $x); ?></option>
    <?php }?>
    </select>
    
    <select name="theYear" class="input">
        <option><?php echo (date("Y") - 1); ?></option>
        <option selected="selected"><?php echo date("Y"); ?></option>
    </select>

</td></tr>
<tr><td valign='top'><u>Start Time:</td><td>
    <select name="StartTime" class="input">
	    <?php
	    $hour = "12";
	    $minutes = "00";
	    $ampm = "AM";
	    for ($x = 1; $x <= 96; $x++) {
		    if ($x==33) $q='selected="selected"'; else $q="";
		    echo '<option value="'.$x.'" '.$q.'>'.$hour.':'.$minutes.' '.$ampm.'</option>';
		    if ($x % 4 == 0) $hour++;
		    if ($x == 48) $ampm = "PM";
		    if ($hour == 13) $hour = "1";
		    $minutes = $minutes + 15;
		    if ($minutes == 60) $minutes = "00";
	    }
	    ?>
    </select>
    </td></tr>
    <tr><td valign='top'><u>End Time:</td><td>
    <select name="EndTime" class="input">
	    <?php
	    $hour = "12";
	    $minutes = "00";
	    $ampm = "AM";
	    for ($x = 1; $x <= 96; $x++) {
	    	    if ($x==35) $q='selected="selected"'; else $q="";
		    echo '<option value="'.$x.'" '.$q.'>'.$hour.':'.$minutes.' '.$ampm.'</option>';
		    if ($x % 4 == 0) $hour++;
		    if ($x == 48) $ampm = "PM";
		    if ($hour == 13) $hour = "1";
		    $minutes = $minutes + 15;
		    if ($minutes == 60) $minutes = "00";
	    }
	    ?>
    </select>
    </td></tr>
<tr><td valign='top'><u>Notes:</td><td><input type="text" name="Description" size="27" class="input"></td></tr>
<tr><td></td><td><input type="submit" value="Go" class="button" /></td></tr>
</table>
</form>	
	
	
<?php	
}
else if (isset($_GET['pending'])) { 
	
	if ($_SESSION['AccountType'] != 'Administrator') { echo "<br />Hey, where did you come from? You are not logged in with proper privileges."; exit; }
	?>
	
<h2>Pending Shifts to be Paid</h2>

<?php
if (!isset($_GET['employee'])) echo '<br /><span style="color:red;">Click the name of an employee to set shifts as paid.</span><br /><br />';
?>

<form action="#" method="post">
	<input type="hidden" name="goPending">
	
		<table cellspacing="0" class="nicetable" align="center">
		<tr><th>Date</th><th>Name</th><th style="white-space:nowrap">Start Time</th>
		<th style="white-space:nowrap">End Time</th><th>Hours</th><th style="white-space:nowrap">Rate</th>
		<th>Fed Tax</th><th>St Tax</th><th>FICA</th><th>Med</th><th>Notes</th><th></th>
		
		<?php
		if (isset($_GET['employee'])) echo '<th>All <input type="checkbox" onClick="toggle(this)" /></th>';
		?>
		</tr>
		<?php
		if (isset($_GET['employee']))
			$sql = "SELECT * FROM ".$table_timesheet." WHERE Paid = '' AND EmployeeID = '".$_GET['employee']."' ORDER BY Date desc";
		else 	$sql = "SELECT * FROM ".$table_timesheet." WHERE Paid = '' ORDER BY Date desc";
		
		$getShifts = mysql_query($sql) or die(mysql_error());  
		$q = 0;$addup = 0;$addupFed = 0;$addupState = 0;
		
		  while($row = mysql_fetch_array( $getShifts )) {
		  	  $getEntries2 = mysql_query("SELECT * FROM ".$table_employees." WHERE EmployeeID = '".$row['EmployeeID']."'") or die(mysql_error());   while($row2 = mysql_fetch_array( $getEntries2 )) { $name = $row2['FirstName'] . " " . $row2['LastName']; }
			  $q++; if ($q % 2) $bg = "#FFFFFF"; else $bg = "#DDDDDD"; 
			  
			echo '
			<tr bgcolor='.$bg.'>
			<td nowrap="nowrap">'.$row['Date'].'</td>
			<td nowrap="nowrap"><a href="home.php?pending&employee='.$row['EmployeeID'].'">'.$name.'</a></td>
			<td>'.$times[$row['StartTime']].'</td>
			<td>'.$times[$row['EndTime']].'</td>
			<td>'.$row['TotalHours'].'</td>
			<td>'.$row['PayRate'].'</td>';
			
			//Calculate Taxes (tax rates set in variables.php)
			echo '<td>';
			if ($global_tax1 == 0 && isset($_GET['employee'])) echo '<input type="text" name="deduction1[]" size="4" style="background-color:#fafafa;border:1px solid #CCC">'; 
			else { $deduction1=($row['TotalHours']*$row['PayRate']*$global_tax1)/100; echo ''.money_format('%.2n', $deduction1).''; }
			echo '</td>';
			
			echo '<td>';
			if ($global_tax2 == 0 && isset($_GET['employee'])) echo '<input type="text" name="deduction2[]" size="4" style="background-color:#fafafa;border:1px solid #CCC">'; 
			else { $deduction2=($row['TotalHours']*$row['PayRate']*$global_tax2)/100; echo ''.money_format('%.2n', $deduction2).''; }
			echo '</td>';
			
			echo '<td>';
			if ($global_tax3 == 0 && isset($_GET['employee'])) echo '<input type="text" name="deduction3[]" size="4" style="background-color:#fafafa;border:1px solid #CCC">'; 
			else { $deduction3=($row['TotalHours']*$row['PayRate']*$global_tax3)/100; echo ''.money_format('%.2n', $deduction3).''; }
			echo '</td>';
			
			echo '<td>';
			if ($global_tax4 == 0 && isset($_GET['employee'])) echo '<input type="text" name="deduction4[]" size="4" style="background-color:#fafafa;border:1px solid #CCC">'; 
			else { $deduction4=($row['TotalHours']*$row['PayRate']*$global_tax4)/100; echo ''.money_format('%.2n', $deduction4).''; }
			echo '</td>';
			
		if ($row['Description'] != "") { echo '
			<td align="center" nowrap="nowrap"><a href="#" class="tooltip"><span><img class="callout" src="images/callout.gif" />'.htmlspecialchars($row['Description']).'</span>View</a></td>';
		} else echo '<td></td>';
		?>
		<td><a href="home.php?delete=<?php echo $row['EntryID']; ?>"  class="tooltip" onclick="return confirm('Sure you want to delete this timesheet submission?')"><span><img class="callout" src="images/callout.gif" />Delete</span>X</a></td>
		<?php
		
		
			if (isset($_GET['employee'])) echo '<td><input type="checkbox" name="check_list[]" value='.$row['EntryID'].'></td>';
			?>
		</tr>
		<?php
		$deduct1total += $deduction1;
		$deduct2total += $deduction2;
		$deduct3total += $deduction3;
		$deduct4total += $deduction4;
		$addup += $row['TotalHours'];
		$rate = $row['PayRate'];
		
		echo '<input type="hidden" name="Tax1['.($q-1).']" value="'.$deduction1.'">';
		echo '<input type="hidden" name="Tax2['.($q-1).']" value="'.$deduction2.'">';
		echo '<input type="hidden" name="Tax3['.($q-1).']" value="'.$deduction3.'">';
		echo '<input type="hidden" name="Tax4['.($q-1).']" value="'.$deduction4.'">';
		}
		$netpay = $addup*$rate;
		?>
		
		
		<?php
		$sql3 = mysql_query("SELECT * FROM ".$table_employees." WHERE EmployeeID = '".$_GET['employee']."'");
		  	  while($row4 = mysql_fetch_array( $sql3 )) { $insur_deduct = $row4['MonthlyInsuranceDeduct']; }
		  	  
		if ($insur_deduct != "" AND $insur_deduct != "0.00") { echo "<tr><td colspan='9'></td><td colspan='3' align='right'>Deduct Monthly Insurance? -".$insur_deduct."</td><td><input type='checkbox' name='doInsurance'></td></tr>"; }
		?>
		
		<?php if ($addup > 40 AND isset($_GET['employee'])) { 
			$overtimepay = ($addup-40)*1.5*$rate;
			$netpay = 40*$rate;
			$netpay += $overtimepay;
			$deduct1total+=($overtimepay*$global_tax1)/100;
			$deduct2total+=($overtimepay*$global_tax2)/100;
			$deduct3total+=($overtimepay*$global_tax3)/100;
			$deduct4total+=($overtimepay*$global_tax4)/100;
			?>
		<tr><td colspan="3"></td><td><strong>Overtime</strong>:</td>
		<td><?php echo ($addup-40); ?></td>
		<td><?php echo money_format('%.2n', $overtimepay); ?></td>
		<td>$<?php echo money_format('%.2n', ($overtimepay*$global_tax1)/100); ?></td>
		<td>$<?php echo money_format('%.2n', ($overtimepay*$global_tax2)/100); ?></td>
		<td>$<?php echo money_format('%.2n', ($overtimepay*$global_tax3)/100); ?></td>
		<td>$<?php echo money_format('%.2n', ($overtimepay*$global_tax4)/100); ?></td>
		<td colspan='2'></td></tr>
		<?php } ?>
		
		<tr><td colspan="3"></td><td>Totals:</td><td><?php echo $addup; ?></td><td>$<?php echo money_format('%.2n', $netpay); ?></td>
		<td>$<?php echo money_format('%.2n', $deduct1total); ?></td><td>$<?php echo money_format('%.2n', $deduct2total); ?></td>
		<td>$<?php echo money_format('%.2n', $deduct3total); ?></td><td>$<?php echo money_format('%.2n', $deduct4total); ?></td>
		<td colspan='3' align='right'>
		<?php
		if (isset($_GET['employee'])) { echo '<input type="submit" name="submit" value="Set as Paid" class="button" style="font-size:11px;" />'; 
		

		}
		?>
		</td></tr>
		<?php
		//if (isset($_GET['employee'])) echo '<tr><td colspan="4"></td><td colspan="4">Total after taxes: $'.money_format('%.2n',round($netpay-$addupFed-$addupState,2)).'</td><td colspan="2"></td></tr>';
		?>
		</table>
</form>

<?php 
}

else if (isset($_GET['archive'])) {

?>
<h2>Shift History</h2>
<?php
 if ($_SESSION['AccountType'] != 'Administrator') { ?>
<table cellspacing="0" class="nicetable" align="center">
<tr><th>Date</th><th>Start Time</th><th>End Time</th><th>Total Hours</th><th>Notes</th><th>Paid</th></tr>
<?php
$sql = "SELECT * FROM ".$table_timesheet." WHERE EmployeeID = '".$_SESSION['EmployeeID']."' ORDER BY EntryID desc";		
$getEntries = mysql_query($sql) or die(mysql_error());  
$q = 0;

while($row = mysql_fetch_array( $getEntries )) {
  	  $q++; if ($q % 2) $bg = "#FFFFFF"; else $bg = "#DDDDDD";
echo '
<tr bgcolor='.$bg.'>
<td nowrap="nowrap">'.$row['Date'].'</td>
<td>'.$times[$row['StartTime']].'</td>
<td>'.$times[$row['EndTime']].'</td>
<td>'.$row['TotalHours'].'</td>';
		if ($row['Description'] != "") { echo '
			<td align="center" title="'.htmlspecialchars($row['Description']).'" nowrap="nowrap"><a href="#" class="tooltip"><span><img class="callout" src="images/callout.gif" />'.htmlspecialchars($row['Description']).'</span>View</a></td>';
		} else echo '<td></td>';
		if ($row['Paid'] == "y") { echo '
			<td align="center"><img src="images/checkmark-transparent.png" width="20" alt="Yes" title="Yes" /></div></td>';
		} else echo '<td></td>';
?>
</tr>
<?php
}
?>
</table>
 <?php }
	else {
		?>
		<table cellspacing="0" class="nicetable" align="center">
		<tr><th>Date</th><th>Name</th><th>Start Time</th><th>End Time</th><th>Total Hours</th><th>Notes</th><th>Paid</th></tr>
		<?php
		if (isset($_GET['employee']))
			$sql = "SELECT * FROM ".$table_timesheet." WHERE EmployeeID = '".$_GET['employee']."' ORDER BY EntryID desc";
		else 	$sql = "SELECT * FROM ".$table_timesheet." ORDER BY EntryID desc";

		$getEntries = mysql_query($sql) or die(mysql_error());  
		$q = 0;
		
		  while($row = mysql_fetch_array( $getEntries )) {
		  	  $getEntries2 = mysql_query("SELECT * FROM ".$table_employees." WHERE EmployeeID = '".$row['EmployeeID']."'") or die(mysql_error());   while($row2 = mysql_fetch_array( $getEntries2 )) { $name = $row2['FirstName'] . " " . $row2['LastName']; }
			  $q++; if ($q % 2) $bg = "#FFFFFF"; else $bg = "#DDDDDD";
			echo '
			<tr bgcolor='.$bg.'>
			<td nowrap="nowrap">'.$row['Date'].'</td>
			<td nowrap="nowrap"><a href="home.php?archive&employee='.$row['EmployeeID'].'">'.$name.'</a></td>
			<td>'.$times[$row['StartTime']].'</td>
			<td>'.$times[$row['EndTime']].'</td>
			<td>'.$row['TotalHours'].'</td>';
		if ($row['Description'] != "") { echo '
			<td align="center" nowrap="nowrap"><a href="#" class="tooltip"><span><img class="callout" src="images/callout.gif" />'.htmlspecialchars($row['Description']).'</span>View</a></td>';
		} else echo '<td></td>';
		if ($row['Paid'] == "y") { echo '
			<td align="center"><img src="images/checkmark-transparent.png" width="20" alt="Yes" title="Yes" /></div></td>';
		} else echo '<td></td>';
			?>
		</tr>
		<?php
		}
		?>
		</table>
<?php	
	}
}

else if (isset($_GET['paychecks'])) {
?>
<h2>Paychecks</h2>
	<table cellspacing="0" class="nicetable" align="center">
		<tr><th>Date</th><th>Name</th><th>Total</th><th>Total Hours</th><th>Fed Tax</th><th>St Tax</th><th>FICA</th><th>Med</th><th>Insur</th><th>Otime</th><th>Check #</th></tr>
		<?php
		if (isset($_GET['employee']))
			$sql = "SELECT * FROM ".$table_paychecks." WHERE EmployeeID = '".$_GET['employee']."' ORDER BY PaycheckID desc";
		else 	$sql = "SELECT * FROM ".$table_paychecks." ORDER BY PaycheckID desc";

		$getEntries = mysql_query($sql) or die(mysql_error());  
		$q = 0;
		
		  while($row = mysql_fetch_array( $getEntries )) {
		  	  $getEntries2 = mysql_query("SELECT * FROM ".$table_employees." WHERE EmployeeID = '".$row['EmployeeID']."'") or die(mysql_error());   while($row2 = mysql_fetch_array( $getEntries2 )) { 
		  	  $name = $row2['FirstName'] . " " . $row2['LastName']; 
		  }
			$q++; if ($q % 2) $bg = "#FFFFFF"; else $bg = "#DDDDDD";
			
			if ($row['OvertimePay'] != "") $otime = $row['OvertimePay']; else $otime = "0.00";
			
			echo '
			<tr bgcolor='.$bg.'>
			<td nowrap="nowrap">'.$row['Date'].'</td>
			<td nowrap="nowrap"><a href="home.php?paychecks&employee='.$row['EmployeeID'].'">'.$name.'</a></td>
			<td>'.$row['TotalAfterTaxes'].'</td>
			<td>'.$row['TotalHours'].'</td>
			<td>'.$row['FedTax'].'</td>
			<td>'.$row['StTax'].'</td>
			<td>'.$row['FICA'].'</td>
			<td>'.$row['Med'].'</td>
			<td>'.$row['MonthlyInsuranceDeduct'].'</td>
			<td>'.$otime.'</td>
			<td>'.$row['CheckNum'].'</td>';
			?>
		</tr>
		<?php
		}
		?>
	</table>
	
	<?php
		$thisyear = date("Y");
		$lastyear = $thisyear-1;
		$thismonth = date("m");
		$lastmonth = sprintf("%02d", $thismonth-1);
		$grandhours = 0;$grandearnings=0;
		?>
	

		
	<h2>Paid Earnings by Quarter</h2>
	<?php if (isset($_GET['employee'])) { ?>
		
	<table cellspacing="0" class="nicetable" align="center">
	<tr><td colspan='10' style='font-size:18px;'><?php echo $thisyear; ?></td></tr>
		<tr><td><u>Name</td><td><u>Quarter</td><td><u>Hours Worked</td><td><u>Earnings</td>
		<td><u>FedTax</td><td><u>StTax</td><td><u>FICA</td><td><u>Med</td><td><u>Insur</td><td><u>Net pay</td>
		</tr>
		<?php


		for ($i = 4;$i > 0;$i--) {
			
			
			
		$sql2 = "SELECT * FROM ".$table_paychecks." WHERE EmployeeID = '".$_GET['employee']."' AND (Date LIKE '".sprintf("%02d", (($i*3)-0))."%' OR Date LIKE '".sprintf("%02d", (($i*3)-1))."%' OR Date LIKE '".sprintf("%02d", (($i*3)-2))."%') AND Date LIKE '%".$thisyear."'";
		$getEntries2 = mysql_query($sql2) or die(mysql_error());  
		
		$totalhours = 0;$totalpay=0;$totalgrosspay=0;$insur=0;
		  while($row2 = mysql_fetch_array( $getEntries2 )) {
		  	  $totalhours += $row2['TotalHours'];
		  	  $totalpay += ($row2['TotalAfterTaxes']);	  
		  	  $totalgrosspay += ($row2['PayRatePerHour']*$row2['TotalHours']);
		  	  $tax1+=$row2['FedTax'];
		  	  $tax2+=$row2['StTax'];
		  	  $tax3+=$row2['FICA'];
		  	  $tax4+=$row2['Med'];
		  	  $insur+=$row2['MonthlyInsuranceDeduct'];
		  }
		  $a = DateTime::createFromFormat('!m', $i);
		  $b = $a->format('F'); // March
		  echo '<tr><td>'.$name.'</td><td>'.$i.'</td><td>'.$totalhours.'</td><td>'.$totalgrosspay.'</td>
			<td>'.$tax1.'</td>
			<td>'.$tax2.'</td>
			<td>'.$tax3.'</td>
			<td>'.$tax4.'</td>
			<td>'.$insur.'</td>
			<td>$'.$totalpay.'</td>
			</tr>';
		  $grandhours += $totalhours;
		  $grandearnings += $totalpay;
		  $grandgross += $totalgrosspay;
		  $grandtax1 += $tax1;
		  $grandtax2 += $tax2;
		  $grandtax3 += $tax3;
		  $grandtax4 += $tax4;
		  $grandinsur += $insur;
		}
		echo '<tr><td></td><td align="right"><i>Totals</i>:</td><td>'.$grandhours.'</td>
		<td>'.$grandgross.'</td>
			<td>'.$grandtax1.'</td>
			<td>'.$grandtax2.'</td>
			<td>'.$grandtax3.'</td>
			<td>'.$grandtax4.'</td>
			<td>'.$grandinsur.'</td>
		<td>$'.money_format('%.2n', $grandearnings).'</td></tr>
		</table>';
		
		  ?>
	</table>
	
	<table cellspacing="0" class="nicetable" align="center">
	<tr><td colspan='10' style='font-size:18px;'><?php echo $lastyear; ?></td></tr>
		<tr><td><u>Name</td><td><u>Quarter</td><td><u>Hours Worked</td><td><u>Earnings</td>
		<td><u>FedTax</td><td><u>StTax</td><td><u>FICA</td><td><u>Med</td><td><u>Net pay</td>
		</tr>
		<?php
			  $tax1="";$grandtax1="";
		  	  $tax2="";$grandtax2="";
		  	  $tax3="";$grandtax3="";
		  	  $tax4="";$grandtax4="";
		  	  $grandearnings="";$grandhours="";$insur="";$grandgross="";
		  	  
		for ($i = 4;$i > 0;$i--) {
		$sql2 = "SELECT * FROM ".$table_paychecks." WHERE EmployeeID = '".$_GET['employee']."' AND (Date LIKE '".sprintf("%02d", (($i*3)-0))."%' OR Date LIKE '".sprintf("%02d", (($i*3)-1))."%' OR Date LIKE '".sprintf("%02d", (($i*3)-2))."%') AND Date LIKE '%".$lastyear."'";
		$getEntries2 = mysql_query($sql2) or die(mysql_error());  
		$totalhours = 0;$totalpay=0;$totalgrosspay=0;
		  while($row2 = mysql_fetch_array( $getEntries2 )) {
		  	  $totalhours += $row2['TotalHours'];
		  	  $totalpay += ($row2['TotalAfterTaxes']);	
		  	  $totalgrosspay += ($row2['PayRatePerHour']*$row2['TotalHours']);	
		  	  $tax1+=$row2['FedTax'];
		  	  $tax2+=$row2['StTax'];
		  	  $tax3+=$row2['FICA'];
		  	  $tax4+=$row2['Med'];
		  }
		  $a = DateTime::createFromFormat('!m', $i);
		  $b = $a->format('F'); // March
		  echo '<tr><td>'.$name.'</td><td>'.$i.'</td><td>'.$totalhours.'</td><td>'.$totalgrosspay.'</td>
		  	<td>'.$tax1.'</td>
			<td>'.$tax2.'</td>
			<td>'.$tax3.'</td>
			<td>'.$tax4.'</td>
			
			<td>$'.money_format('%.2n', $totalpay).'</td></tr>';
		  $grandhours += $totalhours;
		  $grandearnings += $totalpay;
		  $grandgross += $totalgrosspay;
		  $grandtax1 += $tax1;
		  $grandtax2 += $tax2;
		  $grandtax3 += $tax3;
		  $grandtax4 += $tax4;
		}
		echo '<tr><td></td><td align="right"><i>Totals</i>:</td><td>'.$grandhours.'</td>
		<td>'.$grandgross.'</td>
			<td>'.$grandtax1.'</td>
			<td>'.$grandtax2.'</td>
			<td>'.$grandtax3.'</td>
			<td>'.$grandtax4.'</td>
			<td>$'.money_format('%.2n', $grandearnings).'</td></tr>
		</table>';
		
		  ?>
	</table>
	
	<?php } else { ?>
		
	<table cellspacing="0" class="nicetable" align="center">
	<tr><td colspan='5' style='font-size:18px;'><?php echo $thisyear; ?></td></tr>
		<tr><td><u>Quarter</td><td><u>Hours Worked</td><td><u>Gross</td><td><u>Net</td></tr>
		
		<?php
			  $tax1="";$grandtax1="";
		  	  $tax2="";$grandtax2="";
		  	  $tax3="";$grandtax3="";
		  	  $tax4="";$grandtax4="";
		  	  $grandearnings="";$grandgross="";$grandhours="";

		for ($i = 4;$i > 0;$i--) {
		$sql2 = "SELECT * FROM ".$table_paychecks." WHERE (Date LIKE '".sprintf("%02d", (($i*3)-0))."%' OR Date LIKE '".sprintf("%02d", (($i*3)-1))."%' OR Date LIKE '".sprintf("%02d", (($i*3)-2))."%') AND Date LIKE '%".$thisyear."'";
		$getEntries2 = mysql_query($sql2) or die(mysql_error());  
		
		$totalhours = 0;$totalpay=0;$totalgrosspay=0;$insur=0;
		  while($row2 = mysql_fetch_array( $getEntries2 )) {
		  	  $totalhours += $row2['TotalHours'];
		  	  $totalpay += ($row2['TotalAfterTaxes']);
		  	  $totalgrosspay += ($row2['PayRatePerHour']*$row2['TotalHours']);
		  	  $tax1+=$row2['FedTax'];
		  	  $tax2+=$row2['StTax'];
		  	  $tax3+=$row2['FICA'];
		  	  $tax4+=$row2['Med'];
		  }
		  $a = DateTime::createFromFormat('!m', $i);
		  $b = $a->format('F'); // March
		  echo '<tr><td>'.$i.'</td><td>'.$totalhours.'</td><td>$'.money_format('%.2n', $totalgrosspay).'</td><td>$'.money_format('%.2n', $totalpay).'</td></tr>';
		  $grandhours += $totalhours;
		  $grandearnings += $totalpay;
		  $grandgross += $totalgrosspay;
		}
		echo '<tr><td align="right"><i>Totals</i>:</td><td>'.$grandhours.'</td>
			<td>$'.money_format('%.2n', $grandgross).'</td>
			<td>$'.money_format('%.2n', $grandearnings).'</td>
			</tr>
		</table>';
		
		  ?>
	</table>
	
	<table cellspacing="0" class="nicetable" align="center">
	<tr><td colspan='5' style='font-size:18px;'><?php echo $lastyear; ?></td></tr>
		<tr><td><u>Quarter</td><td><u>Hours Worked</td><td><u>Gross</td><td>Net</td></tr>
		<?php
			  $tax1="";$grandtax1="";
		  	  $tax2="";$grandtax2="";
		  	  $tax3="";$grandtax3="";
		  	  $tax4="";$grandtax4="";
		  	  $grandearnings=0;$grandhours=0;$grandgross=0;$grandearnings=0;

		for ($i = 4;$i > 0;$i--) {
		$sql2 = "SELECT * FROM ".$table_paychecks." WHERE (Date LIKE '".sprintf("%02d", (($i*3)-0))."%' OR Date LIKE '".sprintf("%02d", (($i*3)-1))."%' OR Date LIKE '".sprintf("%02d", (($i*3)-2))."%') AND Date LIKE '%".$lastyear."'";
		$getEntries2 = mysql_query($sql2) or die(mysql_error());  
		$totalhours = 0;$totalpay=0;$totalgrosspay=0;$insur=0;
		  while($row2 = mysql_fetch_array( $getEntries2 )) {
		  	  $totalhours += $row2['TotalHours'];
		  	  $totalpay += ($row2['TotalAfterTaxes']);
		  	  $totalgrosspay += ($row2['PayRatePerHour']*$row2['TotalHours']);
		  	  $tax1+=$row2['FedTax'];
		  	  $tax2+=$row2['StTax'];
		  	  $tax3+=$row2['FICA'];
		  	  $tax4+=$row2['Med'];
		  }
		  $a = DateTime::createFromFormat('!m', $i);
		  $b = $a->format('F'); // March
		  echo '<tr><td>'.$i.'</td><td>'.$totalhours.'</td><td>$'.money_format('%.2n', $totalgrosspay).'</td><td>$'.money_format('%.2n', $totalpay).'</td></tr>';
		  $grandhours += $totalhours;
		  $grandearnings += $totalpay;
		  $grandgross += $totalgrosspay;
		  $grandtax1 += $tax1;
		  $grandtax2 += $tax2;
		  $grandtax3 += $tax3;
		  $grandtax4 += $tax4;
		}
		echo '<tr><td align="right"><i>Totals</i>:</td><td>'.$grandhours.'</td>
			<td>$'.money_format('%.2n', $grandgross).'</td>
			<td>$'.money_format('%.2n', $grandearnings).'</td>
			</tr>
		</table>';
		
		  ?>
	</table>
		<?php } ?>
		
		
	<h2>Paid Earnings by Month</h2>
	<?php if (isset($_GET['employee'])) { ?>
	<table cellspacing="0" class="nicetable" align="center">
	<tr><td colspan='10' style='font-size:18px;'><?php echo $thisyear; ?></td></tr>
		<tr><td><u>Name</td><td><u>Month</td><td><u>Hours Worked</td><td><u>Earnings</td>
		<td><u>FedTax</td><td><u>StTax</td><td><u>FICA</td><td><u>Med</td><td><u>Insur</td><td><u>Net pay</td>
		</tr>
		<?php
		  	 $grandtax1="";
		  	  $tax2="";$grandtax2="";
		  	  $tax3="";$grandtax3="";
		  	  $tax4="";$grandtax4="";
		  	  $grandinsur=0;$grandearnings=0;$grandhours=0;$grandgross=0;$grandearnings=0;

		for ($i = $thismonth;$i > 0;$i--) {
			 $tax1="";
			  $tax2="";
			   $tax3="";
			    $tax4="";
			    $insur="";
			    
		$sql2 = "SELECT * FROM ".$table_paychecks." WHERE EmployeeID = '".$_GET['employee']."' AND Date LIKE '".sprintf("%02d", $i)."%' AND Date LIKE '%".$thisyear."'";
		$getEntries2 = mysql_query($sql2) or die(mysql_error());  
		$totalhours = 0;$totalpay=0;$totalgrosspay=0;
		  while($row2 = mysql_fetch_array( $getEntries2 )) {
		  	  $totalhours += $row2['TotalHours'];
		  	  $totalpay += ($row2['TotalAfterTaxes']);
		  	  $totalgrosspay += ($row2['PayRatePerHour']*$row2['TotalHours']);
		  	  $tax1+=$row2['FedTax'];
		  	  $tax2+=$row2['StTax'];
		  	  $tax3+=$row2['FICA'];
		  	  $tax4+=$row2['Med'];
		  	  $insur+=$row2['MonthlyInsuranceDeduct'];
		  }
		  $a = DateTime::createFromFormat('!m', $i);
		  $b = $a->format('F'); // March
		  echo '<tr><td>'.$name.'</td><td>'.$b.'</td><td>'.$totalhours.'</td><td>'.$totalgrosspay.'</td>
		  <td>'.$tax1.'</td>
			<td>'.$tax2.'</td>
			<td>'.$tax3.'</td>
			<td>'.$tax4.'</td>
			<td>'.$insur.'</td>
			<td>$'.money_format('%.2n', $totalpay).'</td></tr>';
		  $grandhours += $totalhours;
		  $grandearnings += $totalpay;
		  $grandgross += $totalgrosspay;
		   $grandtax1 += $tax1;
		  $grandtax2 += $tax2;
		  $grandtax3 += $tax3;
		  $grandtax4 += $tax4;
		  $grandinsur += $insur;
		}
		echo '<tr><td></td><td align="right"><i>Totals</i>:</td><td>'.$grandhours.'</td><td>'.$grandgross.'</td>
		<td>'.$grandtax1.'</td>
			<td>'.$grandtax2.'</td>
			<td>'.$grandtax3.'</td>
			<td>'.$grandtax4.'</td>
			<td>'.$grandinsur.'</td>
			<td>$'.money_format('%.2n', $grandearnings).'</td></tr>
		</table><table cellspacing="0" class="nicetable" align="center">
		<tr><td colspan="5" style="font-size:18px;">'.$lastyear.'</td></tr><tr><td><u>Name</td><td><u>Month</td><td><u>Hours Worked</td><td><u>Earnings</td></tr>';
		
		$grandhours = 0;$grandearnings=0;
		// Last year
		for ($i = 12;$i > 0;$i--) {
		$sql2 = "SELECT * FROM ".$table_paychecks." WHERE EmployeeID = '".$_GET['employee']."' AND Date LIKE '".sprintf("%02d", $i)."%' AND Date LIKE '%".$lastyear."'";
		$getEntries2 = mysql_query($sql2) or die(mysql_error());  
		$totalhours = 0;$totalpay=0;
		  while($row2 = mysql_fetch_array( $getEntries2 )) {
		  	  $totalhours += $row2['TotalHours'];
		  	  $totalpay += ($row2['TotalAfterTaxes']);	
		  	  $totalgrosspay += ($row2['PayRatePerHour']*$row2['TotalHours']);
		  	  $tax1+=$row2['FedTax'];
		  	  $tax2+=$row2['StTax'];
		  	  $tax3+=$row2['FICA'];
		  	  $tax4+=$row2['Med'];
		  }
		  $a = DateTime::createFromFormat('!m', $i);
		  $b = $a->format('F'); // March
		  echo '<tr><td>'.$name.'</td><td>'.$b.'</td><td>'.$totalhours.'</td><td>$'.money_format('%.2n', $totalpay).'</td></tr>';
		  $grandhours += $totalhours;
		  $grandearnings += $totalpay;
		}
		  echo '<tr><td></td><td align="right"><i>Totals</i>:</td><td>'.$grandhours.'</td><td>$'.money_format('%.2n', $grandearnings).'</td></tr>';
		  ?>
	</table>
	
	<?php } 
	// No employee selected, show earnings of all employees
	else {
		
		$thisyear = date("Y");
		$lastyear = $thisyear-1;
		$thismonth = date("m");
		$lastmonth = sprintf("%02d", $thismonth-1);
		?>
		
	<table cellspacing="0" class="nicetable" align="center">
	<tr><td colspan='4' style='font-size:18px;'><?php echo $thisyear; ?></td></tr>
		<tr><td><u>Month</td><td><u>Hours Worked</td><td><u>Gross</td><td><u>Net</td>
		</tr>
		<?php
		
		  	  $tax1="";$grandtax1="";
		  	  $tax2="";$grandtax2="";
		  	  $tax3="";$grandtax3="";
		  	  $tax4="";$grandtax4="";
		  	  $grandinsur=0;$grandearnings=0;$grandhours=0;$grandgross=0;$grandearnings=0;
		
		for ($i = $thismonth;$i > 0;$i--) {
		$sql2 = "SELECT * FROM ".$table_paychecks." WHERE Date LIKE '".sprintf("%02d", $i)."%' AND Date LIKE '%".$thisyear."'";
		$getEntries2 = mysql_query($sql2) or die(mysql_error());  
		$totalhours = 0;$totalpay=0;$totalgrosspay=0;
		  while($row2 = mysql_fetch_array( $getEntries2 )) {
		  	  $totalhours += $row2['TotalHours'];
		  	  $totalpay += ($row2['TotalAfterTaxes']);  
		  	  $totalgrosspay += ($row2['PayRatePerHour']*$row2['TotalHours']);
		  	  $tax1+=$row2['FedTax'];
		  	  $tax2+=$row2['StTax'];
		  	  $tax3+=$row2['FICA'];
		  	  $tax4+=$row2['Med'];
		  }
		  $a = DateTime::createFromFormat('!m', $i);
		  $b = $a->format('F'); // i.e. March
		  echo '<tr><td>'.$b.'</td><td>'.$totalhours.'</td><td>$'.money_format('%.2n', $totalgrosspay).'</td><td>$'.money_format('%.2n', $totalpay).'</td></tr>';
		  $grandhours += $totalhours;
		  $grandearnings += $totalpay;
		  $grandgross += $totalgrosspay;
		}
		echo '<tr><td align="right"><i>Totals</i>:</td><td>'.$grandhours.'</td>
		<td>$'.money_format('%.2n', $grandgross).'</td>
		<td>$'.money_format('%.2n', $grandearnings).'</td>
		</tr>
		</table>
		<table cellspacing="0" class="nicetable" align="center">
		<tr><td colspan="4" style="font-size:18px;">'.$lastyear.'</td></tr><tr><td><u>Month</td><td><u>Hours Worked</td><td><u>Gross</td><td><u>Net</td></tr>';
		
		$grandhours = 0;$grandearnings=0;$grandgross=0;
		// Last year
		for ($i = 12;$i > 0;$i--) {
		$sql2 = "SELECT * FROM ".$table_paychecks." WHERE Date LIKE '".sprintf("%02d", $i)."%' AND Date LIKE '%".$lastyear."'";
		$getEntries2 = mysql_query($sql2) or die(mysql_error());  
		$totalhours = 0;$totalpay=0;$totalgrosspay=0;
		  while($row2 = mysql_fetch_array( $getEntries2 )) {
		  	  $totalhours += $row2['TotalHours'];
		  	  $totalpay += ($row2['TotalAfterTaxes']);
		  	  $totalgrosspay += ($row2['PayRatePerHour']*$row2['TotalHours']);
		  	  $tax1+=$row2['FedTax'];
		  	  $tax2+=$row2['StTax'];
		  	  $tax3+=$row2['FICA'];
		  	  $tax4+=$row2['Med'];
		  }
		  $a = DateTime::createFromFormat('!m', $i);
		  $b = $a->format('F'); // i.e. March
		  echo '<tr><td>'.$b.'</td><td>'.$totalhours.'</td><td>$'.money_format('%.2n', $totalgrosspay).'</td><td>$'.money_format('%.2n', $totalpay).'</td></tr>';
		  $grandhours += $totalhours;
		  $grandearnings += $totalpay;
		  $grandgross += $totalgrosspay;
		}
		  echo '<tr><td align="right"><i>Totals</i>:</td><td>'.$grandhours.'</td><td>$'.money_format('%.2n', $grandgross).'</td><td>$'.money_format('%.2n', $grandearnings).'</td></tr>';
		  ?>
	</table>	
		
	<?php }
}


if ($_SESSION['AccountType'] == 'Administrator' AND !isset($_GET['archive']) AND !isset($_GET['pending']) AND !isset($_GET['paychecks']) AND !isset($_GET['add']) AND !isset($_GET['add2'])) {

	
if (!isset($_GET['employee'])) {  ?>
	
<h2>Current Month</h2>
<br />
<table align='center'><tr><td valign='top'>

<table cellspacing="0" class="nicetable" align="center" style="width:400px;">
<tr><th>Name</th><th>Hours</th></tr>
<?php
$getEntries = mysql_query("SELECT EmployeeID, SUM(TotalHours) totalCount FROM ".$table_timesheet." WHERE Date LIKE '".date("m")."%' AND Date LIKE '%".date("y")."' GROUP BY EmployeeID") or die(mysql_error());  
$q = 0;$addup = 0;

  while($row = mysql_fetch_array( $getEntries )) {
  	  $q++; if ($q % 2) $bg = "#FFFFFF"; else $bg = "#DDDDDD";
  	  $getEntries2 = mysql_query("SELECT * FROM ".$table_employees." WHERE EmployeeID = '".$row['EmployeeID']."'") or die(mysql_error());   while($row2 = mysql_fetch_array( $getEntries2 )) { $name = $row2['FirstName'] . " " . $row2['LastName']; }
  	  
  	  echo '
		<tr bgcolor='.$bg.'>
		<td nowrap="nowrap"><a href="home.php?employee='.$row['EmployeeID'].'">'.$name.'</a></td>
		<td>'.$row['totalCount'].'</td>';
		$addup += $row['totalCount'];
  }
 echo '<tr><td><strong>Total hours:</strong></td><td>'.$addup.'</td></tr>';
 ?>
</table>

</td><td>&nbsp;&nbsp;&nbsp;</td><td valign='top'>
<?php include 'small_calendar.php'; ?>
</td></tr>
</table>
<br />
<?php } 

if (isset($_GET['employee'])) echo '<h2>Shifts this Month</h2>'; ?>

<table cellspacing="0" class="nicetable" align="center">
<tr><th>Date</th><th>Name</th><th>Start Time</th><th>End Time</th><th>Total Hours</th><th>Net Pay</th><th>Notes</th><th>Paid</th></tr>

<?php
	if (isset($_GET['employee']))
		$sql = "SELECT * FROM ".$table_timesheet." WHERE Date LIKE '".date("m")."%' AND Date LIKE '%".date("y")."' AND EmployeeID = ".$_GET['employee']." ORDER BY Date desc";
	else 	$sql = "SELECT * FROM ".$table_timesheet." WHERE Date LIKE '".date("m")."%' AND Date LIKE '%".date("y")."' ORDER BY Date desc";

$getEntries = mysql_query($sql) or die(mysql_error());  
$q = 0;$addup = 0;$addupPay = 0;

  while($row = mysql_fetch_array( $getEntries )) {
  	  $q++; if ($q % 2) $bg = "#FFFFFF"; else $bg = "#DDDDDD";
  	  $getEntries2 = mysql_query("SELECT * FROM ".$table_employees." WHERE EmployeeID = '".$row['EmployeeID']."'") or die(mysql_error());   while($row2 = mysql_fetch_array( $getEntries2 )) { $name = $row2['FirstName'] . " " . $row2['LastName']; }
  	  if ($row['PayRate'] == "") $payrate = 10; else $payrate = $row['PayRate'];
  	  $netpay = $row['TotalHours'] * $row['PayRate'];
  	  echo '<tr bgcolor='.$bg.'>
		<td nowrap="nowrap">'.$row['Date'].'</td>
		<td nowrap="nowrap"><a href="home.php?employee='.$row['EmployeeID'].'">'.$name.'</a></td>
		<td>'.$times[$row['StartTime']].'</td>
		<td>'.$times[$row['EndTime']].'</td>
		<td>'.$row['TotalHours'].'</td>
		<td>'.money_format('%.2n', $netpay).'</td>';
		if ($row['Description'] != "") { echo '
			<td align="center" nowrap="nowrap"><a href="#" class="tooltip"><span><img class="callout" src="images/callout.gif" />'.htmlspecialchars($row['Description']).'</span>View</a></td>';
		} else echo '<td></td>';
		if ($row['Paid'] == "y") { echo '
			<td align="center"><img src="images/checkmark-transparent.png" width="20" alt="Yes" title="Yes" /></div></td>';
		} else echo '<td></td>';
		$addup += $row['TotalHours'];
		$addupPay += $netpay;
}
echo '<tr><td></td><td></td><td></td><td><strong>Totals:</strong></td><td>'.$addup.'</td><td>$'.money_format('%.2n', $addupPay).'</td><td></td><td></td></tr>';
?>
</table>



<?php } ?>
 
<?php 
if (!isset($_GET['archive']) AND !isset($_GET['pending']) AND !isset($_GET['paychecks'])) {
 	  if ($_SESSION['AccountType'] != 'Administrator' AND $_SESSION['AccountType'] != 'Accountant') { ?>
<h2>My Hours this Month</h2>

<table cellspacing="0" class="nicetable" align="center">
<tr><td colspan='7'><h3><?php echo date("F")." ". date("Y"); ?></h3></td></tr>
<tr><td><u>Date</td><td><u>Start Time</td><td><u>End Time</td><td><u>Total Hours</td><td><u>Net Pay</td><td><u>Notes</td><td></td></tr>
<?php
$getEntries = mysql_query("SELECT * FROM ".$table_timesheet." WHERE EmployeeID = '".$_SESSION['EmployeeID']."' AND Date LIKE '".date("m")."%' AND Date LIKE '%".date("y")."' ORDER BY Date desc") or die(mysql_error());  
$q = 0;$addup = 0;$addupPay = 0;

  while($row = mysql_fetch_array( $getEntries )) {
  	  $q++; if ($q % 2) $bg = "#FFFFFF"; else $bg = "#DDDDDD";
  	  $netpay = $row['TotalHours'] * $row['PayRate'];

  	  echo '<tr bgcolor='.$bg.'>
		<td nowrap="nowrap">'.$row['Date'].'</td>
		<td>'.$times[$row['StartTime']].'</td>
		<td>'.$times[$row['EndTime']].'</td>
		<td>'.$row['TotalHours'].'</td>
		<td>'.money_format('%.2n', $netpay).'</td>';
		if ($row['Description'] != "") { echo '
			<td align="center" nowrap="nowrap"><a href="#" class="tooltip"><span><img class="callout" src="images/callout.gif" />'.htmlspecialchars($row['Description']).'</span>View</a></td>';
		} else echo '<td></td>';
?>

<td><a href="home.php?delete=<?php echo $row['EntryID']; ?>"  class="tooltip" onclick="return confirm('Are you sure you want to delete this shift?')"><span><img class="callout" src="images/callout.gif" />Delete</span>X</a></td></tr>
<?php
$addup += $row['TotalHours'];
$addupPay += $netpay;
  }
if ($q == 0) echo '<tr><td colspan="7">No hours logged in this month.</td></tr>';
else echo '<tr><td></td><td></td><td><strong>Totals:</strong></td><td>'.$addup.'</td><td>$'.money_format('%.2n', $addupPay).'</td><td></td><td></td></tr>';
?>
</table>

 <?php }
 
}
 
include '_footer.php';
?>
