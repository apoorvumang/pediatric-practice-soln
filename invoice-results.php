<?php include('header.php');
if($_SESSION['type']!=='doctor') {
  exit();
}
//What needs to be done on this page:
// List out all schedules for a particular date, in a form.
// The dates *only* can be edited. Give a link for the patient also.
?>

<h3>Search Results for invoice</h3>
<h4>Totals for <?php
if($_GET['specificdate']) {
  echo $_GET['date'];
} else if($_GET['dateRange']) {
  echo $_GET['dateFrom']." to ".$_GET['dateTo'];
} else if($_GET['patientID']) {
  $query = "SELECT name FROM patients WHERE id = {$_GET['p_id']}";
  $row = mysqli_fetch_assoc(mysqli_query($link, $query));
  echo "Invoices for {$row['name']}";
}
 ?></h4>
<p style="font-size:16px;" id="amountTotalsByType">Nothing here!</p>
<?php
if($_POST['delete']) {
  $array = $_POST['delete'];
  $concat = "";
  foreach ($array as $key => $value) {
      $concat = $concat.$value.",";
  }
  $concat = rtrim($concat, ",");
  $query = "DELETE from invoice where id in(".$concat.")";
  if(mysqli_query($link, $query)) {
      echo "Succesfully deleted invoices!";
      $query = "DELETE FROM visit_invoices WHERE invoice_id IN(".$concat.")";
      if(mysqli_query($link, $query)) {
          echo "Succesfully unlinked invoice_id from visit!";
      } else {
          echo "Error in unlinking invoice_id from visit!";
      }
  } else {
      echo "Unable to delete invoices";
  }
}
if($_GET['specificdate'] || $_GET['dateRange'] || $_GET['patientID'])  //If some submit button clicked
{
  $date = date('Y-m-d', strtotime($_GET['date']));
  $dateFrom = date('Y-m-d', strtotime($_GET['dateFrom']));
  $dateTo = date('Y-m-d', strtotime($_GET['dateTo']));
  $date = mysqli_real_escape_string($link, $date);
  $doctor = $_GET['doctor'];
  if($doctor == "Both") {
    $doctor_query = "";
  } else {
    $doctor_query = "AND i.doctor = '{$doctor}' ";
  }
  if($_GET['specificdate']) {
    $query = "SELECT i.discount as discount, i.invoice_id as invoice_id, i.id, i.p_id as pid, i.date as date, i.mode as mode, p.name as pname, i.descriptions as descriptions, i.amounts as amounts, i.doctor as doctor FROM invoice i, patients p WHERE i.date='".$date."' AND i.p_id = p.id {$doctor_query} ORDER BY i.id";
  } else if($_GET['dateRange']){
    $query = "SELECT i.discount as discount, i.invoice_id as invoice_id, i.id, i.p_id as pid, i.date as date, i.mode as mode, p.name as pname, i.descriptions as descriptions, i.amounts as amounts, i.doctor as doctor FROM invoice i, patients p WHERE i.date>='".$dateFrom."' AND i.date<='{$dateTo}' AND i.p_id = p.id {$doctor_query} ORDER BY i.id";
  } else if($_GET['patientID']) {
    $query = "SELECT i.discount as discount, i.invoice_id as invoice_id, i.id, i.p_id as pid, i.date as date, i.mode as mode, p.name as pname, i.descriptions as descriptions, i.amounts as amounts, i.doctor as doctor FROM invoice i, patients p WHERE i.p_id = p.id AND p.id = {$_GET['p_id']} {$doctor_query} ORDER BY i.id";
  }

  $result = mysqli_query($link, $query);
  $nrows = mysqli_num_rows($result);
?>
<form action="" method="post" enctype="multipart/form-data" style="width:auto" name="1">
<table>
<tbody>
<tr>
<th>Invoice ID</th>
<th>Doctor</th>
<th>Patient ID</th>
<th>Patient</th>
<th>Date</th>
<th>Mode</th>
<th>Descriptions</th>
<th>Amounts</th>
<th>Discount</th>
<th>Total</th>
<th>Delete</th>
</tr>
<?php
$count = 0;
$cash = 0;
$card = 0;
$paytm = 0;
$upi = 0;
while($row = mysqli_fetch_assoc($result))
{
?>
<tr>
<td>
<?php echo $row['invoice_id'];?>
</td>
<td>
<?php echo $row['doctor'];?>
</td>
<td>
<?php echo "<b>".$row['pid']."</b>";?>
</td>
<td>
<a href= <?php echo "\"pdf-invoice.php?id={$row['id']}\""; ?> ><?php echo $row['pname']; ?></a>
</td>
<td>
<?php echo date('j M Y',strtotime($row['date'])); ?>
</td>
<td>
<?php echo $row['mode']; ?>
</td>
<td>
<?php
  $row['descriptions'] = str_replace("*", ", ", $row['descriptions']);
  echo $row['descriptions']; ?>
</td>
<td>
<?php
 $row['amounts'] = str_replace("*", ", ", $row['amounts']);
 echo $row['amounts']; ?>
</td>
<td>
<?php echo $row['discount']; ?>
</td>
<td>
  <?php
  $total = 0;
  $amounts = explode(",", $row['amounts']);
  foreach ($amounts as $key => $amount) {
    $total = $total + $amount;
  }
  $grandTotal = $total - $row['discount'];
  echo $grandTotal;
  if($row['mode'] == "CASH") {
    $cash += $grandTotal;
  } else if($row['mode'] == "CARD") {
    $card += $grandTotal;
  } else if($row['mode'] == "PAYTM") {
    $paytm += $grandTotal;
  } else if($row['mode'] == "UPI") {
    $upi += $grandTotal;
  }
  ?>
</td>
<td>
<input type="checkbox" name="delete[]" value=<?php echo "'{$row['id']}'"; ?> />
</td>
</tr>
<?php
$count++;
}

$totalDisplay = "CASH: ".$cash.".00<br>CARD: ".$card.".00<br>PAYTM: ".$paytm.".00<br>UPI: ".$upi.".00<br>FINAL AMOUNT: ".($cash + $card + $paytm + $upi).".00";

?>
</tbody>
</table>
<script type="text/javascript">
    document.getElementById("amountTotalsByType").innerHTML = <?php echo "\"".$totalDisplay."\""; ?>;
</script>

<input type="submit" value="Delete invoices">
</form>
<?php
}
else
{
echo "<h4>You cannot access this page directly!</h4>";
}
include('footer.php'); ?>
