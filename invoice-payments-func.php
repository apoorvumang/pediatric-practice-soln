<?php
/**
 * Helpers for invoices that are paid using more than one mode of payment
 * (for example part cash + part UPI on a single invoice).
 *
 * Storage design (kept deliberately backwards compatible):
 *
 *  - `invoice`.`mode` KEEPS holding one of the existing values
 *    (CASH / CARD / PAYTM / UPI). For a split payment it holds the mode with
 *    the largest amount. Nothing that already reads that column can therefore
 *    break, and no change to the `invoice` table is needed.
 *
 *  - The break-up of a split payment is stored in a new table
 *    `invoice_payments` (invoice_id, mode, amount). Rows are written ONLY for
 *    invoices that are actually split across two or more modes.
 *    An invoice with no rows there - i.e. every invoice created before this
 *    feature, and every single-mode invoice created after it - is treated as
 *    one payment of the whole amount in `invoice`.`mode`.
 *
 *  - If the `invoice_payments` table does not exist yet, every read falls back
 *    to `invoice`.`mode` and the application keeps working exactly as before.
 *    The table is created on demand (CREATE TABLE IF NOT EXISTS) the first
 *    time a split payment is saved. It can also be created by hand:
 *
 *      CREATE TABLE IF NOT EXISTS `invoice_payments` (
 *        `id` int(11) NOT NULL AUTO_INCREMENT,
 *        `invoice_id` int(11) NOT NULL,
 *        `mode` varchar(20) NOT NULL,
 *        `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
 *        PRIMARY KEY (`id`),
 *        KEY `invoice_id` (`invoice_id`)
 *      );
 */

/**
 * The modes of payment offered on the invoice screens.
 * Keys are what gets stored, values are what is shown to the user.
 */
function invoicePaymentModes() {
  return array(
    'CASH'  => 'Cash',
    'CARD'  => 'Card',
    'PAYTM' => 'PayTM',
    'UPI'   => 'UPI',
  );
}

/**
 * Order the split rows are shown in. Part cash + part UPI is by far the most
 * common split, so those two come first and the usual case needs no fiddling
 * with the dropdowns.
 */
function invoiceSplitRowModeOrder() {
  return array('CASH', 'UPI', 'CARD', 'PAYTM');
}

/**
 * Only ever store a mode we know about - this keeps the value safe to put in
 * the `mode` column no matter how narrowly that column is defined.
 */
function sanitizeInvoicePaymentMode($mode, $default = 'CASH') {
  $modes = invoicePaymentModes();
  $mode = strtoupper(trim((string)$mode));
  if (isset($modes[$mode])) {
    return $mode;
  }
  return $default;
}

/**
 * Create `invoice_payments` if it is not there yet. Returns TRUE when the
 * table is available for writing.
 */
