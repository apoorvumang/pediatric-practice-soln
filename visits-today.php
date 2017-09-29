<?php include('header.php');
//What needs to be done on this page:
// List out all schedules for current day.
?>

<h3>Today's visits</h3>
<?php
  $today = date('Y-m-d');
  $result = mysqli_query($link, "SELECT n.invoice_id as invoice_id, n.id, n.p_id as pid, n.date as date, n.note as note, p.name as pname, n.height as height, n.weight as weight FROM notes n, patients p WHERE n.date='".$today."' AND n.p_id = p.id ORDER BY n.id");
  $nrows = mysqli_num_rows($result);
?>

<table>
<tbody>
<tr>
<th>Visit ID</th>
<th>Patient ID</th>
<th>Patient</th>
<th>Height</th>
<th>Weight</th>
<th>BMI</th>
<th>Note</th>
<th>Date</th>
<th>Invoice ID</th>
</tr>
<?php
$count = 0;
while($row = mysqli_fetch_assoc($result))
{

?>
<tr>
<td>
<?php echo "v".$row['id'];?>
</td>
<td>
<?php echo "<b>".$row['pid']."</b>";?>
</td>
<td>
<a href= <?php echo "\"edit-sched.php?id={$row['pid']}\""; ?> ><?php echo $row['pname']; ?></a>
</td>
<td>
<?php echo $row['height']." cm"; ?>
</td>
<td>
<?php echo $row['weight']." kg"; ?>
</td>
<td>
<?php
  $height = $row['height']/100.0;
  $weight = $row['weight'];
  $height_squared = $height*$height;
  $bmi = $weight/$height_squared;
  echo number_format((float)$bmi, 2, '.', '');;
?>
</td>
<td>
<?php echo $row['note']; ?>
</td>
<td>
<?php echo date('j M Y',strtotime($row['date'])); ?>
</td>
<td>
<?php
if($_SESSION['type']=='doctor') {
  $invoice_id = $row['invoice_id'];
  if($invoice_id) {
    echo "<a href='pdf-invoice.php?id={$row['invoice_id']}'> Invoice ID {$row['invoice_id']} </a>";
  } else {
    echo "Invoice not made!";
    echo "<a href='create-invoice.php?id={$row['pid']}&visit_id={$row['id']}'> Click here to create invoice </a>";
  }
} else {
  echo '-';
}
?>
</td>
</tr>
<?php
$count++;
}
?>
</tbody>
</table>

<a href="visits.php">Search visits</a>

<?php

include('footer.php'); ?>
