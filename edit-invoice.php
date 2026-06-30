<?php
require('connect.php');
include('header_db_link.php');
include('header.php');

session_name('tzLogin');
session_start();
error_reporting(0);

if($_SESSION['type'] !== 'doctor') {
  echo '<h2>Access Denied</h2>';
  exit;
}

function updateInvoice($link, $invoiceInfo) {
  $id = intval($invoiceInfo['id']);
  $mode = $invoiceInfo['mode'];
  $discount = $invoiceInfo['discount'];
  if($discount === '' || $discount === null) {
    $discount = '0';
  }

  // Re-join the description/amount line items into the '*'-separated form
  // used by the invoice table. Rows with an empty description are dropped,
  // and the matching amount is dropped with them so both strings stay
  // aligned (same number of entries).
  $descriptions = $invoiceInfo['description'];
  $amounts = $invoiceInfo['amount'];
  $descriptionConcat = "";
  $amountConcat = "";
  $length = sizeof($descriptions);
  for($i = 0; $i < $length; $i++) {
    if(trim($descriptions[$i]) === "") {
      continue;
    }
    $descriptionConcat .= $descriptions[$i]."*";
    $amountConcat .= $amounts[$i]."*";
  }
  $descriptionConcat = rtrim($descriptionConcat, '*');
  $amountConcat = rtrim($amountConcat, '*');

  $mode = mysqli_real_escape_string($link, $mode);
  $descriptionConcat = mysqli_real_escape_string($link, $descriptionConcat);
  $amountConcat = mysqli_real_escape_string($link, $amountConcat);
  $discount = mysqli_real_escape_string($link, $discount);

  $query = "UPDATE invoice SET mode='{$mode}', descriptions='{$descriptionConcat}', amounts='{$amountConcat}', discount='{$discount}' WHERE id={$id}";
  return mysqli_query($link, $query);
}

if(isset($_POST['submit'])) {
  if(updateInvoice($link, $_POST)) {
    Redirect("pdf-invoice.php?id=".intval($_POST['id']));
    exit;
  } else {
    echo "<h4>Error occured while updating invoice.</h4>";
  }
}

if(!isset($_GET['id'])) {
  echo '<h2>Access Denied</h2>';
  exit;
}

$id = intval($_GET['id']);
$invoice = mysqli_fetch_assoc(mysqli_query($link, "SELECT i.*, p.name as pname FROM invoice i, patients p WHERE i.id = {$id} AND i.p_id = p.id"));
if(!$invoice) {
  echo "<h4>Invoice not found.</h4>";
  include('footer.php');
  exit;
}

$descriptions = explode("*", $invoice['descriptions']);
$amounts = explode("*", $invoice['amounts']);
$currentMode = $invoice['mode'];
$standardModes = array('CASH' => 'Cash', 'CARD' => 'Card', 'PAYTM' => 'PayTM', 'UPI' => 'UPI');
?>
<script>
function updateEditTotal() {
  var sum = 0;
  $(".amounts").each(function(){
    sum += +$(this).val();
  });
  $("#totalBeforeDiscount").val(sum);
  sum = sum - (+$("#discount").val());
  $("#totalAmount").val(sum);
}
$(document).on("change keyup", ".amounts, #discount", function() {
  updateEditTotal();
});
$(function() { updateEditTotal(); });
</script>
<h4>Edit Invoice <?php echo htmlspecialchars($invoice['invoice_id']); ?> for <?php echo htmlspecialchars($invoice['pname']); ?></h4>
<form onsubmit="return confirm('Save changes to this invoice? Total amount: ' + document.getElementById('totalAmount').value + '.');" action="" method="post" enctype="multipart/form-data" style="width:auto">
  <input type="hidden" name="id" value="<?php echo $id; ?>" />
  <p>
    <label class="grey" for="mode">Mode of payment:&nbsp;&nbsp;</label>
    <select name="mode" id="mode" style="margin-right:60px;">
      <?php
        $matched = false;
        foreach($standardModes as $value => $display) {
          $selected = ($currentMode == $value) ? " selected='selected'" : "";
          if($currentMode == $value) { $matched = true; }
          echo "<option value='".htmlspecialchars($value)."'{$selected}>".htmlspecialchars($display)."</option>";
        }
        // Preserve any existing non-standard value (e.g. a split-payment
        // string) so editing the amount does not silently change the mode.
        if(!$matched && trim($currentMode) !== "") {
          echo "<option value='".htmlspecialchars($currentMode)."' selected='selected'>".htmlspecialchars($currentMode)." (current)</option>";
        }
      ?>
    </select>
  </p>
  <p>
    <label>Description and amount</label>
    <br><br>
    <?php
      // Existing line items, plus two blank rows for additions.
      $rowCount = max(sizeof($descriptions), sizeof($amounts)) + 2;
      for($i = 0; $i < $rowCount; $i++) {
        $desc = isset($descriptions[$i]) ? $descriptions[$i] : "";
        $amt = isset($amounts[$i]) ? $amounts[$i] : "";
        echo "<input type='text' name='description[]' value='".htmlspecialchars($desc)."' style='font-size:15px; width:300px;'/>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<input type='text' name='amount[]' value='".htmlspecialchars($amt)."' class='amounts' style='font-size:15px;'/>";
        echo "<br>";
      }
    ?>
    <br>
  </p>
  <p>Total before discount: <input type="text" id="totalBeforeDiscount" style="font-size:15px" readonly="1"/></p>
  <p>Discount: <input type="text" name="discount" id="discount" style="font-size:15px" value="<?php echo htmlspecialchars($invoice['discount']); ?>"/></p>
  <p><strong>Final amount: Rs. </strong><input type="text" id="totalAmount" style="font-size:25px" readonly="1"/></p>
  <p>
    <input type="submit" name="submit" value="Save changes" />
  </p>
</form>
<?php
include('footer.php');
?>
