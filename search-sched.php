<?php include('header.php'); ?>
<script>
$(function() {
	$( "#date" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1985:2022",
		dateFormat:"d M yy"
	});
});
</script>
<h3>Search Schedule</h3>
<form action="search-sched-results.php" method="post" enctype="multipart/form-data" style="width:auto">
	<label for="date">Show schedules for : &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="date" id="date" />
	<input type="submit" name="submit" value="Go" />
</form>
<?php include('footer.php'); ?>