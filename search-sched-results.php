<?php include('header.php');
//What needs to be done on this page:
// List out all schedules for a particular date, in a form.
// The dates *only* can be edited. Give a link for the patient also.

include "smsGateway.php";
include 'send_message_twilio.php';
$smsGateway = new SmsGateway('apoorvumang@gmail.com', 'vultr123');

$deviceID = 84200;
?>

<h3>Search Results</h3>
<?php

if($_POST['specificdate']||$_POST['tofromdate']||$_POST['patientsearch'])	//If some submit button clicked
{
    $include_appointments = isset($_POST['include_appointments']);

    if($_POST['specificdate'])
    {
        $_POST['date'] = date('Y-m-d', strtotime($_POST['date']));
        $_POST['date'] = mysqli_real_escape_string($link, $_POST['date']);
        $result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE date ='{$_POST['date']}' AND given='N'");
        $nrows = mysqli_num_rows($result);

        if ($include_appointments) {
            $appt_result = mysqli_query($link, "SELECT * FROM appointments WHERE date ='{$_POST['date']}'");
            $nrows += mysqli_num_rows($appt_result);
        }
    }
    else if($_POST['tofromdate'])
    {
        $_POST['todate'] = date('Y-m-d', strtotime($_POST['todate']));
        $_POST['todate'] = mysqli_real_escape_string($link, $_POST['todate']);
        $_POST['fromdate'] = date('Y-m-d', strtotime($_POST['fromdate']));
        $_POST['fromdate'] = mysqli_real_escape_string($link, $_POST['fromdate']);

        $result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE date >='{$_POST['fromdate']}' AND date <='{$_POST['todate']}' AND given='N' ORDER BY date");
        $nrows = mysqli_num_rows($result);

        if ($include_appointments) {
            $appt_result = mysqli_query($link, "SELECT * FROM appointments WHERE date >='{$_POST['fromdate']}' AND date <='{$_POST['todate']}' ORDER BY date");
            $nrows += mysqli_num_rows($appt_result);
        }
    }
    else if($_POST['patientsearch'])
    {
        $_POST['patientid'] = mysqli_real_escape_string($link, $_POST['patientid']);
        $result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id ='{$_POST['patientid']}' AND given='N' ORDER BY date");
        $nrows = mysqli_num_rows($result);

        if ($include_appointments) {
            $appt_result = mysqli_query($link, "SELECT * FROM appointments WHERE p_id ='{$_POST['patientid']}' ORDER BY date");
            $nrows += mysqli_num_rows($appt_result);
        }
    }
?>

<script type="text/javascript">
$(function() {
    $( ".datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1970:2032",
        dateFormat:"d M yy"
    });
});
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
            <th>Type</th>
            <th>Given</th>
            <th>ID</th>
            <th>Patient</th>
            <th>Vaccine/Appointment</th>
            <th>Scheduled Date</th>
            <th>Time</th>
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
        <td>Vaccine</td>
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
            <input type="text" name="vac_date[]" style="width:80px" class="datepicker" value=<?php echo "\"".date('j M Y',strtotime($row['date']))."\"";?>/>
            <input type="hidden" name="vac_id[]" style="width:80px" value=<?php echo "\"{$row['id']}\""; ?> />
        </td>
        <td>N/A</td>
        <td>
            <?php echo $patient['phone']; ?>
            <?php if($patient['phone2']) echo "<br />" . $patient['phone2']; ?>
        </td>
        <td>
            <input type="checkbox" name="send_sms_id[]" value= <?php echo "\"{$row['id']}\""; ?> phoneCount= <?php if($patient['phone2']) echo "2"; else echo "1"; ?> patientID = <?php echo $row['p_id'];?> dataType="vaccine"/>
        </td>
    </tr>
