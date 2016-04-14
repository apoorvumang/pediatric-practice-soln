<?php include('header.php'); 
include_once('gen-sched-func.php');
include_once('add-patient-func.php');

if(isset($_POST['submit']))
{  //If the Register form has been submitted
	//Returns 0 on error, otherwise new patient id
	$retval = addPatient($_POST);
	if($retval)
	{
		Redirect("edit-sched.php?id={$retval}");
		exit;
		// Redirect("edit-sched.php?id={$retval}");
		// exit();
	}
}
if($_SESSION['msg']['reg-err'])
{
	echo '<div class="err">'.$_SESSION['msg']['reg-err'].'</div>';
	unset($_SESSION['msg']['reg-err']);
}

if($_SESSION['msg']['reg-success'])
{
	echo '<div class="success">'.$_SESSION['msg']['reg-success'].'</div>';
	unset($_SESSION['msg']['reg-success']);
}
?>

<script>
$(function() {
	$( "#dob_show" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1970:2032",
		dateFormat:"dd/mm/yy",
		altField: "#dob",
		altFormat: "yy-mm-dd"
	});
});

$(function() {
	$( "#doregistration_show" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1970:2032",
		dateFormat:"dd/mm/yy",
		altField: "#doregistration",
		altFormat: "yy-mm-dd"
	});
});

function autocomplet() {
	var min_length = 3; // min characters to display the autocomplete
	var keyword = $('#sibling_id').val();
	var myId = 0;
	if (keyword.length >= min_length) {
		$.ajax({
			url: 'ajax_refresh.php',
			type: 'POST',
			data: {"keyword":keyword, "myid": myId},
			success:function(data){
				$('#sibling_autocomplet_list').show();
				$('#sibling_autocomplet_list').html(data);
			}
		});
	} else {
		$('#sibling_autocomplet_list').hide();
	}
}

// set_item : this function will be executed when we select an item
function set_item(item) {
	// change input value
	$('#sibling_id').val(item);
	// hide proposition list
	$('#sibling_autocomplet_list').hide();
}
</script>

<form action="" method="post" enctype="multipart/form-data" style="width:auto">
	<h3>Add patient</h3>
	<p>
	* = Required
	</p>
	<p>
	<label for="first_name">First Name:&nbsp;&nbsp;</label>
	<input type="text" name="first_name" id="first_name" style="width:477px" />
	*
	</p>

	<p>
	<label for="last_name">Last Name:&nbsp;&nbsp;</label>
	<input type="text" name="last_name" id="last_name" style="width:477px" />
	</p>

	<p>
	<label for="dob_show">Date of Birth:&nbsp;&nbsp;</label>
	<input type="hidden" name="dob" id="dob" />
	<input type="text" name="dob_show" id="dob_show" />*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	<label class="grey" for="sex">Sex:&nbsp;&nbsp;</label>
	<select name="sex" style="margin-right:60px;">
	<option value='M'>Male</option>
	<option value='F'>Female</option>
	</select>
	</p>

	<p>
	<label for="email">Email:&nbsp;&nbsp;</label>
	<input type="text" name="email" id="email"  />
	</p>

	<p>
	<label for="email2">Email 2:&nbsp;&nbsp;</label>
	<input type="text" name="email2" id="email2"  />
	</p>

	<p>
	<label for="phone">Phone number 1:&nbsp;&nbsp;</label>
	<input type="text" name="phone" id="phone"  />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<label for="phone2">Phone number 2:&nbsp;&nbsp;</label>
	<input type="text" name="phone2" id="phone2"  />
	</p>

	<p>
	<label for="address">Address:&nbsp;&nbsp;</label><br />
	<textarea name="address" id="address" rows=3 cols=70></textarea>
	</p>

	<p>
	<label for="father_name">Father's name:&nbsp;&nbsp;</label>
	<input type="text" name="father_name" id="father_name"  />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	<label for="father_occ">Father's occupation:&nbsp;&nbsp;</label>
	<input type="text" name="father_occ" id="father_occ"  />
	</p>

	<p>
	<label for="mother_name">Mother's name:&nbsp;&nbsp;</label>
	<input type="text" name="mother_name" id="mother_name"  />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<label for="mother_occ">Mother's occupation:&nbsp;&nbsp;</label>
	<input type="text" name="mother_occ" id="mother_occ"  />
	</p>

	<!-- <p> -->
	<!-- <label class="grey" for="sibling">Sibling ID:&nbsp;&nbsp;</label>
	<input type="text" name="sibling" value="0" style="width:20px" id="sibling"/> -->

	<div class="clear input_container">
		<label for="add_sibling">Add sibling:&nbsp;&nbsp;</label>
		<input type="text" id = "sibling_id" name ="add_sibling" onkeyup="autocomplet()" />
		<ul id="sibling_autocomplet_list"></ul>
	</div>
<!--	<select name="sibling" style="margin-right:60px;">
	<option value=0>None</option>
	<?php
	// $result = mysqli_query($link, "SELECT name, id FROM patients WHERE 1");
	// while($pat_sib = mysqli_fetch_assoc($result))
	// {
	// 	echo "<option value=".$pat_sib['id'].">".$pat_sib['name']."</option>\n";
	// }
	?>
	</select>
-->
<!-- </p> -->
	<p>
	<label class="grey" for="born_at">Birth Time:&nbsp;&nbsp;</label>
	<input type="text" name="born_at" id="born_at"/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<label class="grey" for="birth_weight">Birth Weight:&nbsp;&nbsp;</label>
	<input type="text" name="birth_weight" id="birth_weight"/>
	</p>

	<p>
	<label class="grey" for="length">Length:&nbsp;&nbsp;</label>
	<input type="text" name="length" id="length"/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<label class="grey" for="head_circum">Head Circumference:&nbsp;&nbsp;</label>
	<input type="text" name="head_circum" id="head_circum"/>
	</p>

	<p>
	<label class="grey" for="mode_of_delivery">Mode of Delivery:&nbsp;&nbsp;</label>
	<select name="mode_of_delivery" style="margin-right:60px;">
		<option value="Normal">Normal</option>
		<option value="Caesarean">Caesarean</option>
		<option value="Forceps">Forceps</option>
		<option value="Vacuum">Vacuum</option>
	</select>

	<label class="grey" for="gestation">Gestation:&nbsp;&nbsp;</label>
	<select name="gestation" style="margin-right:60px;">
		<option value="FT">FT</option>
		<option value="PT">PT</option>
		<option value="LPT">LPT</option>
	</select>
	</p>
	<p>
		<label for="doregistration_show">Date of Registration:&nbsp;&nbsp;</label>
		<input type="hidden" name="doregistration" id="doregistration" />
		<input type="text" name="doregistration_show" id="doregistration_show" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="checkbox" name="active" id="active" value="1" checked="true"/>
		<label for="active">Active</label>
	</p>

	<p>
		<label for="place_of_birth">Place of Birth:&nbsp;&nbsp;</label>
		<input type="text" name="place_of_birth" id="place_of_birth"  />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<label for="obstetrician">Obstetrician:&nbsp;&nbsp;</label>
		<input type="text" name="obstetrician" id="obstetrician"  />
	</p>

	<p>
	<input type="checkbox" name="gen_sched" value="1" checked="true"/> Generate New Schedule
	</p>
	<p>
	<input type="submit" name="submit" value="Register"/>
	</p>

</form>
<?php include('footer.php'); ?>
