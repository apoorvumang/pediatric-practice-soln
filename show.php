<?php include('header.php'); 
if($_POST['id'])	//id posted and not 0? weird TODO change this maybe?
{
	Redirect("edit-sched.php?id={$_POST['id']}");
	exit;
}
else if($_POST['dob'])
{
	$result = mysqli_query($link, "SELECT * FROM patients WHERE dob='{$_POST['dob']}'");
	if(mysqli_num_rows($result)==1)
	{
		$patient = mysqli_fetch_assoc($result);
		Redirect("edit-sched.php?id={$patient['id']}");
		exit;
	}
	while($patient = mysqli_fetch_assoc($result))
	{
		echo "<a href=\"edit-sched.php?id={$patient['id']}\">".$patient['name']."</a>";
		echo "<br />";
	}
}
?>

<script>
$(function() {
	$( "#dob_show" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1985:2032",
		dateFormat:"dd/mm/yy",
		altField: "#dob",
		altFormat: "yy-mm-dd"
	});
});
</script>

<h3>Patient Information</h3>
<form action="" method="post">
	<label for="id">Enter ID:</label>
	<input type="text" name="id" id="id" />
	<input type="submit" name="submit" value="Go" />
</form>
<form action="" method="post">
	<label for="dob">Enter DOB:</label>
	<input type="hidden" name="dob" id="dob" />
	<input type="text" name="dob_show" id="dob_show" />
	<input type="submit" name="submit" value="Go" />
</form>
<table>
	<tbody>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Date of Birth</th>
			<th>Phone</th>
			<th>Sex</th>
		</tr>
		<?php $result = mysqli_query($link, "SELECT * FROM patients WHERE 1 ORDER BY id");
		while($row = mysqli_fetch_assoc($result))
		{
			
			echo "<tr>";
			echo "<td>".$row['id']."</td>";
			echo "<td><a href=edit-sched.php?id=".$row['id'].">";
			echo $row['name'];
			echo "</a></td>";
			echo "<td>".$row['dob']."</td>";
			echo "<td>".$row['phone']."</td>";
			echo "<td>".$row['sex']."</td>";
			echo "</tr>";
			
		}
		?>
	</tbody>
</table>


<?php include('footer.php'); ?>