<?php
    $count++;
    }

    if ($include_appointments && isset($appt_result)) {
        while($row = mysqli_fetch_assoc($appt_result))
        {
            $patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT name, sex, id, phone, phone2, dob, active FROM patients WHERE id={$row['p_id']}"));
            // We want consultation of even inactive patients, so commenting out the following line.
			// This is because we want to send SMS to patients who have appointments, even if they are inactive for vaccines.
			// if($patient['active']==0)
            //     continue;
?>
    <tr id="focus_gray">
        <td>Appointment</td>
        <td>N/A</td>
        <td>
            <?php echo $row['p_id'];?>
        </td>
        <td>
            <a href= <?php echo "\"edit-sched.php?id={$patient['id']}\""; ?> ><?php echo $patient['name']; ?></a>
        </td>
        <td>
			<?php echo $row['comment'];?>
		</td>
        <td>
            <input type="text" name="appt_date[]" style="width:80px" class="datepicker" value=<?php echo "\"".date('j M Y',strtotime($row['date']))."\"";?>/>
            <input type="hidden" name="appt_id[]" style="width:80px" value=<?php echo "\"{$row['id']}\""; ?> />
        </td>
        <td>
            <?php echo $row['time'];?>
        </td>
        <td>
            <?php echo $patient['phone']; ?>
            <?php if($patient['phone2']) echo "<br />" . $patient['phone2']; ?>
        </td>
        <td>
            <input type="checkbox" name="appt_send_sms_id[]" value= <?php echo "\"{$row['id']}\""; ?> phoneCount= <?php if($patient['phone2']) echo "2"; else echo "1"; ?> patientID = <?php echo $row['p_id'];?> dataType="appointment"/>
        </td>
    </tr>
<?php
            $count++;
        }
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
    if(isset($_POST['appt_date']))
    {
        foreach ($_POST['appt_date'] as $key => $value) {
            $value = date('Y-m-d', strtotime($value));
            if(!mysqli_query($link, "UPDATE appointments SET date='{$value}' WHERE id={$_POST['appt_id'][$key]}"))
            {
                $err = 1;
            }
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
		// initialize $templateName to blank, $msg_data to blank dictionary

		$templateName = '';
		$msg_data = [];

		if(isset($_POST['sendautosms'])||isset($_POST['sendemail']))
		{
			if(strtotime($row['date']) < strtotime("now"))	//If date has passed
			{
				$templateName = 'vaccine_reminder_nodate';
				$msg_data = [
					'name' => $row['pname'],
					'vaccine' => $row['vaccines']
				];
				$message = "Dear {$row['pname']}\nYou are due for {$row['vaccines']} vaccination\n" .$dr_name."\n".$dr_phone;
			}
			else
			{
				$templateName = 'vaccine_reminder';
				$msg_data = [
					'name' => $row['pname'],
					'vaccine' => $row['vaccines'],
					'date' => date('j M Y',strtotime($row['date']))
				];
				$message = "Dear {$row['pname']}\nYou are due for {$row['vaccines']} vaccination on ".date('j M Y',strtotime($row['date']))."\n".$dr_name."\n".$dr_phone;
			}
		}
		else
		{
			$message = $_POST['customsms'];
		}

		if(isset($_POST['sendautosms'])||isset($_POST['sendcustomsms']))
		{
			if ($use_twilio)
			{
				if(isset($_POST['sendautosms'])) {
					$phone = $row['phone'];
					$phone2 = $row['phone2'];

					if($phone)
						$message = sendMessageTwilio($phone, $templateName, $msg_data);
					if($phone2)
						$message = sendMessageTwilio($phone2, $templateName, $msg_data);

					echo "Twilio Whatsapp vaccine reminder msg sent with data " . json_encode($msg_data) . " to {$row['pname']} <br>";
				} else if(isset($_POST['sendcustomsms'])) {
					echo "Custom SMS not supported for Twilio Whatsapp <br>";
				}

			}
			else
			{
				if($row['phone'])
					$smsResult = $smsGateway->sendMessageToNumber($row['phone'], $message, $deviceID);
				if($row['phone2'])
					$smsResult = $smsGateway->sendMessageToNumber($row['phone2'], $message, $deviceID);
				echo "SMS sent to {$row['pname']} <br>";
			}
			
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

    // Add appointment SMS logic here
    if (isset($_POST['appt_send_sms_id'])) {
        $apptQueryPart1 = "SELECT p.email as email, p.id as pid, p.first_name as pname, a.comment as comment, a.date as date, p.phone as phone, p.phone2 as phone2 FROM patients p, appointments a WHERE p.id = a.p_id AND a.id in(";
        $apptQueryPart2 = "";
        foreach ($_POST['appt_send_sms_id'] as $key => $value) {
            $apptQueryPart2 .= "{$value},";
        }
        $apptQueryPart2 .= "0";  // To avoid trailing commas in the query
        $apptQuery = $apptQueryPart1 . $apptQueryPart2 . ")";

        $apptResult = mysqli_query($link, $apptQuery);

        while ($apptRow = mysqli_fetch_assoc($apptResult))
        {
            if (isset($_POST['sendautosms']) || isset($_POST['sendemail'])) {
                $message = "Dear {$apptRow['pname']},\nYou have an appointment on " . date('j M Y', strtotime($apptRow['date'])) . ".\nDr. {$dr_name}\n{$dr_phone}";
            } else {
                $message = $_POST['customsms'];
            }

            // SMS sending
            if (isset($_POST['sendautosms']) || isset($_POST['sendcustomsms'])) {
                if ($use_twilio) {
                    if ($apptRow['phone']) {
                        sendMessageTwilio($apptRow['phone'], 'appointment_reminder', ['name' => $apptRow['pname'], 'date' => date('j M Y', strtotime($apptRow['date']))]);
                    }
                    if ($apptRow['phone2']) {
                        sendMessageTwilio($apptRow['phone2'], 'appointment_reminder', ['name' => $apptRow['pname'], 'date' => date('j M Y', strtotime($apptRow['date']))]);
                    }
                    echo "Twilio WhatsApp appointment reminder sent to {$apptRow['pname']}<br>";
                } else {
                    if ($apptRow['phone']) {
                        $smsResult = $smsGateway->sendMessageToNumber($apptRow['phone'], $message, $deviceID);
                    }
                    if ($apptRow['phone2']) {
                        $smsResult = $smsGateway->sendMessageToNumber($apptRow['phone2'], $message, $deviceID);
                    }
                    echo "SMS sent to {$apptRow['pname']}<br>";
                }
            }

            // Email sending
            if (isset($_POST['sendemail'])) {
                if ($apptRow['email']) {
                    mail($apptRow['email'], 'Appointment Reminder - ' . $dr_name, $message, "From: " . $dr_email . "\n");
                    echo "Email sent to {$apptRow['pname']}<br>";
                } else {
                    echo "Unable to send email - no email address present. Patient name: {$apptRow['pname']}<br>";
                }
            }
        }
    }
}
else
{
	echo "<h4>You cannot access this page directly!</h4>";
}
include('footer.php'); ?>
