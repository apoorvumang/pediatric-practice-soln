<?php include('header.php');
if(!($_GET['id'])) {
  echo 'Patient ID required';
  include('footer.php');
  exit;
}

$id = $_GET['id'];
$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT * from patients WHERE id={$id};"));
//Get invoices

$query = "SELECT * from invoice WHERE p_id={$id} ORDER BY date;";
$result = mysqli_query($link, $query);



?>
<h3>Invoices for <?php echo $patient["name"]; ?> </h3>
<form action="email-invoice.php" method="post" enctype="multipart/form-data" style="width:auto" name="1">
<input type="hidden" name="p_id" value="<?php echo "'{$patient['id']}'";?>" >
<input type="submit" value="Send invoice email">
<table>
  <tr>
    <th>Invoice</th>
    <th>Date</th>
    <th>Select</th>
  </tr>
<?php
while($invoice = mysqli_fetch_assoc($result)) {
  $date = date('j M Y', strtotime($invoice['date']));
  echo "<tr>";
  echo "<td><a href='pdf-invoice.php?id={$invoice['id']}'>".$invoice['descriptions']."</a></td>";
  echo "<td>".$date."</td>";
  echo "<td>";
  echo "<input type='checkbox' name='invoice_number[]' value={$invoice['id']}>";
  echo "</td>";
  echo "</tr>";
}
?>

</table>
<input type="submit" value="Send invoice email">
</form>

<?php

include('footer.php'); ?>
