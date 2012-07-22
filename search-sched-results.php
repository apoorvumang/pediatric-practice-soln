<?php include('header.php'); 
//What needs to be done on this page:
// List out all schedules for a particular date, in a form.
// The dates *only* can be edited. Give a link for the patient also.
?>

<h3>Search Results</h3>
<?php
if(isset($_POST['date']))
{
	$_POST['date'] = date('Y-m-d', strtotime($_POST['date']));
	$_POST['date'] = mysqli_real_escape_string($link, $_POST['date']);
	$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE date ='{$_POST['date']}'");
?>
<table>
	<tbody>
		<tr>
			<th>Given</th>
			<th>Patient</th>
			<th>Vaccine</th>
			<th>Scheduled Date</th>
		</tr>
<?php
	while($row = mysqli_fetch_assoc($result))
	{
		$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT name, sex, id FROM patients WHERE id={$row['p_id']}"));
		$vaccine = mysqli_fetch_assoc(mysqli_query($link, "SELECT name FROM vaccines WHERE id={$row['v_id']}"));

?>
	<tr>
		<td <?php if($row['given']=='Y') echo "id=\"focus_green\"";?>>
			<?php echo $row['given'];?>
		</td>
		<td>
			<?php echo $patient['name']; ?>
		</td>
		<td>
			<?php echo $vaccine['name']; ?>
		</td>
		<td>
			<?php echo $row['date']; ?>
		</td>
	</tr>
<?php 
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