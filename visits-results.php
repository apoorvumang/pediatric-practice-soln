<?php include('header.php');
//What needs to be done on this page:
// List out all schedules for a particular date, in a form.
// The dates *only* can be edited. Give a link for the patient also.
?>

<h3>Search Results</h3>
<?php
if($_POST['specificdate'])  //If some submit button clicked
{
  
  $_POST['date'] = date('Y-m-d', strtotime($_POST['date']));
  $_POST['date'] = mysqli_real_escape_string($link, $_POST['date']);
  $result = mysqli_query($link, "SELECT n.p_id as pid, n.date as date, n.note as note, p.name as pname FROM notes n, patients p WHERE n.date='".$_POST['date']."' AND n.p_id = p.id");
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

<?php
}
else
{
echo "<h4>You cannot access this page directly!</h4>";
}
include('footer.php'); ?>
