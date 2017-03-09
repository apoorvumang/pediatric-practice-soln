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


</script>
<h3>Search invoices</h3>
<form action="invoice-results.php" method="post" enctype="multipart/form-data" style="width:auto" name="1">
<label for="date">Show transactions on : &nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="text" name="date" id="date" style="margin-right:40px;"/>
<input type="submit" name="specificdate" value="Go" />
</form>
<?php include('footer.php'); ?>
