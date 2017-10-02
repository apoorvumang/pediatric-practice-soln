<?php include('header.php');
if($_SESSION['type']!=='doctor') {
  exit();
}
?>
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
$( "#dateFrom" ).datepicker({
changeMonth: true,
changeYear: true,
yearRange: "1970:2032",
dateFormat:"d M yy"
});
});

$(function() {
$( "#dateTo" ).datepicker({
changeMonth: true,
changeYear: true,
yearRange: "1970:2032",
dateFormat:"d M yy"
});
});

</script>
<h3>Search invoices</h3>
<form action="invoice-results.php" method="get" enctype="multipart/form-data" style="width:auto" name="1">
<label for="doctor">Doctor : &nbsp;&nbsp;&nbsp;&nbsp;</label>
<select name="doctor" id="doctor" style="margin-right:60px;">
  <option value='Dr. Mahima'>Dr. Mahima</option>
  <option value='Dr. Anurag'>Dr. Anurag</option>
  <option value="Both" selected="1">Both</option>
</select>
<br>
<label for="date">Show transactions on : &nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="text" name="date" id="date" style="margin-right:40px;"/>
<input type="submit" name="specificdate" value="Go" />

<br>
<label for="dateFrom">Show transactions from : &nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="text" name="dateFrom" id="dateFrom" style="margin-right:40px;"/>
<label for="dateTo">To : &nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="text" name="dateTo" id="dateTo" style="margin-right:40px;"/>
<input type="submit" name="dateRange" value="Go" />
</form>
<?php include('footer.php'); ?>
