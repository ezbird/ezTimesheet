<?php
$monthNames = Array("January", "February", "March", "April", "May", "June", "July",
"August", "September", "October", "November", "December");
?>

<?php
if (!isset($_REQUEST["month"])) $_REQUEST["month"] = date("m");
if (!isset($_REQUEST["year"])) $_REQUEST["year"] = date("Y");
?>

<?php
$cMonth = $_REQUEST["month"];
$cYear = $_REQUEST["year"];
 
$prev_year = $cYear;
$next_year = $cYear;
$prev_month = $cMonth-1;
$next_month = $cMonth+1;
 
if ($prev_month == 0 ) {
    $prev_month = 12;
    $prev_year = $cYear - 1;
}
if ($next_month == 13 ) {
    $next_month = 1;
    $next_year = $cYear + 1;
}
?>

<table width="200">
<tr>
<td align="center">
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='font-size:12px;'>
<tr align="center">
<td colspan="7" bgcolor="#999999" style="color:#FFFFFF;border-radius:3px;"><strong><?php echo $monthNames[$cMonth-1].' '.$cYear; ?></strong></td>
</tr>
<tr>
<td align="center" bgcolor="#999999" style="color:#FFFFFF;border-radius:3px;"><strong>S</strong></td>
<td align="center" bgcolor="#999999" style="color:#FFFFFF;border-radius:3px;"><strong>M</strong></td>
<td align="center" bgcolor="#999999" style="color:#FFFFFF;border-radius:3px;"><strong>T</strong></td>
<td align="center" bgcolor="#999999" style="color:#FFFFFF;border-radius:3px;"><strong>W</strong></td>
<td align="center" bgcolor="#999999" style="color:#FFFFFF;border-radius:3px;"><strong>T</strong></td>
<td align="center" bgcolor="#999999" style="color:#FFFFFF;border-radius:3px;"><strong>F</strong></td>
<td align="center" bgcolor="#999999" style="color:#FFFFFF;border-radius:3px;"><strong>S</strong></td>
</tr>

<?php
$timestamp = mktime(0,0,0,$cMonth,1,$cYear);
$maxday = date("t",$timestamp);
$thismonth = getdate ($timestamp);
$startday = $thismonth['wday'];

for ($i=0; $i<($maxday+$startday); $i++) {
    if(($i % 7) == 0 ) echo "<tr>";
    if($i < $startday) echo "<td></td>";
    else { 
    $asdf = ($i - $startday + 1); 
    if ($asdf == date('d') && $cMonth == date('m')) $more = " style='color:blue;padding:2px;background-color:#AAA;'"; else $more = " style='color:blue;'";
    	    ?>
    <td align='center' valign='middle' height='20px'>
        <a href='##' onclick='ShowActionDate(<?php echo $asdf; ?>,<?php echo $_REQUEST["month"]; ?>,<?php echo $_REQUEST["year"]; ?>);return false;' <?php echo $more; ?>><?php echo $asdf; ?></a>
    </td><?php 
    }
    if(($i % 7) == 6 ) echo "</tr>";
}
?>

</table>
</td>
</tr>
</table>
