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
		$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE date ='{$_POST['date']}'");
		$nrows = mysqli_num_rows($result);
	}
	else if($_POST['tofromdate'])
	{
		$_POST['todate'] = date('Y-m-d', strtotime($_POST['todate']));
		$_POST['todate'] = mysqli_real_escape_string($link, $_POST['todate']);
		$_POST['fromdate'] = date('Y-m-d', strtotime($_POST['fromdate']));
		$_POST['fromdate'] = mysqli_real_escape_string($link, $_POST['fromdate']);

		$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE date >='{$_POST['fromdate']}' AND date <='{$_POST['todate']}' ORDER BY date");
		$nrows = mysqli_num_rows($result);
	}
	else if($_POST['patientsearch'])
	{
		$_POST['patientid'] = mysqli_real_escape_string($link, $_POST['patientid']);
		$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id ='{$_POST['patientid']}' ORDER BY date");
		$nrows = mysqli_num_rows($result);
	}
?>

<script type="text/javascript">

	<?php for ($i=0; $i < $nrows; $i++) { ?>

			$(function() {
				$( <?php echo "\"#vac_date".$i."\""; ?> ).datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: "1985:2022",
					dateFormat:"d M yy"
				});
			});
			
	<?php } ?>
	
</script>

<form action="" method="post" enctype="multipart/form-data" style="width:auto">
<table>
	<tbody>
		<tr>
			<th>Given</th>
			<th>Patient</th>
			<th>Vaccine</th>
			<th>Scheduled Date</th>
		</tr>
<?php
	$count = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT name, sex, id FROM patients WHERE id={$row['p_id']}"));
		$vaccine = mysqli_fetch_assoc(mysqli_query($link, "SELECT name FROM vaccines WHERE id={$row['v_id']}"));

?>
	<tr <?php if($row['given']=='Y') echo "id=\"focus_green\"";?>>
		<td>
			<?php echo $row['given'];?>
		</td>
		<td>
			<?php echo $patient['name']; ?>
		</td>
		<td>
			<?php echo $vaccine['name']; ?>
		</td>
		<td>
			<input type="text" name="vac_date[]" style="width:80px" <?php echo "id=\"vac_date".$count."\""; ?> value=<?php echo "\"".date('j M Y',strtotime($row['date']))."\"";?>/>
			<input type="hidden" name="vac_id[]" style="width:80px" value=<?php echo "\"{$row['id']}\""; ?> />
		</td>
	</tr>
<?php 
	$count++;
	}
?>
	</tbody>
</table>
<input type="hidden" name="save" value="save" /> <!-- This is here so that we can know that this form is being submitted, not some other -->
<input type="submit" name="submit" value="Save Changes" />
</form>
<?php
}
else if(isset($_POST['save']))
{
	$err = 0;
	foreach ($_POST['vac_date'] as $key => $value) {
		# code...
		$value = date('Y-m-d', strtotime($value));
		if(!mysqli_query($link, "UPDATE vac_schedule SET date='{$value}' WHERE id={$_POST['vac_id'][$key]}"))
		{
			$err = 1;
		}
	}
	if(!$err)
	{
		echo "Schedule successfully updated!";
	}
	else
	{
		echo "Error in updating schedule.";
	}
}
else
{
	echo "<h4>You cannot access this page directly!</h4>";
}
include('footer.php'); ?>