<?php
/**
 * Reading and rebuilding the line items of an invoice.
 *
 * Line items are stored in two "*"-separated strings on the `invoice` row:
 * `descriptions` and `amounts`, with one entry each per line. A description
 * ending in "xx" is a free text line - see pdf-functions-invoice.php, where the
 * "xx" marker is what stops the word "Vaccination" being appended to it.
 */

/**
 * The stored line items of an invoice, as rows that can be shown in a form.
 */
function invoiceLinesForEdit($invoice) {
  $lines = array();
  $descriptions = explode("*", (string)$invoice['descriptions']);
  $amounts = explode("*", (string)$invoice['amounts']);
  foreach($descriptions as $i => $description) {
    if(trim($description) === '') {
      continue;
    }
    $freeText = (substr($description, -2) === 'xx');
    $lines[] = array(
      'description' => $freeText ? substr($description, 0, -2) : $description,
      'amount' => isset($amounts[$i]) ? trim($amounts[$i]) : '',
      'free_text' => $freeText,
    );
  }
  return $lines;
}

/**
 * Rebuild the stored descriptions/amounts strings from a submitted edit form.
 *
 * Returns array('descriptions' => ..., 'amounts' => ...), or FALSE with $error
 * set when the form is not valid.
 */
function invoiceLinesFromPost($post, &$error) {
  $error = '';
  $descriptions = array();
  $amounts = array();

  // Groups of fields, in the order they should appear on the invoice.
  //   free_text = TRUE  -> stored with the "xx" marker (typed in by hand)
  //   free_text = FALSE -> stored as is (picked from the product list)
  //   free_text = NULL  -> keep whatever the existing line was
  $groups = array(
    array('description' => 'description', 'amount' => 'amount', 'free_text' => null),
    array('description' => 'new_vac_description', 'amount' => 'new_vac_amount', 'free_text' => false),
    array('description' => 'new_description', 'amount' => 'new_amount', 'free_text' => true),
  );

  foreach($groups as $group) {
    $groupDescriptions = isset($post[$group['description']]) ? (array)$post[$group['description']] : array();
    $groupAmounts = isset($post[$group['amount']]) ? (array)$post[$group['amount']] : array();
    foreach($groupDescriptions as $i => $description) {
      $description = trim((string)$description);
      // An emptied out description means "remove this line".
      if($description === '' || strcasecmp($description, 'N/A') == 0) {
        continue;
      }
      $amount = isset($groupAmounts[$i]) ? trim((string)$groupAmounts[$i]) : '';
      if($amount !== '' && !is_numeric($amount)) {
        $error = "'".htmlspecialchars($amount)."' is not a valid amount (line: ".htmlspecialchars($description).").";
        return false;
      }
      if($amount !== '' && (float)$amount < 0) {
        $error = "Amounts cannot be negative (line: ".htmlspecialchars($description).").";
        return false;
      }
      // "*" is the separator used in the database, so it cannot appear in the text.
      $description = str_replace("*", "-", $description);
      $freeText = $group['free_text'];
      if($freeText === null) {
        $freeText = !empty($post['description_free_text'][$i]);
      }
      $descriptions[] = $freeText ? $description."xx" : $description;
      $amounts[] = $amount;
    }
  }

  if(!$descriptions) {
    $error = "An invoice needs at least one line with a description.";
    return false;
  }

  return array(
    'descriptions' => implode("*", $descriptions),
    'amounts' => implode("*", $amounts),
  );
}
?>
