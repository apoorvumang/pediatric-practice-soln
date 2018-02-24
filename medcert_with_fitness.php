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
$(function() {
$( "#fitFrom" ).datepicker({
  changeMonth: true,
  changeYear: true,
  yearRange: "1970:2032",
  dateFormat:"d M yy"
  });
});
</script>
<div class="body">

	<h3>Medical Certificate with Fitness</h3>
  <form action="pdf-medcert_with_fitness.php" method="post">
    <input type="hidden" name="patient_name" value=<?php echo "'{$patient['name']}'"; ?> />
    <input type="hidden" name="patient_sex" value=<?php echo "'{$patient['sex']}'"; ?> />
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
      was under my treatment from
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
      was advised rest for the duration of
      <input type="text" name="no_of_days" id="no_of_days" style="width:30px" />
      days from
      <input type="text" name="restFrom" id="restFrom" placeholder="Date"/>
      to
      <input type="text" name="restTo" id="restTo" placeholder="Date"/>
      .
      <br />
      <?php
        //he or she
        if($patient['sex']=='M') {
          echo "He ";
        } else {
          echo "She ";
        }
      ?> has now recovered well and is fit to attend school from <input type="text" name="fitFrom" id="fitFrom" placeholder="Date"/>
      .
      <br />
      <br />
      Dr. Mahima Anurag
    </p>
    <input type="submit" value="Create certificate">
  </form>

</div>
<?php include('footer.php'); ?>
