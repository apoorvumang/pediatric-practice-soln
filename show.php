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
		yearRange: "1970:2032",
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
<?php include('footer.php'); ?>