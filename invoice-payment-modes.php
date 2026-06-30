<?php
/**
 * Helpers for invoices that may be settled using more than one payment mode
 * in a single invoice (e.g. part cash, part UPI).
 *
 * To avoid a schema change on the per-doctor databases, the split is encoded
 * inside the existing single `invoice.mode` column:
 *
 *   - Single mode (legacy / default):  "CASH"
 *   - Split across modes:              "CASH:300+UPI:200"
 *
 * A value is treated as a split when it contains a ':'. Everything else is
 * treated as a plain single-mode value exactly as before, so existing rows
 * keep working untouched.
 */

if (!function_exists('invoiceModeIsSplit')) {

  function invoiceModeIsSplit($mode) {
    return strpos((string)$mode, ':') !== false;
  }

  /**
   * Formats a numeric amount without trailing ".00" noise (amounts are
   * whole rupees in practice, but decimals are preserved if present).
   */
  function invoiceFormatAmount($amount) {
    $amount = floatval($amount);
    if ($amount == intval($amount)) {
      return (string) intval($amount);
    }
    return rtrim(rtrim(number_format($amount, 2, '.', ''), '0'), '.');
  }

  /**
   * Returns an associative array of mode => amount for an invoice.
   *
   * For a legacy single-mode invoice the whole $grandTotal is attributed to
   * that mode. For a split invoice the per-mode amounts are read from the
   * stored string and $grandTotal is ignored.
   */
  function invoiceParsePaymentModes($mode, $grandTotal) {
    $mode = trim((string)$mode);
    if ($mode === '') {
      return array();
    }
    if (!invoiceModeIsSplit($mode)) {
      return array($mode => floatval($grandTotal));
    }
    $result = array();
    foreach (explode('+', $mode) as $part) {
      if (strpos($part, ':') === false) {
        continue;
      }
      list($m, $amt) = explode(':', $part, 2);
      $m = trim($m);
      if ($m === '') {
        continue;
      }
      if (!isset($result[$m])) {
        $result[$m] = 0;
      }
      $result[$m] += floatval($amt);
    }
    return $result;
  }

  /**
   * Human-readable representation of the payment mode(s) for display on the
   * invoice PDF and in listings, e.g. "CASH" or "CASH: 300, UPI: 200".
   */
  function invoiceFormatPaymentModes($mode) {
    $mode = trim((string)$mode);
    if (!invoiceModeIsSplit($mode)) {
      return $mode;
    }
    $pieces = array();
    foreach (invoiceParsePaymentModes($mode, 0) as $m => $amt) {
      $pieces[] = $m . ': ' . invoiceFormatAmount($amt);
    }
    return implode(', ', $pieces);
  }

  /**
   * Builds the value to store in the `mode` column from an array of
   * mode => amount. Zero/empty amounts are dropped. When exactly one mode
   * has a value, the legacy single-mode string is returned for backwards
   * compatibility.
   */
  function invoiceBuildModeString($modeAmounts) {
    $clean = array();
    foreach ($modeAmounts as $m => $amt) {
      $m = trim($m);
      $amt = floatval($amt);
      if ($m === '' || $amt <= 0) {
        continue;
      }
      if (!isset($clean[$m])) {
        $clean[$m] = 0;
      }
      $clean[$m] += $amt;
    }
    if (count($clean) === 0) {
      return '';
    }
    if (count($clean) === 1) {
      return (string) array_keys($clean)[0];
    }
    $pieces = array();
    foreach ($clean as $m => $amt) {
      $pieces[] = $m . ':' . invoiceFormatAmount($amt);
    }
    return implode('+', $pieces);
  }
}
?>
