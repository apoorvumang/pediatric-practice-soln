<?php
include('header.php');



if($_POST['patient_id']) {
  $p_id = $_POST['patient_id'];
  $patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT name from patients where id={$p_id}"));
  $patient_name = $patient['name'];
  $query = "SELECT v.name as vac_name, vs.date as date FROM vaccines v, vac_schedule vs WHERE vs.p_id={$p_id} AND v.id = vs.v_id AND vs.given='N'";
  ?>
  <h3>Schedule for <?php echo $patient_name; ?></h3>
  <table style="margin: 0px 0px 0px 0px;border:none;"><tbody>
    <tr>
      <!-- <th>Patient ID</th> -->
      <th>Vaccine</th>
      <th>Date scheduled</th>
    </tr>
  <?php
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_assoc($result)) {
      $vaccine_name = $row['vac_name'];
      $patient_name = $row['patient_name'];
      $date = $row['date'];
      echo "<tr>";
      echo "<td>".$vaccine_name."</td>";
      echo "<td>".date('d-F-Y', strtotime($date))."</td>";
      echo "</tr>";
    }
    echo "</tbody></table>";
} else {
?>

<h3>Search vaccination appointment</h3>
<form action="" method="post" enctype="multipart/form-data" style="width:auto">
<label for='patient_id'>Patient ID:&nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="text" name="patient_id" id="patient_id" style="width:100px" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type='submit' value='Go'>

</form>

<?php
}


include('footer.php');
// $pdf = createPrintSchedulePDF($_GET['id'], $link);
// $pdf->Output();
?>
