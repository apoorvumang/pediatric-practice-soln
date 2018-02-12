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
<input type="submit" name="save" value="Save Changes" />
<input type="button" name="check" value="Check All" onClick="checkAll()" style="float:right;margin-right:20px" />
<input type="button" name="uncheck" value="Uncheck All" onClick="uncheckAll()" style="float:right;margin-right:20px" />
<input type="submit" name="sendautosms" value="Send Auto SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<input type="submit" name="sendcustomsms" value="Send Custom SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<input type="submit" name="sendemail" value="Send Email" style="float:right;margin-right:20px" />
<textarea rows="3" cols="80" name="customsms"></textarea>
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
		$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT name, sex, id, phone, phone2, dob, active FROM patients WHERE id={$row['p_id']}"));
		if($patient['active']==0)
			continue;
		$vaccine = mysqli_fetch_assoc(mysqli_query($link, "SELECT name, upper_limit, dependent FROM vaccines WHERE id={$row['v_id']}"));
		if($vaccine['dependent'] != 0) {
			$query_for_dependent = "SELECT vs.given as given FROM vac_schedule vs WHERE p_id={$row['p_id']} and v_id = {$vaccine['dependent']}";
		  $dependent_schedule = mysqli_fetch_assoc(mysqli_query($link, $query_for_dependent));
			if($dependent_schedule['given'] == 'N') {
				continue;
			}
		}

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
			<?php if($patient['phone2']) echo "<br />" + $patient['phone2']; ?>
		</td>
		<td>
			<input type="checkbox" name="send_sms_id[]" value= <?php echo "\"{$row['id']}\""; ?> phoneCount= <?php if($patient['phone2']) echo "2"; else echo "1"; ?> patientID = <?php echo $row['p_id'];?>/>
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
<input type="submit" name="sendautosms" value="Send Auto SMS" style="float:right;margin-right:20px" onclick="countMessages(event)" />
<input type="submit" name="sendcustomsms" value="Send Custom SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<input type="submit" name="sendemail" value="Send Email" style="float:right;margin-right:20px"/>
<textarea rows="3" cols="80" name="customsms"></textarea>
</form>
<?php
}
else if(isset($_POST['save']))
{
	$err = 0;
	foreach ($_POST['vac_date'] as $key => $value) {
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
else if(isset($_POST['sendautosms'])||isset($_POST['sendcustomsms'])||isset($_POST['sendemail']))
{
	$queryPart1 = "SELECT p.email as email, p.id as pid, p.first_name as pname, group_concat(v.name order by v.name asc separator ',') as vaccines, vs.date as date, p.phone as phone, p.phone2 as phone2 FROM patients p, vaccines v, vac_schedule vs WHERE  p.id = vs.p_id AND v.id = vs.v_id AND vs.id in(";
	$queryPart3 = ") group by p.id, vs.date";
	$queryPart2 = "";
	foreach ($_POST['send_sms_id'] as $key => $value) {
		$queryPart2 .= "{$value},";
	}
	$queryPart2 .="0";
	$query = $queryPart1.$queryPart2.$queryPart3;
	$result = mysqli_query($link, $query);

	while ($row = mysqli_fetch_assoc($result))
	{
		$row['vaccines'] = str_replace("PNEUMOCOCCAL", "PCV", $row['vaccines']);
		if(isset($_POST['sendautosms'])||isset($_POST['sendemail']))
		{
			if(strtotime($row['date']) < strtotime("now"))	//If date has passed
			{
				$message = "Dear {$row['pname']}\nYou are due for {$row['vaccines']} vaccination\n" .$dr_name."\n".$dr_phone;
			}
			else
			{
				$message = "Dear {$row['pname']}\nYou are due for {$row['vaccines']} vaccination on ".date('j M Y',strtotime($row['date']))."\n".$dr_name."\n".$dr_phone;
			}
		}
		else
		{
			$message = $_POST['customsms'];
		}

		if(isset($_POST['sendautosms'])||isset($_POST['sendcustomsms']))
		{
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
			$headers .= "From: ".$dr_name." <".$dr_email.">\r\n";
			if($row['phone'])
				mail($dr_email_sms, "ets: ".$row['phone'], $message, $headers);
			if($row['phone2'])
				mail($dr_email_sms, "ets: ".$row['phone2'], $message, $headers);
			echo "SMS sent to {$row['pname']} <br>";
		}

		if(isset($_POST['sendemail']))
		{
			if($row['email'])
			{
				mail($row['email'], 'Vaccination Due - '.$dr_name, $message, "From: ".$dr_email."\n");
				echo "Email sent to {$row['pname']} <br>";
			}
			else
			{
				echo "Unable to send email - no email address present. Patient name: {$row['pname']} <br>";
			}
		}
	}
}
else
{
	echo "<h4>You cannot access this page directly!</h4>";
}
include('footer.php'); ?>
