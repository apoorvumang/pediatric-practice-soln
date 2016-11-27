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
$(function() {
	$( "#dob_from" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1970:2032",
		dateFormat:"d M yy"
	});
});
$(function() {
	$( "#dob_to" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1970:2032",
		dateFormat:"d M yy"
	});
});
</script>
<div class="row">
<div class="col-six tab-full">
<h3>Search Patients</h3>
<form action="search-patient-results.php" method="post" enctype="multipart/form-data" name="4">
	<label for="id">ID: </label>
	<input class="full-width" type="text" name="id" id="id" style="margin-right:40px;"/>
	<input type="submit" name="specificid" value="Go" />
</form>
</div>
<div class="col-six tab-full">
<form action="search-patient-results.php" method="post" enctype="multipart/form-data"  name="5">
	<!-- <label for="id_from">ID Range</label>
	<br /> -->
	<label for="id_from">ID From: </label>
	<input class="full-width" type="text" name="id_from" id="id_from" style="margin-right:40px;"/>
	<label for="id_to">To: </label>
	<input class="full-width" type="text" name="id_to" id="id_to" style="margin-right:40px;"/>
	<input type="submit" name="id_range" value="Go" />
</form>
</div>
<div class="col-six tab-full">
<form action="search-patient-results.php" method="post" enctype="multipart/form-data" name="1">
	<label for="dob">DOB: </label>
	<input class="full-width" type="text" name="dob" id="dob" style="margin-right:40px;"/>
	<input type="submit" name="specificdob" value="Go" />
</form>
</div>
<div class="col-six tab-full">
<form action="search-patient-results.php" method="post" enctype="multipart/form-data"  name="6">
	<label for="dob_from">DOB From: </label>
	<input class="full-width" type="text" name="dob_from" id="dob_from" style="margin-right:40px;"/>
	<label for="dob_to">To: </label>
	<input class="full-width" type="text" name="dob_to" id="dob_to" style="margin-right:40px;"/>
	<input type="submit" name="dobrange" value="Go" />
</form>
</div>
<div class="col-six tab-full">
<form action="search-patient-results.php" method="post" enctype="multipart/form-data" name="2">
	<label for="dob_noyear">DOB (Year not needed): </label>
	<input class="full-width" type="text" name="dob_noyear" id="dob_noyear" style="margin-right:40px;"/>
	<input type="submit" name="specificdob_noyear" value="Go" />
</form>
</div>
<div class="col-six tab-full">
<form action="search-patient-results.php" method="post" enctype="multipart/form-data" style="width:auto" name="3">
	<label for="name">Name: </label>
	<input class="full-width" type="text" name="name" id="name" style="margin-right:40px;"/>
	<input type="submit" name="specificname" value="Go" />
</form>
</div>
<?php include('footer.php'); ?>