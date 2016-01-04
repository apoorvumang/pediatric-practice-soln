<?php include('header.php');

$result = mysqli_query($link, "SELECT pd.id as pd_id, p.name as name, p.phone as phone, p.phone2 as phone2, pd.p_id as id, pd.date as date, pd.amount as amount, pd.comment as comment FROM patients p, payment_due pd WHERE p.id = pd.p_id");
?>
<h3>Payments Due</h3>
<table>
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Amount</th>
		<th>Comment</th>
		<th>Date</th>
		<th>Phone</th>
		<th>Send SMS</th>
		<th>Delete</th>
	</tr>
<?php
while($payment_due = mysqli_fetch_assoc($result)) {
	echo "<tr>";
	echo "<td>".$payment_due['id']."</td>";
	echo "<td>".$payment_due['name']."</td>";
	echo "<td>".$payment_due['amount']."</td>";
	echo "<td>".$payment_due['comment']."</td>";
	echo "<td>".$payment_due['date']."</td>";
	?>
	<td>
		<?php echo $payment_due['phone']; ?>
		<?php if($payment_due['phone2']) echo "<br />" + $payment_due['phone2']; ?>
	</td>
	<td>
		<input type="checkbox" name="send_sms_id[]" value= <?php echo "\"{$payment_due['id']}\""; ?> phoneCount= <?php if($payment_due['phone2']) echo "2"; else echo "1"; ?> />
	</td>
	<td>
		<input type="checkbox" name="delete_id[]" value= <?php echo "\"{$payment_due['pd_id']}\""; ?>  />
	</td>
	<?php

	echo "</tr>";
};
?>
</table>

<?php
include('footer.php'); ?>