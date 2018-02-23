<?php include('header.php'); ?>

<?php
  if(!$_GET['id']) {
    echo 'Invalid request! Need patient ID as get param.';
    exit;
  }
  $query = "SELECT * from patients WHERE id={$_GET['id']};";
  $patient = mysqli_fetch_assoc(mysqli_query($link, $query));
?>

<script>
$(function() {
$( "#treatmentFrom" ).datepicker({
  changeMonth: true,
  changeYear: true,
  yearRange: "1970:2032",
  dateFormat:"d M yy"
  });
});
$(function() {
$( "#restFrom" ).datepicker({
  changeMonth: true,
  changeYear: true,
  yearRange: "1970:2032",
  dateFormat:"d M yy"
  });
});
$(function() {
$( "#restTo" ).datepicker({
  changeMonth: true,
  changeYear: true,
  yearRange: "1970:2032",
  dateFormat:"d M yy"
  });
});
</script>
<div class="body">

	<h3>Medical Certificate</h3>
  <form action="" method="post">
    <p> This is to certify that <?php echo "<b>".$patient['name']."</b>"; ?>
      <?php
        //son or daughter
        if($patient['sex'] == 'M') {
          echo "son of ";
        } else {
          echo "daughter of ";
        }
      ?>

      <select name="parent_name">
      <?php
        //choose mother or father's name
        $mother_name = $patient['mother_name'];
        $father_name = $patient['father_name'];
        echo "<option value='Mrs. {$mother_name}'>Mrs. {$mother_name}</option>";
        echo "<option value='Mr. {$father_name}'>Mr. {$father_name}</option>";
      ?>
      </select>
      is under my treatment from
      <input type="text" name="treatmentFrom" id="treatmentFrom" placeholder="Date" style="margin-right:20px;"/>
      for
      <input type="text" name="diagnosis" id="diagnosis" placeholder="Diagnosis" style="margin-right:20px;margin-left:20px;"/>

      <br />
      <?php
        //he or she
        if($patient['sex']=='M') {
          echo "He ";
        } else {
          echo "She ";
        }
      ?>
      is advised rest for the duration of
      <input type="text" name="no_of_days" id="no_of_days" style="width:30px" />
      days from
      <input type="text" name="restFrom" id="restFrom" placeholder="Date"/>
      to
      <input type="text" name="restTo" id="restTo" placeholder="Date"/>
      .
      <br />
      <br />
      Dr. Mahima Anurag
    </p>
    <input type="submit" value="Create certificate">
  </form>

</div>
<?php include('footer.php'); ?>