

<?php include('header.php'); ?>
<script>
$(function() {
    $( ".datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1970:2032",
        dateFormat:"d M yy"
    });
});
</script>
<h3>Search Schedule</h3>
<a href="search-scheddg.php">Search for vaccines that have already been given</a>
<form action="search-sched-results.php" method="post" enctype="multipart/form-data" style="width:auto" name="1">
    <label for="date">Show schedules for : &nbsp;&nbsp;&nbsp;&nbsp;</label>
    <input type="text" name="date" id="date" class="datepicker" style="margin-right:40px;"/>
    <input type="checkbox" name="include_appointments" id="include_appointments" checked>
    <label for="include_appointments">Include consultation appointments</label>
    <input type="submit" name="specificdate" value="Go" />
</form>
<form action="search-sched-results.php" method="post" enctype="multipart/form-data" style="width:auto" name="2">
    <label for="fromdate">From : &nbsp;&nbsp;&nbsp;&nbsp;</label>
    <input type="text" name="fromdate" id="fromdate" class="datepicker" style="margin-right:40px;"/>
    <label for="todate">To : &nbsp;&nbsp;&nbsp;&nbsp;</label>
    <input type="text" name="todate" id="todate" class="datepicker" style="margin-right:40px;"/>
    <input type="checkbox" name="include_appointments" id="include_appointments2" checked>
    <label for="include_appointments2">Include consultation appointments</label>
    <input type="submit" name="tofromdate" value="Go" />
</form>
<form action="search-sched-results.php" method="post" enctype="multipart/form-data" style="width:auto" name="3">
    <label for="patientid">Patient ID : &nbsp;&nbsp;&nbsp;&nbsp;</label>
    <input type="text" name="patientid" id="patientid" style="margin-right:40px;"/>
    <input type="checkbox" name="include_appointments" id="include_appointments3" checked>
    <label for="include_appointments3">Include consultation appointments</label>
    <input type="submit" name="patientsearch" value="Go" />
</form>
<?php include('footer.php'); ?>