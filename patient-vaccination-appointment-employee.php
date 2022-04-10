<?php include('header.php'); ?>
<script type="text/javascript">
$(function() {
	$( "#date_from" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1970:2032",
		dateFormat:"d M yy"
	});
});
$(function() {
	$( "#date_to" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1970:2032",
		dateFormat:"d M yy"
	});
});
</script>
<?php

if($_POST['patient_id'] || $_GET['patient_id']) {
  $p_id = 0;
  if($_POST['patient_id']) {
    $p_id = $_POST['patient_id'];
  } else {
    $p_id = $_GET['patient_id'];
  }

  $patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT name from patients where id={$p_id}"));
  $patient_name = $patient['name'];
  $query = "SELECT v.name as vac_name, vs.date as date FROM vaccines v, vac_schedule vs WHERE vs.p_id={$p_id} AND v.id = vs.v_id AND vs.given='N' ORDER BY vs.date";
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
      $date = $row['date'];
      echo "<tr>";
      echo "<td>".$vaccine_name."</td>";
      echo "<td>".date('d-F-Y', strtotime($date))."</td>";
      echo "</tr>";
    }
    echo "</tbody></table>";
} elseif($_POST['date_from'] || $_POST['patient_phone'] || $_POST['patient_name']) {
  if($_POST['date_from']) {
    $_POST['date_from'] = mysqli_real_escape_string($link, date('Y-m-d', strtotime($_POST['date_from'])));
    $_POST['date_to'] = mysqli_real_escape_string($link, date('Y-m-d', strtotime($_POST['date_to'])));
    $query = "SELECT p.id as p_id, p.name as patient_name, v.name as vac_name, vs.date as date, vs.v_id as v_id FROM vaccines v, vac_schedule vs, patients p WHERE p.active=1 AND vs.p_id=p.id AND v.id = vs.v_id AND vs.given='N' AND vs.date >= '{$_POST['date_from']}' AND vs.date <= '{$_POST['date_to']}' ORDER BY p.id, vs.date;";
    $result = mysqli_query($link, $query);
    $nrows = mysqli_num_rows($result);
    $fromDate = $_POST['date_from'];
    $toDate = $_POST['date_to'];
    ?>
    <h3>Schedule for dates <?php echo date('d-F-Y', strtotime($fromDate))." to ".date('d-F-Y', strtotime($toDate)); ?></h3>
    <?php
  }
  if ($_POST['patient_phone']) {
    $phone = mysqli_real_escape_string($link, $_POST['patient_phone']);
    $query = "SELECT p.id as p_id, p.name as patient_name, v.name as vac_name, vs.date as date, vs.v_id as v_id from vaccines v, vac_schedule vs, patients p where (p.phone like '%{$phone}' or p.phone2 like '%{$phone}') and p.active=1 AND vs.p_id=p.id AND v.id = vs.v_id AND vs.given='N' ORDER BY p.id, vs.date;";
    $result = mysqli_query($link, $query);
    $nrows = mysqli_num_rows($result);

    ?>
    <h3>Schedule for phone <?php echo $phone; ?></h3>
    <?php
  } 
  if ($_POST['patient_name']) {
    $name = mysqli_real_escape_string($link, $_POST['patient_name']);
    $query = "SELECT p.id as p_id, p.name as patient_name, v.name as vac_name, vs.date as date, vs.v_id as v_id from vaccines v, vac_schedule vs, patients p where p.name like '%{$name}%' and p.active=1 AND vs.p_id=p.id AND v.id = vs.v_id AND vs.given='N' ORDER BY p.id, vs.date;";
    $result = mysqli_query($link, $query);
    $nrows = mysqli_num_rows($result);
    ?>
    <h3>Schedule for patient names matching <?php echo $name; ?></h3>
    <?php
  }
  ?>
  
  <table style="margin: 0px 0px 0px 0px;border:none;"><tbody>
    <tr>
      <th>Patient</th>
      <th>Vaccine</th>
      <th>Date scheduled</th>
    </tr>
  <?php

  while($row = mysqli_fetch_assoc($result)) {
    $vaccine_name = $row['vac_name'];
    $patient_name = $row['patient_name'];
    $vaccine = mysqli_fetch_assoc(mysqli_query($link, "SELECT name, upper_limit, dependent FROM vaccines WHERE id={$row['v_id']}"));
		if($vaccine['dependent'] != 0) {
			$query_for_dependent = "SELECT vs.given as given FROM vac_schedule vs WHERE p_id={$row['p_id']} and v_id = {$vaccine['dependent']}";
		  $dependent_schedule = mysqli_fetch_assoc(mysqli_query($link, $query_for_dependent));
			if($dependent_schedule['given'] == 'N') {
				continue;
			}
		}
    $p_id = $row['p_id'];
    $date = $row['date'];
    echo "<tr>";
    echo "<td><a href='edit-sched.php?id={$p_id}'>".$patient_name."</a></td>";
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

<form action="" method="post" enctype="multipart/form-data" style="width:auto">
<label for='patient_name'>Patient Name:&nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="text" name="patient_name" id="patient_name" style="width:100px" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type='submit' value='Go'>
</form>


<form action="" method="post" enctype="multipart/form-data" style="width:auto">
<label for='patient_id'>Patient Phone Number:&nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="number" name="patient_phone" id="patient_phone" style="width:100px" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type='submit' value='Go'>
</form>

<form action="" method="post" enctype="multipart/form-data" style="width:auto" name="2">
	<label for="date_from">Date From: &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="date_from" id="date_from" style="margin-right:40px;"/>
	<label for="date_to">To: &nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="text" name="date_to" id="date_to" style="margin-right:40px;"/>
	<input type="submit" name="dateRange" value="Go" />
</form>

<?php
}


include('footer.php');
// $pdf = createPrintSchedulePDF($_GET['id'], $link);
// $pdf->Output();
?>
