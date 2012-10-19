<?php include('header.php'); ?>
<script>
$(function() {
$( "#date" ).datepicker({
changeMonth: true,
changeYear: true,
yearRange: "1970:2032",
dateFormat:"d M yy"
});
});
$(function() {
$( "#fromdate" ).datepicker({
changeMonth: true,
changeYear: true,
yearRange: "1970:2032",
dateFormat:"d M yy"
});
});
$(function() {
$( "#todate" ).datepicker({
changeMonth: true,
changeYear: true,
yearRange: "1970:2032",
dateFormat:"d M yy"
});
});

</script>
<h3>Search Given Vaccines</h3>
<form action="search-sched-resultsdg.php" method="post" enctype="multipart/form-data" style="width:auto" name="1">
<label for="date">Show vaccines given on : &nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="text" name="date" id="date" style="margin-right:40px;"/>
<input type="submit" name="specificdate" value="Go" />
</form>
<form action="search-sched-resultsdg.php" method="post" enctype="multipart/form-data" style="width:auto" name="2">
	<label for="fromdate">From : &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="fromdate" id="fromdate" style="margin-right:40px;"/>
	<label for="todate">To : &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="todate" id="todate" style="margin-right:40px;"/>
	<input type="submit" name="tofromdate" value="Go" />
</form>
<form action="search-sched-resultsdg.php" method="post" enctype="multipart/form-data" style="width:auto" name="3">
	<label for="patientid">Patient ID : &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="patientid" id="patientid" style="margin-right:40px;"/>
	<input type="submit" name="patientsearch" value="Go" />
</form>
<?php include('footer.php'); ?>