<?php
require('connect.php');
include('header_db_link.php');
include('header.php');

session_name('tzLogin');
session_start();
error_reporting(0);

function addInvoice($link, $invoiceInfo) {
  $descriptionConcat = "";
  $p_id = $invoiceInfo["p_id"];
  $date = date('Y-m-d', strtotime($invoiceInfo['date']));
  $mode = $invoiceInfo["mode"];
  $length = sizeof($invoiceInfo['description']);
  for($i = 0; $i < $length; $i++) {
    if(strcmp($invoiceInfo['description'][$i],"") == 0) {
      continue;
    }
    $descriptionConcat = $descriptionConcat.$invoiceInfo['description'][$i].",";
  }
  $descriptionConcat = rtrim($descriptionConcat, ',');
  $amountConcat = "";
  $length = sizeof($invoiceInfo['amount']);
  for($i = 0; $i < $length; $i++) {
    // Keeping same as description because both strings must have same number
    // of entries (descriptionConcat and amountConcat)
    if(strcmp($invoiceInfo['description'][$i],"") == 0) {
      continue;
    }
    $amountConcat = $amountConcat.$invoiceInfo['amount'][$i].",";
  }
  $amountConcat = rtrim($amountConcat, ',');
  $query = "INSERT into invoice(p_id, date, mode, descriptions, amounts) VALUES ('{$p_id}', '{$date}', '{$mode}', '{$descriptionConcat}', '{$amountConcat}');";
  $retval = mysqli_query($link, $query);
  if($retval) {
    $invoiceId = mysqli_insert_id($link);
    return $invoiceId;
  }
  else {
    echo "Error occured while creating invoice.";
    return 0;
  }
}

if (isset($_POST['submit'])) { //If the new invoice form has been submitted
                //Returns 0 on error, otherwise new invoice id
	$retval = addInvoice($link, $_POST);

	if ($retval) {
		Redirect("pdf-invoice.php?id={$retval}");
		exit;
	}
}

if((!isset($_GET['id']))||(!(isset($_SESSION['id'])||isset($_SESSION['username']))))
{
	echo '<h2>Access Denied</h2>';
	exit;
}
if($_GET["id"]) {
  $patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT name from patients where id = {$_GET['id']};"));
  $patientName = $patient["name"];
}
?>
<script>
$(function() {
  $("#date").datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: "1970:2032",
    dateFormat:"d M yy"
  });
});
</script>
<h4>Create Invoice for <?php echo $patientName; ?></h4>
<form action="" method="post" enctype="multipart/form-data" style="width:auto">
  <input type="hidden" name="p_id" value=<?php echo "'".$_GET['id']."'"; ?> />
  <p>
    <label for="date">Date:&nbsp;&nbsp;</label>
    <input type="text" name="date" id="date" value= <?php echo "'".date('j M Y')."'";?>/>
  </p>
  <p>
    <label class="grey" for="mode">Mode of payment:&nbsp;&nbsp;</label>
    <select name="mode" style="margin-right:60px;">
      <option value='CASH'>Cash</option>
      <option value='CARD'>Card</option>
      <option value='PAYTM'>PayTM</option>
    </select>
  </p>
  <p>
    <label>Description and amount </label>
    <br>
    <br>
    <input type="text" name="description[]" value="Consultation"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="amount[]" value = "400"/>
    <br>
    <input type="text" name="description[]"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="amount[]"/>
    <br>
    <input type="text" name="description[]"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="amount[]"/>
    <br>
    <input type="text" name="description[]"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="amount[]"/>
    <br>
    <input type="text" name="description[]"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="amount[]"/>
    <br>
  </p>
  <p>
  	<input type="submit" name="submit" value="Create invoice"/>
  </p>
</form>


<?php
include('footer.php');
?>
