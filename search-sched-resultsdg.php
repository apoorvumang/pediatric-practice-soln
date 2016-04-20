<?php include('header.php');
//What needs to be done on this page:
// List out all schedules for a particular date, in a form.
// The dates *only* can be edited. Give a link for the patient also.
?>

<h3>Search Results</h3>
<?php
if($_POST['specificdate']||$_POST['tofromdate']||$_POST['patientsearch'])	//If some submit button clicked
{
	if($_POST['specificdate'])
	{
		$_POST['date'] = date('Y-m-d', strtotime($_POST['date']));
		$_POST['date'] = mysqli_real_escape_string($link, $_POST['date']);
		$result = mysqli_query($link, "SELECT p.id as pid, p.name as pname, v.name as vname, vm.name as vmname, vs.date_given, p.phone, p.phone2 FROM patients p, vaccines v, vac_make vm, vac_schedule vs WHERE vs.date_given ='{$_POST['date']}' AND vs.given='Y' AND p.id = vs.p_id AND v.id = vs.v_id AND vm.id = vs.make");
		$nrows = mysqli_num_rows($result);
	}
	else if($_POST['tofromdate'])
	{
		$_POST['todate'] = date('Y-m-d', strtotime($_POST['todate']));
		$_POST['todate'] = mysqli_real_escape_string($link, $_POST['todate']);
		$_POST['fromdate'] = date('Y-m-d', strtotime($_POST['fromdate']));
		$_POST['fromdate'] = mysqli_real_escape_string($link, $_POST['fromdate']);
		$result = mysqli_query($link, "SELECT p.id as pid, p.name as pname, v.name as vname, vm.name as vmname, vs.date_given, p.phone, p.phone2 FROM patients p, vaccines v, vac_make vm, vac_schedule vs WHERE vs.date_given <='{$_POST['todate']}' AND vs.date_given >= '{$_POST['fromdate']}' AND vs.given='Y' AND p.id = vs.p_id AND v.id = vs.v_id AND vm.id = vs.make");
		
		$nrows = mysqli_num_rows($result);
	}
	else if($_POST['patientsearch'])
	{
		$_POST['patientid'] = mysqli_real_escape_string($link, $_POST['patientid']);
		$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id ='{$_POST['patientid']}' AND given='Y' ORDER BY date_given");
		$nrows = mysqli_num_rows($result);
	}
?>

<script type="text/javascript">

<?php for ($i=0; $i < $nrows; $i++) { ?>

$(function() {
$( <?php echo "\"#vac_date".$i."\""; ?> ).datepicker({
changeMonth: true,
changeYear: true,
yearRange: "1970:2032",
dateFormat:"d M yy"
});
});
<?php } ?>
</script>

<table>
<tbody>
<tr>
<th>ID</th>
<th>Patient</th>
<th>Vaccine</th>
<th>Vaccine make</th>
<th>Given Date</th>
<th>Phone</th>
</tr>
<?php
$count = 0;
while($row = mysqli_fetch_assoc($result))
{

?>
<tr>
<td>
<?php echo $row['pid'];?>
</td>
<td>
<a href= <?php echo "\"edit-sched.php?id={$row['pid']}\""; ?> ><?php echo $row['pname']; ?></a>
</td>
<td>
<?php echo $row['vname']; ?>
</td>
<td>
<?php echo $row['vmname']; ?>
</td>
<td>
<?php echo date('j M Y',strtotime($row['date_given'])); ?>
</td>
<td>
<?php echo $row['phone']; ?>
</td>
</tr>
<?php
$count++;
}
?>
</tbody>
</table>

<?php
}
else
{
echo "<h4>You cannot access this page directly!</h4>";
}
include('footer.php'); ?>
