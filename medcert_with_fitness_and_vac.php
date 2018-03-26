<?php include('header.php'); ?>

<?php
  if(!$_GET['id']) {
    echo 'Invalid request! Need patient ID as get param.';
    exit;
  }
  $query = "SELECT * from patients WHERE id={$_GET['id']};";
  $patient = mysqli_fetch_assoc(mysqli_query($link, $query));
  $formatted_dob = date('d-F-Y', strtotime($patient['dob']));
  $formatted_sex = "Female";
  $pronoun = "She";
  $prefix = "Miss";
  if($patient['sex'] == 'M') {
    $formatted_sex = "Male";
    $pronoun = "He";
    $prefix = "Master";
  }
  $from = new DateTime($patient['dob']);
  $to   = new DateTime('tomorrow');
  $age = $from->diff($to);
  $formatted_age = $age->y." years ".$age->m." months ".$age->d." days";
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

	<h3>Medical Fitness Certificate</h3>
  <form action="pdf-medcert_with_fitness_and_vac.php" method="post">
    <input type="checkbox" name="save_pdf" value="true" checked /> Save certificate <br>
    <input type="hidden" name="p_id" value=<?php echo "'{$patient['id']}'"; ?> />
    <input type="hidden" name="formatted_patient_name" value=<?php echo "'".$prefix." "."{$patient['name']}'"; ?> />
    <input type="hidden" name="patient_sex" value=<?php echo "'{$patient['sex']}'"; ?> />
    <input type="hidden" name="formatted_dob" value=<?php echo "'{$formatted_dob}'"; ?> />
    <input type="hidden" name="formatted_age" value=<?php echo "'{$formatted_age}'"; ?> />
    <input type="hidden" name="first_name" value=<?php echo "'{$patient['first_name']}'"; ?> />
    <p> This is to certify that <?php echo "<b>".$prefix." ".$patient['name']."</b> DOB {$formatted_dob} "; ?>
      <?php
        //son or daughter
        if($patient['sex'] == 'M') {
          echo "son of ";
        } else {
          echo "daughter of ";
        }
      ?>


      <?php
        //choose mother or father's name
        $mother_name = $patient['mother_name'];
        $father_name = $patient['father_name'];
        $parents_names = "Mrs. {$mother_name} and Mr. {$father_name}";
      ?>
      <input type="hidden" name="parent_name"  value=<?php echo "'{$parents_names}'"; ?> />
      <?php echo $parents_names." "; ?>
      is a healthy child of <?php echo $formatted_age ?>.
      <br />

      <?php
        //he or she
        echo $pronoun." ";
      ?>
      does not suffer from any chronic or communicable disease.
      <br />
      <?php echo $patient['first_name']; ?> is a physically active and mentally alert child fit to participate in all school activites.
      <?php
        $lowerCasePronoun = "she";
        if($pronoun=="He")
          $lowerCasePronoun = "he";
        echo "<br><br>Please find attached the list of immunizations {$lowerCasePronoun} has received till date.\n";
      ?>
      <br />
      <br />
      Dr. Mahima Anurag
    </p>
    <input type="submit" value="Create certificate">
  </form>

</div>
<?php include('footer.php'); ?>
