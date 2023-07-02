<?php include('header.php');
//What needs to be done on this page:
// List out all schedules for current day.
?>

<?php
if($_POST['save_changes']) {

  if($_POST['note_id']) {
    foreach ($_POST['note_id'] as $key => $value) {
      $weight = $_POST['change_weight'][$key];
      $height = $_POST['change_height'][$key];
      $query = "UPDATE notes SET weight='{$weight}', height='{$height}' WHERE id = ".$value;
      if(!mysqli_query($link, $query))
        $err[] = "Error while updating visits {$value}";
    }
  }

  if($err) {
    echo $err;
  }

  if($_POST['delete']) {
    $deleteArray = $_POST['delete'];
    $idList = implode(",", $deleteArray);

    // Delete from visit_invoices table
    $query = "DELETE from visit_invoices WHERE visit_id in ({$idList});";
    if(!mysqli_query($link, $query)) {
      echo 'Error in deleting from visit_invoices';
      exit;
    }
    
    // Delete from notes table
    $query = "DELETE from notes WHERE id in ({$idList});";
    if(mysqli_query($link, $query)) {
      echo 'Deletion successful!';
    } else {
      echo 'Error in deleting visits';
    }
}

}

?>

<h3>Today's visits</h3>
<?php
  $today = date('Y-m-d');
  mysqli_query($link, "SET time_zone = '+5:30';");
  $result = mysqli_query($link, "SELECT n.timestamp as timestamp, n.id, n.p_id as pid, n.date as date, n.note as note, p.name as pname, n.height as height, n.weight as weight FROM notes n, patients p WHERE n.date='".$today."' AND n.p_id = p.id ORDER BY n.timestamp DESC");
  $nrows = mysqli_num_rows($result);
  echo "<h4>Number of visits today: ".$nrows."</h4>";
?>
<form action="" method="post" enctype="multipart/form-data" style="width:auto" name="1">
<input type="submit" value="Save">
<input type="hidden" name="save_changes" value="1">
<table>
<tbody>
<tr>
<th>Visit ID</th>
<th>Patient ID</th>
<th>Patient</th>
<th>Height (cm)</th>
<th>Weight (kg)</th>
<th>BMI</th>
<th>Note</th>
<th>Date</th>
<th>Invoice ID</th>
<th>Waiting Time</th>
<th>Delete</th>
</tr>
<?php
$count = 0;
while($row = mysqli_fetch_assoc($result))
{

?>
<input type="hidden" name="note_id[]" value=<?php echo "\"".$row['id']."\"" ?> />
<tr>
<td>
<?php echo "v".$row['id'];?>
</td>
<td>
<?php echo "<b>".$row['pid']."</b>";?>
</td>
<td>
<a href= <?php echo "\"edit-sched.php?id={$row['pid']}\""; ?> ><?php echo $row['pname']; ?></a>
</td>

<td> <input style="text-align:center;vertical-align: middle;width:40px" name="change_height[]" value = <?php  echo "'{$row['height']}'";?> >  </td>
<td> <input style="text-align:center;vertical-align: middle;width:40px" name="change_weight[]" value = <?php  echo "'{$row['weight']}'";?> >  </td>

<!-- 
<td>
<?php echo $row['height']." cm"; ?>
</td>
<td>
<?php echo $row['weight']." kg"; ?>
</td>

 -->
<td>
<?php
if (isset($row['height'], $row['weight']) && is_numeric($row['height']) && is_numeric($row['weight'])) {
  $height = $row['height']/100.0;
  $weight = $row['weight'];
  
  if ($height != 0) {
      $height_squared = $height * $height;
      $bmi = $weight / $height_squared;
      echo number_format((float)$bmi, 2, '.', '');
  } else {
      echo "NA";
  }
} else {
  echo "NA";
}
?>
</td>
<td>
<?php echo $row['note']; ?>
</td>
<td>
<?php echo date('j M Y',strtotime($row['date'])); ?>
</td>
<td>
<?php
if($_SESSION['type']=='doctor') {
  $visit_id = $row['id'];
  $invoiceQuery = "SELECT invoice_id FROM visit_invoices WHERE visit_id = '{$visit_id}';";
  $invoiceResult = mysqli_query($link, $invoiceQuery);
  if (mysqli_num_rows($invoiceResult) > 0) {
    while ($invoiceRow = mysqli_fetch_assoc($invoiceResult)) {
      $invoiceId = $invoiceRow['invoice_id'];
      echo "<a href='pdf-invoice.php?id={$invoiceId}'><div style='background-color:#2c76a6;padding:5px;margin:5px;text-align:center;color:white'> {$invoiceId} </div></a>";
    }
  } 
  echo "<a href='create-invoice.php?id={$row['pid']}&visit_id={$row['id']}' style='color:#2c76a6; white-space: nowrap;'>Create Invoice</a>";
} else {
  echo '-';
}
?>
</td>
<td>
  <b>
    <time class="timeago" datetime=<?php echo "'{$row['timestamp']}'"; ?> >July 17, 2008</time>
  </b>
</td>
<td>
<input type="checkbox" name="delete[]" value=<?php echo "'{$row['id']}'"; ?> />
</td>
</tr>
<?php
$count++;
}
?>
</tbody>
</table>
</form>

<a href="visits.php">Search visits</a>

<?php

include('footer.php'); ?>
