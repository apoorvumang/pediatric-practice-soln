<?php include('header.php');

$result = mysqli_query($link, "SELECT pd.id as pd_id, p.name as name, p.phone as phone, p.phone2 as phone2, pd.p_id as id, pd.date as date, pd.amount as amount, pd.comment as comment FROM patients p, payment_due pd WHERE p.id = pd.p_id");
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
		<input type="checkbox" name="send_sms_id[]" value= <?php echo "\"{$payment_due['id']}\""; ?> phoneCount= <?php if($payment_due['phone2']) echo "2"; else echo "1"; ?> />
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