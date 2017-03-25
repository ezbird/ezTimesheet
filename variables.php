<?php
/* Global Variables - Update ALL these to the correct value */
$database_name   = "ezra087_home";
$database_user   = "ezra087_1";
$database_pass   = "primary";
$table_timesheet = "petsitting_timesheet";
$table_employees = "petsitting_employees";
$table_paychecks = "petsitting_paychecks";
$path_to_logo    = "http://salinapetsitting.com/images/alwayshome_logo_teal_vector_v2.png";
$primary_URL     = "http://salinapetsitting.com/admin/";
$organization    = "Always Home Pet Services";
$adminemail		 = "alwayshomepetservice@gmail.com";

/*
Optional Taxes/Deductions - 
---------------------------
Either enter a percentage for automatic calculation
  OR 
put 0 (ZERO) at any of these values for manual 
input of deductions or to not use them.
*/
$global_tax1     		= "0"; 		// Federal tax
$global_tax2     		= "0"; 		// State tax
$global_tax3     		= "6.2";   	// FICA, i.e. 6.2
$global_tax4     		= "1.45";  	// Medicaire, i.e. 1.45
$overtime_multiplier    = "1.5";  	// Multiplier of hourly rate for hours > 40/week; leave as 0 if overtime not possible

/* 
Email server settings 
-------------------
Used to email employee's a paycheck stub.
Leave blank to not use.
*/
$host		= "mail.salinapetsitting.com";    // i.e. mail.bobswidgets.com
$port		= "25";	// i.e. 25
$user		= "contact@salinapetsitting.com";	// i.e. bob@bobswidgets.com
$pass		= "contact1";	// i.e. niceStrongpassword
$fromemail 	= "contact@salinapetsitting.com";	// i.e. contact@bobswidgets.com

/*
Choose your timezone. 
For choices, go to: http://php.net/manual/en/timezones.php
*/
date_default_timezone_set('America/Chicago');
?>
