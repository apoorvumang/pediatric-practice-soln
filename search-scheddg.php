<?php include('header.php'); ?>
<script>
$(function() {
$( "#date" ).datepicker({
changeMonth: true,
changeYear: true,
yearRange: "1985:2032",
dateFormat:"d M yy"
});
});
$(function() {
$( "#fromdate" ).datepicker({
changeMonth: true,
changeYear: true,
yearRange: "1985:2032",
dateFormat:"d M yy"
});
});
$(function() {
$( "#todate" ).datepicker({
changeMonth: true,
changeYear: true,
yearRange: "1985:2032",
dateFormat:"d M yy"
});
});

</script>
<h3>Search Schedule</h3>
<form action="search-sched-resultsdg.php" method="post" enctype="multipart/form-data" style="width:auto" name="1">
<label for="date">Show schedules for : &nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="text" name="date" id="date" style="margin-right:40px;"/>
<input type="submit" name="date_given" value="Go" />
</form>
<?php include('footer.php'); ?>