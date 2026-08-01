<?php
/**
 * Edit an already created invoice - the line items / amounts, the discount and
 * the mode(s) of payment.
 *
 * Deliberately NOT editable: the invoice number, the doctor and the date. Those
 * three decide the invoice number series (SCPed/SCMed + financial year +
 * running number), so changing them would break the numbering of every invoice
 * that follows.
 */
include('header.php');
include_once('invoice-payments-func.php');
include_once('invoice-payment-split-ui.php');
include_once('invoice-lines-func.php');

if($_SESSION['type']!=='doctor') {
  echo "<h4>Only a doctor can edit invoices.</h4>";
  include('footer.php');
  exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if(!$id && isset($_POST['id'])) {
  $id = (int)$_POST['id'];
}
if(!$id) {
  echo "<h4>No invoice selected.</h4>";
  include('footer.php');
  exit();
}

function loadInvoiceForEdit($link, $id) {
  $id = (int)$id;
  $query = "SELECT i.id as id, i.invoice_id as invoice_id, i.p_id as p_id, i.date as date, i.mode as mode,
                   i.descriptions as descriptions, i.amounts as amounts, i.discount as discount,
                   i.doctor as doctor, p.name as pname
            FROM invoice i, patients p WHERE i.id = {$id} AND p.id = i.p_id";
  return mysqli_fetch_assoc(mysqli_query($link, $query));
}

$invoice = loadInvoiceForEdit($link, $id);
if(!$invoice) {
  echo "<h4>Invoice not found.</h4>";
  include('footer.php');
  exit();
}

$error = '';
$saved = false;

if(isset($_POST['submit'])) {
  $lines = invoiceLinesFromPost($_POST, $error);
  if($lines !== false) {
    $discount = trim((string)$_POST['discount']);
    if($discount === '') {
      $discount = '0';
    }
    if(!is_numeric($discount) || (float)$discount < 0) {
      $error = "'".htmlspecialchars($discount)."' is not a valid discount.";
    } else {
      $grandTotal = invoiceGrandTotalFromAmountsString($lines['amounts'], $discount);
      if($grandTotal < 0) {
        $error = "The discount is more than the total of the invoice.";
      } else {
        $payments = invoicePaymentsFromPost($_POST, $grandTotal, $error);
        if($payments !== false) {
          $mode = sanitizeInvoicePaymentMode($_POST['mode'], $invoice['mode']);
          if($payments) {
            $mode = dominantInvoicePaymentMode($payments, $mode);
          }
          $stmt = mysqli_prepare($link, "UPDATE invoice SET descriptions = ?, amounts = ?, discount = ?, mode = ? WHERE id = ?");
          if($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssi", $lines['descriptions'], $lines['amounts'], $discount, $mode, $id);
            if(mysqli_stmt_execute($stmt)) {
              $saved = true;
              if(!saveInvoicePayments($link, $id, $payments)) {
                $error = "The invoice was saved, but the split payment break-up could not be stored. "
                       ."The invoice now shows the whole amount as {$mode}.";
              }
              $invoice = loadInvoiceForEdit($link, $id);
            } else {
              $error = "Could not save the invoice.";
            }
            mysqli_stmt_close($stmt);
          } else {
            $error = "Could not save the invoice.";
          }
        }
      }
    }
  }
}

$lines = invoiceLinesForEdit($invoice);
$grandTotal = invoiceGrandTotalFromAmountsString($invoice['amounts'], $invoice['discount']);
$payments = getInvoicePayments($link, $id, $invoice['mode'], $grandTotal);

// Products, for the rows that let a new item be added.
$vaccines = array();
$result = mysqli_query($link, "SELECT * FROM vac_make WHERE for_invoice = 'Y' ORDER BY name ASC;");
while($vaccine = mysqli_fetch_assoc($result)) {
  $vaccines[] = $vaccine;
}
?>
<script type="text/javascript">
function updateAmountTotal() {
  var sum = 0;
  jQuery(".amounts").each(function() {
    var value = parseFloat(jQuery(this).val());
    if (!isNaN(value)) {
      sum += value;
    }
  });
  jQuery("#totalBeforeDiscount").val(sum);
  var discount = parseFloat(jQuery("#discount").val());
  if (isNaN(discount)) {
    discount = 0;
  }
  jQuery("#totalAmount").val(Math.round((sum - discount) * 100) / 100);
  if (typeof updateSplitPaymentUI === 'function') {
    updateSplitPaymentUI();
  }
}

function setDescriptionAndAmountValues(val, id) {
  jQuery('#' + id + 'amount').val(val.split('*')[0]);
  jQuery('#' + id + 'descript').val(val.split('*')[1]);
  updateAmountTotal();
}

function confirmEditInvoice() {
  var error = splitPaymentError();
  if (error !== '') {
    alert("Please check the payment break-up: " + error);
    return false;
  }
  return confirm('Save this invoice with total amount: ' + jQuery("#totalAmount").val()
    + ' and mode of payment: ' + paymentModeDescription() + '?');
}

jQuery(document).on("change keyup", ".amounts", function() {
  updateAmountTotal();
});

jQuery(document).on("change keyup", "#discount", function() {
  updateAmountTotal();
});

jQuery(document).ready(function() {
  updateAmountTotal();
});
</script>

<h3>Edit invoice <?php echo htmlspecialchars($invoice['invoice_id']); ?></h3>
<?php if($saved && !$error) { ?>
  <h4 style="color:#178017">Invoice saved.
    <a href="<?php echo "pdf-invoice.php?id={$id}"; ?>">Open the updated invoice (PDF)</a>
  </h4>
<?php } ?>
<?php if($error) { ?>
  <h4 style="color:#b00000"><?php echo $error; ?></h4>
<?php } ?>

<table style="width:auto">
  <tr><th>Invoice No.</th><td><?php echo htmlspecialchars($invoice['invoice_id']); ?></td></tr>
  <tr><th>Patient</th><td><?php echo htmlspecialchars($invoice['pname'])." (ID ".$invoice['p_id'].")"; ?></td></tr>
  <tr><th>Doctor</th><td><?php echo htmlspecialchars($invoice['doctor']); ?></td></tr>
  <tr><th>Date</th><td><?php echo date('j M Y', strtotime($invoice['date'])); ?></td></tr>
</table>
<p style="font-size:14px;color:#555">
  The invoice number, doctor and date cannot be changed - they decide the invoice numbering.
</p>

<form onsubmit="return confirmEditInvoice();" action="" method="post" enctype="multipart/form-data" style="width:auto">
<input type="hidden" name="id" value="<?php echo $id; ?>" />
  <p>
    <label>Description and amount</label>
    <br>
    <span style="font-size:14px;color:#555">Clear a description to remove that line from the invoice.</span>
    <br><br>
<?php foreach($lines as $i => $line) { ?>
    <input type="text" name="description[]" style="font-size:15px;width:320px" value="<?php echo htmlspecialchars($line['description']); ?>" />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="amount[]" class="amounts" style="font-size:15px" value="<?php echo htmlspecialchars($line['amount']); ?>" />
    <input type="hidden" name="<?php echo "description_free_text[{$i}]"; ?>" value="<?php echo $line['free_text'] ? '1' : '0'; ?>" />
    <br>
<?php } ?>
    <br>
    <span style="font-size:14px;color:#555">Add more items (optional):</span>
    <br>
<?php for($i = 0; $i < 2; $i++) { ?>
    <select name="selectBoxForVaccines[]" id="<?php echo "{$i}new"; ?>" onchange="setDescriptionAndAmountValues(this.value, this.id)" style="font-size:15px">
<?php foreach($vaccines as $vaccine) {
        $price = $vaccine['price'];
        $name = ($vaccine['description'] == "") ? $vaccine['name'] : $vaccine['name'].' ('.$vaccine['description'].')';
        $selected = ($name == 'N/A') ? " selected='selected'" : "";
        echo "<option value='".htmlspecialchars($price."*".$name)."'{$selected}>".htmlspecialchars($name)."</option>";
      } ?>
    </select>
    <input type="hidden" name="new_vac_description[]" id="<?php echo "{$i}newdescript"; ?>" />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="new_vac_amount[]" id="<?php echo "{$i}newamount"; ?>" class="amounts" style="font-size:15px" />
    <br>
<?php } ?>
<?php for($i = 0; $i < 2; $i++) { ?>
    <input type="text" name="new_description[]" style="font-size:15px;width:320px" />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="new_amount[]" class="amounts" style="font-size:15px" />
    <br>
<?php } ?>
    <br>
  </p>
  <p>Total before discount: <input type="text" name="totalBeforeDiscount" style="font-size:15px" id="totalBeforeDiscount" readonly="1" /></p>
  <p>Discount: <input type="text" name="discount" style="font-size:15px" id="discount" value="<?php echo htmlspecialchars($invoice['discount']); ?>" /></p>
  <p><strong>Final amount: Rs. </strong><input type="text" name="totalAmount" style="font-size:25px" id="totalAmount" readonly="1" /></p>
  <?php renderInvoicePaymentModeFields($invoice['mode'], $payments); ?>
  <p>
    <input type="submit" name="submit" value="Save invoice" />
  </p>
</form>

<p><a href="<?php echo "pdf-invoice.php?id={$id}"; ?>">Open this invoice (PDF)</a></p>

<?php
include('footer.php');
?>
