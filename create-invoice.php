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
    if(strcmp($invoiceInfo['description'][$i],"") == 0 || strcmp($invoiceInfo['description'][$i],"N/A") == 0 ) {
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
    if(strcmp($invoiceInfo['description'][$i],"") == 0 || strcmp($invoiceInfo['description'][$i],"N/A") == 0 ) {
      continue;
    }
    $amountConcat = $amountConcat.$invoiceInfo['amount'][$i].",";
  }
  $amountConcat = rtrim($amountConcat, ',');
  $time = time();
  $stime = "$time";
  $finaltime = substr($stime,-3);
  $invoice_id = "SP".$p_id.$finaltime;


	$month = intval(date('n', strtotime($invoiceInfo["date"])));
	$year1 = intval(date('y', strtotime($invoiceInfo["date"])));
	if($month <=3) {
		$year1 = $year1 - 1;
	}
	$year2 = $year1 + 1;
	$year = sprintf( "%d-%d", $year1, $year2);


  $query = "SELECT * FROM invoice	 WHERE invoice_id like 'SCPed/".$year."/%' ORDER BY id desc";
  $row = mysqli_fetch_assoc(mysqli_query($link, $query));
  if($row['invoice_id']) {
    $invoice_id_number = intval(substr($row['invoice_id'], -5));
    $invoice_id_new_number = $invoice_id_number + 1;
    $invoice_id = sprintf("SCPed/".$year."/%05d", $invoice_id_new_number);
  } else {
    $invoice_id = "SCPed/".$year."/00001";
  }

  $query = "INSERT into invoice(p_id, date, mode, descriptions, amounts, invoice_id) VALUES ('{$p_id}', '{$date}', '{$mode}', '{$descriptionConcat}', '{$amountConcat}', '{$invoice_id}');";
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

function setDescriptionAndAmountValues(val, id) {
  console.log('#'+id+'amount');
  $('#'+id+'amount').val(val.split(',')[0]);
  $('#'+id+'descript').val(val.split(',')[1]);
}

$(document).ready(function () {
  $('#productName').change(function () {
    console.log('ckicked');
    $('#kjhkh').val('200');
  })

});

</script>
<h4>Create Invoice for <?php echo $patientName; ?></h4>
<form onsubmit="return confirm('Create invoice?');" action="" method="post" enctype="multipart/form-data" style="width:auto" >
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
    <?php
      $query = "SELECT * FROM vac_make WHERE for_invoice = 'Y' ORDER BY name ASC;";
      $result = mysqli_query($link, $query);
      $vaccines = [];
      while($vaccine = mysqli_fetch_assoc($result)){
        $vaccines[] = $vaccine;
      }
      for ($i=0; $i < 5; $i++) {

        ?>
        <select name="selectBoxForVaccines[]" id=<?php echo "'{$i}consult'" ?> onchange="setDescriptionAndAmountValues(this.value, this.id)" style="font-size:15px">
        <?php
          foreach ($vaccines as $key => $vaccine) {
            # code...
          $price = $vaccine['price'];
          if($vaccine['name'] == 'N/A') {
            $name = $vaccine['name'];
          } else {
            $name = $vaccine['name'].' ('.$vaccine['description'].')';
          }          
          if($name=='N/A') {
            echo "<option value='{$price},{$name}' selected='selected'>";
          }
          else {
            echo "<option value='{$price},{$name}'>";
          }
          echo $name;
          echo "</option>";
        }
        echo "</select>";
        echo "<input type='hidden' name='description[]' id='{$i}consultdescript'/>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<input type='text' name='amount[]' id='{$i}consultamount'/>";

        echo "<br>";
      }
    ?>

    <input type="text" name="description[]" style="font-size:15px"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="amount[]"/ style="font-size:15px">
    <br>
    <input type="text" name="description[]" style="font-size:15px"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="amount[]" style="font-size:15px"/>
    <br>
    <br>
  </p>
  <p>
  	<input type="submit" name="submit" value="Create invoice" />
  </p>

</form>


<?php
include('footer.php');
?>