function ensureInvoicePaymentsTable($link) {
  static $ensured = null;
  if ($ensured !== null) {
    return $ensured;
  }
  $query = "CREATE TABLE IF NOT EXISTS `invoice_payments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `invoice_id` int(11) NOT NULL,
    `mode` varchar(20) NOT NULL,
    `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
    PRIMARY KEY (`id`),
    KEY `invoice_id` (`invoice_id`)
  )";
  // mysqli throws on failure on PHP 8+, and returns FALSE on PHP 7 - handle both
  // so that a missing privilege can never take a page down.
  try {
    $ensured = mysqli_query($link, $query) ? true : false;
  } catch (Exception $e) {
    $ensured = false;
  }
  return $ensured;
}

/**
 * TRUE if the table exists. Used on the read paths so that a missing table
 * never turns into a visible error.
 */
function invoicePaymentsTableExists($link) {
  static $exists = null;
  if ($exists !== null) {
    return $exists;
  }
  try {
    $result = mysqli_query($link, "SHOW TABLES LIKE 'invoice_payments'");
    $exists = ($result && mysqli_num_rows($result) > 0);
  } catch (Exception $e) {
    $exists = false;
  }
  return $exists;
}

/**
 * Grand total of an invoice from its stored "*"-separated amounts string.
 */
function invoiceGrandTotalFromAmountsString($amountsString, $discount) {
  $total = 0;
  $amounts = explode("*", (string)$amountsString);
  foreach ($amounts as $amount) {
    $total += (float)trim($amount);
  }
  return $total - (float)$discount;
}

/**
 * Rs. amounts are whole numbers in practice - only show paise when there are
 * any.
 */
function formatInvoiceAmount($amount) {
  $amount = (float)$amount;
  if (abs($amount - round($amount)) < 0.005) {
    return (string)(int)round($amount);
  }
  return number_format($amount, 2, '.', '');
}

/**
 * Build the list of payments from a submitted create/edit invoice form.
 *
 * Returns:
 *   array()      - not a split payment, use the single `mode` dropdown
 *   array(...)   - two or more array('mode' => ..., 'amount' => ...) entries
 *   FALSE        - the form is not valid, $error holds the message to show
 */
function invoicePaymentsFromPost($post, $grandTotal, &$error) {
  $error = '';
  if (empty($post['split_payment'])) {
    return array();
  }
  $modes = isset($post['split_mode']) ? (array)$post['split_mode'] : array();
  $amounts = isset($post['split_amount']) ? (array)$post['split_amount'] : array();

  $payments = array();
  foreach ($amounts as $i => $rawAmount) {
    $rawAmount = trim((string)$rawAmount);
    if ($rawAmount === '') {
      continue;
    }
    if (!is_numeric($rawAmount)) {
      $error = "Payment amount '".htmlspecialchars($rawAmount)."' is not a number.";
      return false;
    }
    $amount = (float)$rawAmount;
    if ($amount < 0) {
      $error = "Payment amounts cannot be negative.";
      return false;
    }
    if ($amount == 0) {
      continue;
    }
    $mode = sanitizeInvoicePaymentMode(isset($modes[$i]) ? $modes[$i] : '');
    // Same mode entered twice - add the two amounts together.
    if (isset($payments[$mode])) {
      $payments[$mode] += $amount;
    } else {
      $payments[$mode] = $amount;
    }
  }

  if (count($payments) < 2) {
    $error = "A split payment needs an amount against at least two different modes of payment. "
           ."Either fill in the second mode, or untick 'Split across multiple modes'.";
    return false;
  }

  $splitTotal = array_sum($payments);
  if (abs($splitTotal - (float)$grandTotal) > 0.01) {
    $error = "The split payment adds up to Rs. ".formatInvoiceAmount($splitTotal)
           ." but the invoice total is Rs. ".formatInvoiceAmount($grandTotal)
           .". Please make the two match.";
    return false;
  }

  $list = array();
  foreach ($payments as $mode => $amount) {
    $list[] = array('mode' => $mode, 'amount' => $amount);
  }
  return $list;
}

/**
 * The mode that should go into `invoice`.`mode` - the one with the largest
 * amount, so that any code reading only that column sees a sensible value.
 */
function dominantInvoicePaymentMode($payments, $default = 'CASH') {
  $best = null;
  $bestAmount = -1;
  foreach ($payments as $payment) {
    if ((float)$payment['amount'] > $bestAmount) {
      $bestAmount = (float)$payment['amount'];
      $best = $payment['mode'];
    }
  }
  if ($best === null) {
    return sanitizeInvoicePaymentMode($default);
  }
  return sanitizeInvoicePaymentMode($best, $default);
}

/**
 * Replace the stored break-up for an invoice. Pass an empty array for a
 * single-mode payment - that removes any earlier break-up and takes the
 * invoice back to plain `invoice`.`mode` behaviour.
 */
function saveInvoicePayments($link, $invoiceId, $payments) {
  $invoiceId = (int)$invoiceId;
  if (!$invoiceId) {
    return false;
  }
  try {
    if (empty($payments)) {
      // Nothing to store. Only touch the table if it is actually there.
      if (invoicePaymentsTableExists($link)) {
        mysqli_query($link, "DELETE FROM invoice_payments WHERE invoice_id = {$invoiceId}");
      }
      return true;
    }
    if (!ensureInvoicePaymentsTable($link)) {
      return false;
    }
    mysqli_query($link, "DELETE FROM invoice_payments WHERE invoice_id = {$invoiceId}");
    $stmt = mysqli_prepare($link, "INSERT INTO invoice_payments (invoice_id, mode, amount) VALUES (?, ?, ?)");
    if (!$stmt) {
      return false;
    }
    $ok = true;
    foreach ($payments as $payment) {
      $mode = sanitizeInvoicePaymentMode($payment['mode']);
      $amount = (float)$payment['amount'];
      mysqli_stmt_bind_param($stmt, "isd", $invoiceId, $mode, $amount);
      if (!mysqli_stmt_execute($stmt)) {
        $ok = false;
      }
    }
    mysqli_stmt_close($stmt);
    return $ok;
  } catch (Exception $e) {
    return false;
  }
}

/**
 * Break-up for one invoice. Always returns at least one entry: invoices
 * without a stored break-up fall back to the single `invoice`.`mode`.
 */
function getInvoicePayments($link, $invoiceId, $fallbackMode, $fallbackAmount) {
  $fallback = array(array(
    'mode' => sanitizeInvoicePaymentMode($fallbackMode, $fallbackMode),
    'amount' => (float)$fallbackAmount,
  ));
  $invoiceId = (int)$invoiceId;
  if (!$invoiceId || !invoicePaymentsTableExists($link)) {
    return $fallback;
  }
  try {
    $result = mysqli_query($link, "SELECT mode, amount FROM invoice_payments WHERE invoice_id = {$invoiceId} ORDER BY id");
  } catch (Exception $e) {
    return $fallback;
  }
  if (!$result || mysqli_num_rows($result) == 0) {
    return $fallback;
  }
  $payments = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $payments[] = array('mode' => $row['mode'], 'amount' => (float)$row['amount']);
  }
  return $payments;
}

/**
 * Break-ups for many invoices in one query, for listing pages.
 * $invoices must be an array of array('id' => .., 'mode' => .., 'total' => ..).
 * Returns a map of invoice id => list of payments.
 */
function getInvoicePaymentsForInvoices($link, $invoices) {
  $map = array();
  $ids = array();
  foreach ($invoices as $invoice) {
    $id = (int)$invoice['id'];
    $map[$id] = array(array(
      'mode' => sanitizeInvoicePaymentMode($invoice['mode'], $invoice['mode']),
      'amount' => (float)$invoice['total'],
    ));
    $ids[] = $id;
  }
  if (!$ids || !invoicePaymentsTableExists($link)) {
    return $map;
  }
  $idList = implode(",", $ids);
  try {
    $result = mysqli_query($link, "SELECT invoice_id, mode, amount FROM invoice_payments WHERE invoice_id IN ({$idList}) ORDER BY id");
  } catch (Exception $e) {
    return $map;
  }
  if (!$result) {
    return $map;
  }
  $found = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $id = (int)$row['invoice_id'];
    if (!isset($found[$id])) {
      $found[$id] = array();
    }
    $found[$id][] = array('mode' => $row['mode'], 'amount' => (float)$row['amount']);
  }
  foreach ($found as $id => $payments) {
    $map[$id] = $payments;
  }
  return $map;
}

/**
 * "CASH" for a single payment, "CASH 500 + UPI 300" for a split one.
 */
function formatInvoicePaymentSplit($payments, $separator = ' + ') {
  if (count($payments) == 1) {
    return $payments[0]['mode'];
  }
  $parts = array();
  foreach ($payments as $payment) {
    $parts[] = $payment['mode']." ".formatInvoiceAmount($payment['amount']);
  }
  return implode($separator, $parts);
}
?>
