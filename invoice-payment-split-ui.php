<?php
/**
 * Shared "mode of payment" form fields for create-invoice.php and
 * edit-invoice.php - a single mode dropdown, plus an optional break-up when
 * the patient pays one invoice in more than one mode (part cash, part UPI...).
 *
 * See invoice-payments-func.php for how this is stored.
 */
include_once('invoice-payments-func.php');

/**
 * $selectedMode - value for the single mode dropdown
 * $payments     - existing break-up (as returned by getInvoicePayments), used
 *                 to pre-tick and pre-fill the split rows when editing
 */
function renderInvoicePaymentModeFields($selectedMode, $payments = array()) {
  $modes = invoicePaymentModes();
  $selectedMode = sanitizeInvoicePaymentMode($selectedMode);
  $isSplit = (count($payments) > 1);

  // Rows to show: the existing break-up first, then the unused modes so that
  // every mode is one click away.
  $rows = array();
  $used = array();
  if ($isSplit) {
    foreach ($payments as $payment) {
      $mode = sanitizeInvoicePaymentMode($payment['mode']);
      $rows[] = array('mode' => $mode, 'amount' => formatInvoiceAmount($payment['amount']));
      $used[$mode] = true;
    }
  }
  foreach (invoiceSplitRowModeOrder() as $mode) {
    if (count($rows) >= count($modes)) {
      break;
    }
    if (!isset($used[$mode])) {
      $rows[] = array('mode' => $mode, 'amount' => '');
      $used[$mode] = true;
    }
  }
?>
  <p>
    <label class="grey" for="mode">Mode of payment:&nbsp;&nbsp;</label>
    <select name="mode" id="mode" style="margin-right:20px;">
<?php foreach ($modes as $value => $label) { ?>
      <option value="<?php echo $value; ?>"<?php if ($value == $selectedMode) echo ' selected="selected"'; ?>><?php echo $label; ?></option>
<?php } ?>
    </select>
    <label for="split_payment" style="font-size:14px;">
      <input type="checkbox" name="split_payment" id="split_payment" value="1" onchange="updateSplitPaymentUI()"<?php if ($isSplit) echo ' checked="checked"'; ?> />
      Paid in more than one mode (part cash, part UPI, ...)
    </label>
  </p>
  <div id="splitPaymentBox" style="display:none;border:1px solid #cccccc;padding:10px 15px;margin:0 0 15px 0;max-width:430px;background-color:#f7f7f7;">
    <p style="margin-top:0;font-size:14px;">
      Enter how much was paid in each mode. Leave a row blank if it was not used.
      The rows must add up to the final amount of the invoice.
    </p>
<?php foreach ($rows as $i => $row) { ?>
    <p style="margin:5px 0;">
      <select name="split_mode[]" style="font-size:15px;width:110px;" onchange="updateSplitPaymentUI()">
<?php foreach ($modes as $value => $label) { ?>
        <option value="<?php echo $value; ?>"<?php if ($value == $row['mode']) echo ' selected="selected"'; ?>><?php echo $label; ?></option>
<?php } ?>
      </select>
      &nbsp;&nbsp;
      <input type="text" name="split_amount[]" class="splitAmounts" style="font-size:15px;width:100px;" value="<?php echo htmlspecialchars($row['amount']); ?>" />
    </p>
<?php } ?>
    <p style="font-size:14px;margin-bottom:0;">
      Entered: <b>Rs. <span id="splitPaymentEntered">0</span></b>
      &nbsp;<span id="splitPaymentMessage"></span>
    </p>
  </div>
  <script type="text/javascript">
  // Total of the split rows.
  function splitPaymentEnteredTotal() {
    var sum = 0;
    jQuery(".splitAmounts").each(function() {
      var value = parseFloat(jQuery(this).val());
      if (!isNaN(value)) {
        sum += value;
      }
    });
    return Math.round(sum * 100) / 100;
  }

  function invoiceFinalAmount() {
    var value = parseFloat(jQuery("#totalAmount").val());
    return isNaN(value) ? 0 : Math.round(value * 100) / 100;
  }

  function isSplitPayment() {
    return jQuery("#split_payment").is(":checked");
  }

  // '' when the form can be submitted, otherwise the problem to show.
  function splitPaymentError() {
    if (!isSplitPayment()) {
      return '';
    }
    var filled = 0;
    var bad = '';
    jQuery(".splitAmounts").each(function() {
      var raw = jQuery.trim(jQuery(this).val());
      if (raw === '') {
        return;
      }
      if (isNaN(parseFloat(raw)) || !isFinite(raw)) {
        bad = raw;
        return;
      }
      if (parseFloat(raw) > 0) {
        filled++;
      }
    });
    if (bad !== '') {
      return "'" + bad + "' is not a valid amount.";
    }
    // Show what is left to enter first - that is the useful message while the
    // amounts are still being typed in.
    var difference = Math.round((splitPaymentEnteredTotal() - invoiceFinalAmount()) * 100) / 100;
    if (difference > 0) {
      return "Rs. " + difference + " more than the final amount of the invoice.";
    }
    if (difference < 0) {
      return "Rs. " + (-difference) + " still to be entered.";
    }
    if (filled < 2) {
      return "Enter an amount against at least two modes of payment.";
    }
    return '';
  }

  function updateSplitPaymentUI() {
    var split = isSplitPayment();
    jQuery("#splitPaymentBox").toggle(split);
    // The single dropdown is only meaningful when the payment is not split.
    jQuery("#mode").prop("disabled", split);
    if (!split) {
      return;
    }
    jQuery("#splitPaymentEntered").text(splitPaymentEnteredTotal());
    var error = splitPaymentError();
    if (error === '') {
      jQuery("#splitPaymentMessage").css("color", "#178017").text("Adds up to the invoice total.");
    } else {
      jQuery("#splitPaymentMessage").css("color", "#b00000").text(error);
    }
  }

  // Text shown in the confirm box, and used by the invoice pages before submit.
  function paymentModeDescription() {
    if (!isSplitPayment()) {
      var mode = jQuery("#mode");
      return mode.find("option:selected").text();
    }
    var parts = [];
    jQuery(".splitAmounts").each(function() {
      var raw = jQuery.trim(jQuery(this).val());
      if (raw === '' || !(parseFloat(raw) > 0)) {
        return;
      }
      var mode = jQuery(this).closest("p").find("select option:selected").text();
      parts.push(mode + " " + parseFloat(raw));
    });
    return parts.join(" + ");
  }

  jQuery(document).on("change keyup", ".splitAmounts", function() {
    updateSplitPaymentUI();
  });

  jQuery(document).ready(function() {
    updateSplitPaymentUI();
  });
  </script>
<?php
}
?>
