<?php include('header.php'); ?>
<script>
$(function() {
	$( "input.datepicker" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1970:2032",
		dateFormat:"d M yy"
	});
});
</script>

<?php
if($_POST['specificdate']||$_POST['tofromdate']||$_POST['patientsearch'])	//If some submit button clicked
{
	if($_POST['specificdate'])
	{
		$_POST['date'] = date('Y-m-d', strtotime($_POST['date']));
        $_POST['date'] = mysqli_real_escape_string($link, $_POST['date']);
        $query = "SELECT a.id as aid, p.id as pid, p.name as pname, a.date as date, a.time as time, TIME(a.time) as time_converted, a.comment as comment, p.phone as phone, p.phone2 as phone2 FROM patients p, appointments a WHERE a.date ='{$_POST['date']}' AND p.id = a.p_id ORDER BY date, time_converted";
        
        $result = mysqli_query($link, $query);
		$nrows = mysqli_num_rows($result);
    }
    
?>

<table>
<tbody>
<tr>
<th>ID</th>
<th>Patient</th>
<th>Date</th>
<th>Time</th>
<th>Phone</th>
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
</tr>
<?php
$count++;
}}
?>
</tbody>
</table>

<h3>Search Consultation Appointments</h3>
<form action="" method="post" enctype="multipart/form-data" style="width:auto" name="1">
	<label for="date">Show appointments for : &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="date" class="datepicker" id="date" style="margin-right:40px;"/>
	<input type="submit" name="specificdate" value="Go" />
</form>

<?php include('footer.php'); ?>
