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

      <select name="parent_name">
      <?php
        //choose mother or father's name
        $mother_name = $patient['mother_name'];
        $father_name = $patient['father_name'];
        echo "<option value='Mrs. {$mother_name}'>Mrs. {$mother_name}</option>";
        echo "<option value='Mr. {$father_name}'>Mr. {$father_name}</option>";
      ?>
      </select>
      is a healthy child of <?php echo $formatted_age ?>.
      <br />
      <?php echo $pronoun." "; ?>has received the following immunizations to date:
      <br />
      <textarea name="vaccine_list" rows=15 cols=30 ><?php
        /*
          For vaccine list, we will be taking the ids of the FIRST dose of each vaccine
          and check if that has been given.

          List is:
          BCG, Hepatitis-B, DTwP/DTaP and OPV, Hib, IPV, Pneumococcal, Rotavirus, Measles, Influenza, Hepatitis-A, Chickenpox, MMR, Typhoid, Cholera, Meningitis, HPV, Tdap/Td/TT
          IDs:
          1,15,8,19,28,39,43,33,22,13,6,37,46,59,34,3,45
        */
        $vac_name_list = ["BCG", "Hepatitis-B", "DTwP/DTaP and OPV", "Hib", "IPV", "Pneumococcal", "Rotavirus", "Measles", "Influenza", "Hepatitis-A", "Chickenpox", "MMR", "Typhoid", "Cholera", "Meningitis", "HPV", "Tdap/Td/TT"];
        $vac_id_list = [1,15,8,19,28,39,43,33,22,13,6,37,46,59,34,3,45];
        $query = "SELECT * FROM vac_schedule WHERE p_id={$patient['id']} AND GIVEN='Y';";
        // echo $query;
        $result = mysqli_query($link, $query);
        $patient_given_vac_list = [];
        while($row = mysqli_fetch_assoc($result)) {
          $patient_given_vac_list[] = $row['v_id'];
        }
        foreach ($vac_id_list as $key => $vac_id) {
          if(in_array($vac_id, $patient_given_vac_list)) {
            echo $vac_name_list[$key]."\n";
          }
        }
        ?></textarea>

      <br />
      <?php
        //he or she
        echo $pronoun." ";
      ?>
      does not suffer from any chronic or communicable disease.
      <br />
      <?php echo $patient['first_name']; ?> is a physically active and mentally alert child fit to participate in all school activites.

      <br />
      <br />
      Dr. Mahima Anurag
    </p>
    <input type="submit" value="Create certificate">
  </form>

</div>
<?php include('footer.php'); ?>
