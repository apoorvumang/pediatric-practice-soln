<?php include('header.php');
include('gen-sched-func.php');
if($_POST['id'])
{
foreach ($_POST['id'] as $key => $patient_id) 
{
	generate_patient_schedule($patient_id);
}
}
?>

<form action="" method="post" enctype="multipart/form-data" style="width:auto">
<h3>Schedule Patients</h3>
<table>
	<tbody>
		<tr>
			<th>Select</th>
			<th>ID</th>
			<th>Name</th>
			<th>Date of Birth</th>
			<th>Phone</th>
			<th>Sex</th>
		</tr>
		<?php $result = mysql_query("SELECT * FROM patients WHERE 1");
		while($row = mysql_fetch_assoc($result))
		{
			echo "<tr>";
			echo "<td><input type=\"checkbox\" name=\"id[]\" value=".$row['id']." /></td>";
			echo "<td>".$row['id']."</td>";
			echo "<td>".$row['name']."</td>";
			echo "<td>".$row['dob']."</td>";
			echo "<td>".$row['phone']."</td>";
			echo "<td>".$row['sex']."</td>";
			echo "</tr>";
		}
		?>
	</tbody>
</table>

<p>
<input type="submit" name="submit" value="Generate Schedule"/>
</p>

</form>
<?php include('footer.php'); ?>