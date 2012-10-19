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
		$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE date_given ='{$_POST['date']}' AND given='Y'");
		$nrows = mysqli_num_rows($result);
	}
	else if($_POST['tofromdate'])
	{
		$_POST['todate'] = date('Y-m-d', strtotime($_POST['todate']));
		$_POST['todate'] = mysqli_real_escape_string($link, $_POST['todate']);
		$_POST['fromdate'] = date('Y-m-d', strtotime($_POST['fromdate']));
		$_POST['fromdate'] = mysqli_real_escape_string($link, $_POST['fromdate']);

		$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE date_given >='{$_POST['fromdate']}' AND date_given <='{$_POST['todate']}' AND given='Y' ORDER BY date_given");
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
<th>Given Date</th>
<th>Phone</th>
</tr>
<?php
$count = 0;
while($row = mysqli_fetch_assoc($result))
{
$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT name, sex, id, phone, dob FROM patients WHERE id={$row['p_id']}"));
$vaccine = mysqli_fetch_assoc(mysqli_query($link, "SELECT name, upper_limit FROM vaccines WHERE id={$row['v_id']}"));

?>
<tr>
<td>
<?php echo $row['p_id'];?>
</td>
<td>
<a href= <?php echo "\"edit-sched.php?id={$patient['id']}\""; ?> ><?php echo $patient['name']; ?></a>
</td>
<td>
<?php echo $vaccine['name']; ?>
</td>
<td>
<?php echo date('j M Y',strtotime($row['date_given'])); ?>
</td>
<td>
<?php echo $patient['phone']; ?>
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