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
		$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE date ='{$_POST['date']}' AND given='N'");
		$nrows = mysqli_num_rows($result);
	}
	else if($_POST['tofromdate'])
	{
		$_POST['todate'] = date('Y-m-d', strtotime($_POST['todate']));
		$_POST['todate'] = mysqli_real_escape_string($link, $_POST['todate']);
		$_POST['fromdate'] = date('Y-m-d', strtotime($_POST['fromdate']));
		$_POST['fromdate'] = mysqli_real_escape_string($link, $_POST['fromdate']);

		$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE date >='{$_POST['fromdate']}' AND date <='{$_POST['todate']}' AND given='N' ORDER BY date");
		$nrows = mysqli_num_rows($result);
	}
	else if($_POST['patientsearch'])
	{
		$_POST['patientid'] = mysqli_real_escape_string($link, $_POST['patientid']);
		$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id ='{$_POST['patientid']}' AND given='N' ORDER BY date");
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

<form action="" method="post" enctype="multipart/form-data" style="width:auto">
<table>
	<tbody>
		<tr>
			<th>Given</th>
			<th>ID</th>
			<th>Patient</th>
			<th>Vaccine</th>
			<th>Scheduled Date</th>
			<th>Phone</th>
			<th>Send SMS</th>
		</tr>
<?php
	$count = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT name, sex, id, phone, dob, active FROM patients WHERE id={$row['p_id']}"));
		if($patient['active']==0)
			continue;
		$vaccine = mysqli_fetch_assoc(mysqli_query($link, "SELECT name, upper_limit FROM vaccines WHERE id={$row['v_id']}"));

?>
	<tr <?php 

if ($row['given']=='Y')
		{
			echo "id=\"focus_green\"";	//green focus if vaccine has been given
		}
		else if (strtotime("now") < strtotime($row['date']))
		{
			echo "id=\"focus_yellow\"";	//yellow focus if sched date is yet to come
		}
		else if (($vaccine['upper_limit'] > 36500)||(strtotime("now") < strtotime("+".$vaccine['upper_limit']." days", strtotime($patient['dob']))))	//strtotime causes error if too large value is given
		{
			echo "id=\"focus_orange\"";	//orange focus if sched date has gone but vac can still be given
		}
		else
		{	
			echo "id=\"focus_red\"";	//red focus if vaccine cant be given now
		}

	?>
>
		<td>
			<?php echo $row['given'];?>
		</td>
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
			<input type="text" name="vac_date[]" style="width:80px" <?php echo "id=\"vac_date".$count."\""; ?> value=<?php echo "\"".date('j M Y',strtotime($row['date']))."\"";?>/>
			<input type="hidden" name="vac_id[]" style="width:80px" value=<?php echo "\"{$row['id']}\""; ?> />
		</td>
		<td>
			<?php echo $patient['phone']; ?>
		</td>
		<td>
			<input type="checkbox" name="send_sms_id[]" value= <?php echo "\"{$row['id']}\""; ?> />
		</td>
	</tr>
<?php 
	$count++;
	}
?>
	</tbody>
</table>
<input type="submit" name="save" value="Save Changes" />
<input type="button" name="check" value="Check All" onClick="checkAll()" style="float:right;margin-right:20px" />
<input type="button" name="uncheck" value="Uncheck All" onClick="uncheckAll()" style="float:right;margin-right:20px" />
<input type="submit" name="sendautosms" value="Send Auto SMS" style="float:right;margin-right:20px"/>
<input type="submit" name="sendcustomsms" value="Send Custom SMS" style="float:right;margin-right:20px"/>
<textarea rows="3" cols="80" name="customsms"></textarea>
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
else if(isset($_POST['sendautosms'])||isset($_POST['sendcustomsms']))
{
	foreach ($_POST['send_sms_id'] as $key => $value) 
	{
		$row = mysqli_fetch_assoc(mysqli_query($link, "SELECT v_id, p_id, date FROM vac_schedule WHERE id={$value}"));
		$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT phone, phone2, first_name FROM patients WHERE id={$row['p_id']}"));
		$vaccine = mysqli_fetch_assoc(mysqli_query($link, "SELECT name FROM vaccines WHERE id={$row['v_id']}"));
		
		if(isset($_POST['sendautosms']))
		{
			if(strtotime($row['date']) < strtotime("now"))	//If date has passed
			{
				$message = "Dear parent\nYour child {$patient['first_name']} is due for {$vaccine['name']} vaccination\nDr. Mahima";
			}
			else
			{
				$message = "Dear parent\nYour child {$patient['first_name']} is due for {$vaccine['name']} vaccination on ".date('j M Y',strtotime($row['date']))."\nDr. Mahima";
			}
		}
		else
		{
			$message = $_POST['customsms'];
		}
		
		if($patient['phone'])
			mail("sms@drmahima.com", $patient['phone'], $message);
		if($patient['phone2'])
			mail("sms@drmahima.com", $patient['phone2'], $message);
		echo "SMS sent to {$patient['first_name']} <br>";
	}
}
else
{
	echo "<h4>You cannot access this page directly!</h4>";
}
include('footer.php'); ?>