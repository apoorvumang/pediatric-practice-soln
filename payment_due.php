<?php include('header.php');

if(isset($_POST['sendautosms'])||isset($_POST['sendcustomsms']))
{
	foreach ($_POST['send_sms_id'] as $key => $value)
	{
		$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT p.name, p.phone, p.phone2, pd.date, pd.amount, pd.comment from patients p, payment_due pd where p.id = pd.p_id and p.id = {$value};"));
		if(isset($_POST['sendautosms']))
		{
			$message = "Dear {$patient['name']} \nYou have Rs.{$patient['amount']} payment due for {$patient['comment']} done on ".date('d M Y', strtotime($patient['date']))."\n" .$dr_name."\n".$dr_phone;
		}
		else if(isset($_POST['sendcustomsms']))
		{
			$message = $_POST['customsms'];
		}

		if($patient['phone'])
			mail($dr_email_sms, $patient['phone'], $message);
		if($patient['phone2'])
			mail($dr_email_sms, $patient['phone2'], $message);
		echo "SMS sent to {$patient['name']} <br>";
	}
}

$result = mysqli_query($link, "SELECT pd.id as pd_id, p.name as name, p.phone as phone, p.phone2 as phone2, pd.p_id as id, pd.date as date, pd.amount as amount, pd.comment as comment FROM patients p, payment_due pd WHERE p.id = pd.p_id AND pd.paid = 'N' ORDER BY pd.date");
?>
<h3>Payments Due</h3>
<form action="" method="post" enctype="multipart/form-data" style="width:auto" role="form">
<input type="submit" name="sendautosms" value="Send Payment Due SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<input type="submit" name="sendcustomsms" value="Send Custom SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<textarea rows="3" cols="80" name="customsms"></textarea>
<table>
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Amount</th>
		<th>Comment</th>
		<th>Date</th>
		<th>Phone</th>
		<th>Send SMS</th>
	</tr>
<?php
	$total = 0;
while($payment_due = mysqli_fetch_assoc($result)) {
	$total += $payment_due['amount'];
	echo "<tr>";
	echo "<td>".$payment_due['id']."</td>";
	?>

	<td>
		<a href= <?php echo "\"edit-sched.php?id={$payment_due['id']}\""; ?> ><?php echo $payment_due['name']; ?></a>
	</td>
	<?php

	echo "<td>".$payment_due['amount']."</td>";
	echo "<td>".$payment_due['comment']."</td>";
	echo "<td>".date('d M Y', strtotime($payment_due['date']))."</td>";
	?>
	<td>
		<?php echo $payment_due['phone']; ?>
		<?php if($payment_due['phone2']) echo "<br />" + $payment_due['phone2']; ?>
	</td>
	<td>
		<input type="checkbox" name="send_sms_id[]" value= <?php echo "\"{$payment_due['id']}\""; ?> phoneCount= <?php if($payment_due['phone2']) echo "2"; else echo "1"; ?> patientID = <?php echo $payment_due['id'];?>/>
	</td>
	<?php

	echo "</tr>";
};
?>
</table>
<?php echo "<p>Total Due: Rs.".$total."</p>"; ?>
<input type="submit" name="sendautosms" value="Send Payment Due SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<input type="submit" name="sendcustomsms" value="Send Custom SMS" style="float:right;margin-right:20px" onclick="countMessages(event)"/>
<textarea rows="3" cols="80" name="customsms"></textarea>
</form>

<?php
include('footer.php'); ?>
