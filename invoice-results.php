<?php include('header.php');
//What needs to be done on this page:
// List out all schedules for a particular date, in a form.
// The dates *only* can be edited. Give a link for the patient also.
?>

<h3>Search Results</h3>
<?php
if($_POST['specificdate'])  //If some submit button clicked
{
  $date = date('Y-m-d', strtotime($_POST['date']));
  $date = mysqli_real_escape_string($link, $date);
  $result = mysqli_query($link, "SELECT i.id, i.p_id as pid, i.date as date, i.mode as mode, p.name as pname, i.descriptions as descriptions, i.amounts as amounts FROM invoice i, patients p WHERE i.date='".$date."' AND i.p_id = p.id ORDER BY i.id");
  $nrows = mysqli_num_rows($result);
?>

<table>
<tbody>
<tr>
<th>ID</th>
<th>Patient</th>
<th>Date</th>
<th>Mode</th>
<th>Descriptions</th>
<th>Amounts</th>
<th>Total</th>
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
<a href= <?php echo "\"pdf-invoice.php?id={$row['id']}\""; ?> ><?php echo $row['pname']; ?></a>
</td>
<td>
<?php echo date('j M Y',strtotime($row['date'])); ?>
</td>
<td>
<?php echo $row['mode']; ?>
</td>
<td>
<?php echo $row['descriptions']; ?>
</td>
<td>
<?php echo $row['amounts']; ?>
</td>
<td>
  <?php
  $total = 0;
  $amounts = explode(",", $row['amounts']);
  foreach ($amounts as $key => $amount) {
    $total = $total + $amount;
  }
  echo $total;
  ?>
</td>
</tr>
<?php
$count++;
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
