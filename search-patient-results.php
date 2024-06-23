<?php include('header.php');

include "smsGateway.php";
include 'send_message_twilio.php';

$smsGateway = new SmsGateway('apoorvumang@gmail.com', 'vultr123');

$deviceID = 84200;

?>

<h3>Search Results</h3>
<?php

if($_POST['specificdob']||$_POST['specificdob_noyear']||$_POST['specificname']||$_POST['specificid']||$_POST['id_range']||$_POST['dobrange']||$_POST['phone_number'])	//If some submit button clicked
{
	if($_POST['specificdob'])
	{
		$_POST['dob'] = date('Y-m-d', strtotime($_POST['dob']));
		$_POST['dob'] = mysqli_real_escape_string($link, $_POST['dob']);
		$result = mysqli_query($link, "SELECT name, sex, id, phone, phone2, dob, active FROM patients WHERE dob ='{$_POST['dob']}' ORDER BY name");
		$nrows = mysqli_num_rows($result);
	}
	else if($_POST['specificdob_noyear'])
	{
		$day = date('d', strtotime($_POST['dob_noyear']));
		$month = date('m', strtotime($_POST['dob_noyear']));
		$result = mysqli_query($link, "SELECT name, sex, id, phone, phone2, dob, active FROM patients WHERE DAY(dob) = {$day} AND MONTH(dob) = {$month} ORDER BY name");
		$nrows = mysqli_num_rows($result);
	}
	else if($_POST['specificname'])
	{
		$_POST['name'] = mysqli_real_escape_string($link, $_POST['name']);
		$result = mysqli_query($link, "SELECT name, sex, id, phone, phone2, dob, active FROM patients WHERE name LIKE '%{$_POST['name']}%' ORDER BY name");
		$nrows = mysqli_num_rows($result);
	}
	else if($_POST['phone_number'])
	{
		$phone = mysqli_real_escape_string($link, $_POST['phone']);
		$query = "SELECT name, sex, id, phone, phone2, dob, active FROM patients WHERE phone LIKE '%{$phone}' or phone2 LIKE '%{$phone}'";
		$result = mysqli_query($link, $query);
		$nrows = mysqli_num_rows($result);
	}
	else if($_POST['specificid'])
	{
		Redirect("edit-sched.php?id={$_POST['id']}");
	}
	else if($_POST['id_range'])
	{
		$_POST['id_from'] = mysqli_real_escape_string($link, $_POST['id_from']);
		$_POST['id_to'] = mysqli_real_escape_string($link, $_POST['id_to']);
		$result = mysqli_query($link, "SELECT name, sex, id, phone, phone2, dob, active FROM patients WHERE id >= {$_POST['id_from']} AND id <= {$_POST['id_to']} ORDER BY id");
		$nrows = mysqli_num_rows($result);
	}
	else if($_POST['dobrange'])
	{
		$_POST['dob_from'] = mysqli_real_escape_string($link, date('Y-m-d', strtotime($_POST['dob_from'])));
		$_POST['dob_to'] = mysqli_real_escape_string($link, date('Y-m-d', strtotime($_POST['dob_to'])));
		$result = mysqli_query($link, "SELECT name, sex, id, phone, phone2, dob, active FROM patients WHERE dob >= '{$_POST['dob_from']}' AND dob <= '{$_POST['dob_to']}' ORDER BY id");
		$nrows = mysqli_num_rows($result);
	}
?>

<form action="" method="post" enctype="multipart/form-data" style="width:auto">
<table>
<input type="button" name="check" value="Check All" onClick="checkAll()" style="float:right;margin-right:20px" />
<input type="button" name="uncheck" value="Uncheck All" onClick="uncheckAll()" style="float:right;margin-right:20px" />
<input type="submit" name="sendautosms" value="Send Birthday SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<input type="submit" name="sendcustomsms" value="Send Custom SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<textarea rows="3" cols="80" name="customsms"></textarea>
	<tbody>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>DOB</th>
			<th>Sex</th>
			<th>Phone</th>
			<th>Send SMS</th>
		</tr>
<?php
	while($row = mysqli_fetch_assoc($result))
	{
?>
	<tr>
		<td>
			<?php echo $row['id'];?>
		</td>
		<td>
			<a href= <?php echo "\"edit-sched.php?id={$row['id']}\""; ?> ><?php echo $row['name']; ?></a>
		</td>
		<td>
			<?php echo date('d M Y', strtotime($row['dob'])); ?>
		</td>
		<td>
			<?php echo $row['sex']; ?>
		</td>
		<td>
			<?php echo $row['phone']; ?>
			<?php if($row['phone2']) echo "<br />" + $row['phone2']; ?>
		</td>
		<td>
			<input type="checkbox" name="send_sms_id[]" value= <?php echo "\"{$row['id']}\""; ?> phoneCount= <?php if($row['phone2']) echo "2"; else echo "1"; ?> patientID = <?php echo $row['id'];?>/>
		</td>
	</tr>
<?php
	}
?>
	</tbody>
</table>
<input type="button" name="check" value="Check All" onClick="checkAll()" style="float:right;margin-right:20px" />
<input type="button" name="uncheck" value="Uncheck All" onClick="uncheckAll()" style="float:right;margin-right:20px" />
<input type="submit" name="sendautosms" value="Send Birthday SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<input type="submit" name="sendcustomsms" value="Send Custom SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<textarea rows="3" cols="80" name="customsms"></textarea>
</form>
<?php
}
else if(isset($_POST['sendautosms'])||isset($_POST['sendcustomsms']))
{
	foreach ($_POST['send_sms_id'] as $key => $value)
	{
		$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT phone, phone2, first_name FROM patients WHERE id={$value}"));

		if ($use_twilio)
		{
			if(isset($_POST['sendautosms'])) {
				$data = [
					'name' => $patient['first_name'],
				];
				$phone = $patient['phone'];
				$phone2 = $patient['phone2'];
				$templateName = 'birthday';

				if($phone)
					$message = sendMessageTwilio($phone, $templateName, $data);
				if($phone2)
					$message = sendMessageTwilio($phone2, $templateName, $data);

					echo "Twilio Whatsapp birthday msg sent to {$patient['first_name']} <br>";
			} else if(isset($_POST['sendcustomsms'])) {
				echo "Custom SMS not supported for Twilio Whatsapp <br>";
			}

		}
		else
		{
			if(isset($_POST['sendautosms']))
			{
				$message = "Dear {$patient['first_name']} \nWishing you a very very Happy Birthday!\n" .$dr_name."\n".$dr_phone;
			}
			else if(isset($_POST['sendcustomsms']))
			{
				$message = $_POST['customsms'];
			}

			if($patient['phone'])
				$result = $smsGateway->sendMessageToNumber($patient['phone'], $message, $deviceID);
			if($patient['phone2'])
				$result = $smsGateway->sendMessageToNumber($patient['phone2'], $message, $deviceID);

			echo "SMS sent to {$patient['first_name']} <br>";
		}
	}
}
else
{
	echo "<h4>You cannot access this page directly!</h4>";
}
include('footer.php'); ?>
