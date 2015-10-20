<?php include('header.php'); ?>
<script>
$(function() {
	$( "#dob" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1970:2032",
		dateFormat:"d M yy"
	});
});
$(function() {
	$( "#dob_noyear" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1970:2032",
		dateFormat:"d M yy"
	});
});
</script>
<h3>Search Patients</h3>
<form action="search-patient-results.php" method="post" enctype="multipart/form-data" style="width:auto" name="4">
	<label for="id">ID: &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="id" id="id" style="margin-right:40px;"/>
	<input type="submit" name="specificid" value="Go" />
</form>
<form action="search-patient-results.php" method="post" enctype="multipart/form-data" style="width:auto" name="5">
	<!-- <label for="id_from">ID Range</label>
	<br /> -->
	<label for="id_from">ID From: &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="id_from" id="id_from" style="margin-right:40px;"/>
	<label for="id_to">To: &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="id_to" id="id_to" style="margin-right:40px;"/>
	<input type="submit" name="id_range" value="Go" />
</form>
<form action="search-patient-results.php" method="post" enctype="multipart/form-data" style="width:auto" name="1">
	<label for="dob">DOB: &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="dob" id="dob" style="margin-right:40px;"/>
	<input type="submit" name="specificdob" value="Go" />
</form>
<form action="search-patient-results.php" method="post" enctype="multipart/form-data" style="width:auto" name="6">
	<label for="dob_from">DOB From: &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="dob_from" id="dob_from" style="margin-right:40px;"/>
	<label for="dob_to">To: &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="dob_to" id="dob_to" style="margin-right:40px;"/>
	<input type="submit" name="dobrange" value="Go" />
</form>
<form action="search-patient-results.php" method="post" enctype="multipart/form-data" style="width:auto" name="2">
	<label for="dob_noyear">DOB (Year not needed): &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="dob_noyear" id="dob_noyear" style="margin-right:40px;"/>
	<input type="submit" name="specificdob_noyear" value="Go" />
</form>
<form action="search-patient-results.php" method="post" enctype="multipart/form-data" style="width:auto" name="3">
	<label for="name">Name: &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="name" id="name" style="margin-right:40px;"/>
	<input type="submit" name="specificname" value="Go" />
</form>
<?php include('footer.php'); ?>