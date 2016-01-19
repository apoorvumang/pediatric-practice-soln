<?php include('header.php'); 
?>

<script>
$(function() {
	$( "#date_show" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1970:2032",
		dateFormat:"dd/mm/yy",
		altField: "#date",
		altFormat: "yy-mm-dd"
	});
});
</script>
<?php 
	if(isset($_POST['p_id'])) {
		$date = date('Y-m-d');
		if(mysqli_query($link, "INSERT INTO visits(p_id, date) VALUES({$_POST['p_id']}, '{$date}');")) {
			echo "Visit added successfully!";
		}
		else {
			echo "Error in adding visit";
			echo "INSERT INTO visits(p_id, date) VALUES({$_POST['p_id']}, \'{$_POST['date']}\');";
		}
		
	}
?>
<form action="" method="post" enctype="multipart/form-data" style="width:auto">
<h3>Add Visit</h3>
	<p>
	<label for="p_id">Patient ID:&nbsp;&nbsp;</label>
	<input type="text" name="p_id" id="p_id" />
	</p>

	<p>
	<input type="submit" />
	</p>

</form>



<?php include('footer.php'); ?>