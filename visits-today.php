<?php include('header.php');
//What needs to be done on this page:
// List out all schedules for current day.
?>

<h3>Today's visits</h3>
<?php
  $today = date('Y-m-d');
  $today = mysqli_real_escape_string($link, today);
  $result = mysqli_query($link, "SELECT n.id, n.p_id as pid, n.date as date, n.note as note, p.name as pname FROM notes n, patients p WHERE n.date='".$today."' AND n.p_id = p.id ORDER BY n.id");
  $nrows = mysqli_num_rows($result);
?>

<table>
<tbody>
<tr>
<th>ID</th>
<th>Patient</th>
<th>Note</th>
<th>Date</th>
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
<?php echo $row['note']; ?>
</td>
<td>
<?php echo date('j M Y',strtotime($row['date'])); ?>
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
