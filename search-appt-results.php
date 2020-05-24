<?php include('header.php');
include "smsGateway.php";
$smsGateway = new SmsGateway();
$deviceID = 84200;
?>

<h3>Appointment Search Results</h3>
<?php
if($_POST['specificdate']||$_POST['tofromdate']||$_POST['patientsearch'])	//If some submit button clicked
{
	if($_POST['specificdate'])
	{
		$_POST['date'] = date('Y-m-d', strtotime($_POST['date']));
        $_POST['date'] = mysqli_real_escape_string($link, $_POST['date']);
        $query = "SELECT a.id as aid, p.id as pid, p.name as pname, a.date as date, a.time as time, a.comment as comment, p.phone as phone, p.phone2 as phone2 FROM patients p, appointments a WHERE a.date ='{$_POST['date']}' AND p.id = a.p_id";
        
        $result = mysqli_query($link, $query);
		$nrows = mysqli_num_rows($result);
	}
	// else if($_POST['tofromdate'])
	// {
	// 	$_POST['todate'] = date('Y-m-d', strtotime($_POST['todate']));
	// 	$_POST['todate'] = mysqli_real_escape_string($link, $_POST['todate']);
	// 	$_POST['fromdate'] = date('Y-m-d', strtotime($_POST['fromdate']));
	// 	$_POST['fromdate'] = mysqli_real_escape_string($link, $_POST['fromdate']);
	// 	$result = mysqli_query($link, "SELECT p.id as pid, p.name as pname, v.name as vname, vm.name as vmname, vs.date_given, p.phone, p.phone2 FROM patients p, vaccines v, vac_make vm, vac_schedule vs WHERE vs.date_given <='{$_POST['todate']}' AND vs.date_given >= '{$_POST['fromdate']}' AND vs.given='Y' AND p.id = vs.p_id AND v.id = vs.v_id AND vm.id = vs.make");
		
	// 	$nrows = mysqli_num_rows($result);
	// }
	// else if($_POST['patientsearch'])
	// {
	// 	$_POST['patientid'] = mysqli_real_escape_string($link, $_POST['patientid']);
	// 	$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id ='{$_POST['patientid']}' AND given='Y' ORDER BY date_given");
	// 	$nrows = mysqli_num_rows($result);
	// }
?>

<script type="text/javascript">
$(function() {
	$( "input.datepicker" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1970:2032",
		dateFormat:"d M yy"
	});
});
</script>
<form action="" method="post" enctype="multipart/form-data" style="width:auto">
<input type="button" name="check" value="Check All" onClick="checkAll()" style="float:right;margin-right:20px" />
<input type="button" name="uncheck" value="Uncheck All" onClick="uncheckAll()" style="float:right;margin-right:20px" />
<input type="submit" name="sendautosms" value="Send Auto SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<input type="submit" name="sendcustomsms" value="Send Custom SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<textarea rows="3" cols="100" name="customsms"></textarea>

<table>
<tbody>
<tr>
<th>ID</th>
<th>Patient</th>
<th>Date</th>
<th>Time</th>
<th>Phone</th>
<th>Send SMS</th>
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
<?php echo date('j M Y',strtotime($row['date'])); ?>
</td>
<td>
<?php echo $row['time']; ?>
</td>
<td>
<?php echo $row['phone']; ?>
</td>
<td>
<input type="checkbox" name="send_sms_id[]" value= <?php echo "\"{$row['aid']}\""; ?> phoneCount= <?php if($row['phone2']) echo "2"; else echo "1"; ?>>
</td>
</tr>
<?php
$count++;
}
?>
</tbody>
</table>

<input type="button" name="check" value="Check All" onClick="checkAll()" style="float:right;margin-right:20px" />
<input type="button" name="uncheck" value="Uncheck All" onClick="uncheckAll()" style="float:right;margin-right:20px" />
<input type="submit" name="sendautosms" value="Send Auto SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<input type="submit" name="sendcustomsms" value="Send Custom SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<textarea rows="3" cols="100" name="customsms">
</textarea>
</form>


<?php
} else if(isset($_POST['sendautosms'])||isset($_POST['sendcustomsms'])) {
	foreach ($_POST['send_sms_id'] as $key => $value)
	{
        $query = "SELECT p.phone, p.phone2, p.first_name, a.date as date, a.time as time FROM patients p, appointments a WHERE a.id={$value} AND a.p_id = p.id";
        $row = mysqli_fetch_assoc(mysqli_query($link, $query));
        $date = date('j M Y',strtotime($row['date']));
        if(isset($_POST['sendautosms']))
        {
            $message = "Dear {$row['first_name']}\nYou have an appointment on {$date} {$row['time']}\n" .$dr_name."\n".$dr_phone;
        }
        if(isset($_POST['sendcustomsms']))
        {
            $message = $_POST['customsms'];
        }
        if(row['phone'])
            $result = $smsGateway->sendMessageToNumber($row['phone'], $message, $deviceID);
        if(row['phone2'])
            $result = $smsGateway->sendMessageToNumber($row['phone2'], $message, $deviceID);
        echo "SMS sent to {$row['first_name']} <br>";
	}
}
else
{
echo "<h4>You cannot access this page directly!</h4>";
}
include('footer.php'); ?>
