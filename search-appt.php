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
<h3>Search Consultation Appointments</h3>
<form action="search-appt-results.php" method="post" enctype="multipart/form-data" style="width:auto" name="1">
	<label for="date">Show appointments for : &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="date" class="datepicker" id="date" style="margin-right:40px;"/>
	<input type="submit" name="specificdate" value="Go" />
</form>

<?php include('footer.php'); ?>
