<?php include('header.php');

  if($_POST['date'] && $_POST['p_id'] && $_POST['note']) {
    $query = "INSERT into notes (p_id, date, note, height, weight) VALUES ({$_POST['p_id']}, '{$_POST['date']}', \"{$_POST['note']}\", '{$_POST['height']}', '{$_POST['weight']}');";
    if(!mysqli_query($link, $query)) {
      echo "Error adding visit!";
    } else {
      echo "Visit added successfully!";
      $query = "SELECT * from patients WHERE id={$_POST['p_id']}";
      $patient = mysqli_fetch_assoc(mysqli_query($link, $query));
      $name = $patient['name'];
      $date = new DateTime();
      $formatted_date = $date->format('Y-m-d H:i:s');
      $headers = 'MIME-Version: 1.0' . "\r\n";
  		$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
  		$headers .= "From: ".$dr_name." <".$dr_email.">\r\n";

      $subject = "Visit added for {$name} on {$formatted_date}";
      $message = "<a href='https://www.drmahima.com/edit-sched.php?id=".$_POST['p_id']."'>Patient profile</a>";
      echo $message;
      if(mail("anuragmahima@gmail.com", $subject, $message, $headers)) {
  			echo 'success';
  		}
    }
  }

?>
<script type="text/javascript">
$(function() {
  $( "#date" ).datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: "1970:2032",
    dateFormat:"Y-m-d"
  });
});

function autocomplet() {
  var min_length = 3; // min characters to display the autocomplete
  var keyword = $('#patient_id').val();
  var myId = 0;
  if (keyword.length >= min_length) {
    $.ajax({
      url: 'ajax_refresh.php',
      type: 'POST',
      data: {"keyword":keyword, "myid": myId},
      success:function(data){
        $('#patient_autocomplet_list').show();
        $('#patient_autocomplet_list').html(data);
      }
    });
  } else {
    $('#patient_autocomplet_list').hide();
  }
}

function set_item(item) {
  // change input value
  $('#patient_id').val(item);
  // hide proposition list
  $('#patient_autocomplet_list').hide();
}

</script>
<h3>Add Visit</h3>
<form action="" method="post" enctype="multipart/form-data" style="width:auto" name="1">
  <label for="date">Date : &nbsp;&nbsp;&nbsp;&nbsp;</label>
  <input type="text" name="date" id="date" style="margin-right:40px;" value= <?php echo "\"".date("Y-m-d")."\""; ?>/>
  <br>
  <div class="clear input_container">
    <label for="p_id">Patient ID (type name for suggestion):&nbsp;&nbsp;</label>
    <input type="text" id = "patient_id" name ="p_id" onkeyup="autocomplet()" />
    <ul id="patient_autocomplet_list"></ul>
  </div>
  <br>
  <br>
  <label for="height">Height (in cms):&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <input type="number" step="any" name="height" id="height" width="40px"/>
    <span>&nbsp;&nbsp;&nbsp;cm </span>
  <br>
  <br>
  <label for="weight">Weight (in kg):&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <input type="number" step="any" name="weight" id="weight" width="40px"/>
    <span>&nbsp;&nbsp;&nbsp;kg </span>
  <br>
  <br>
  <label for="note">Additional info: </label>
  <br>
  <textarea name="note" rows="3" cols="50">
  </textarea>
  <br>
<input type="submit" name="addvisit" value="Go" />
</form>
<?php include('footer.php'); ?>
