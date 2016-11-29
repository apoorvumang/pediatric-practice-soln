<?php
include('header.php');
include_once('gen-sched-func.php');
include_once('add-patient-func.php');

if (isset($_POST['submit'])) { //If the Register form has been submitted
                //Returns 0 on error, otherwise new patient id
	$retval = addPatient($_POST);
	if ($retval) {
		Redirect("edit-sched.php?id={$retval}");
		exit;
                                // Redirect("edit-sched.php?id={$retval}");
                                // exit();
	}
}
if ($_SESSION['msg']['reg-err']) {
	echo '<div class="err">' . $_SESSION['msg']['reg-err'] . '</div>';
	unset($_SESSION['msg']['reg-err']);
}

if ($_SESSION['msg']['reg-success']) {
	echo '<div class="success">' . $_SESSION['msg']['reg-success'] . '</div>';
	unset($_SESSION['msg']['reg-success']);
}
?>

<script>
	

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

$( function() {
	$( "#accordion" ).accordion({
		heightStyle: "content",
		collapsible: true
	});
} );

</script>

<div class="row">
<div class="col-six tab-full">
<form action="" method="post" enctype="multipart/form-data">
	<h3>Add patient
	<small>
		(* = Required)
	</small>
	</h3>
	<div id="accordion">
		<h3> Basic </h3>
		<div>
			<div>
				<label for="first_name">First Name:</label>
				<input class="full-width required" type="text" name="first_name" id="first_name"  />
			</div>

			<div>
				<label for="last_name">Last Name:</label>
				<input class="full-width" type="text" name="last_name" id="last_name"/>
			</div>

			<div>
				<label for="dob">Date of Birth:</label>
				<input class="full-width required" type="date" name="dob" id="dob" />

				<label class="grey" for="sex">Sex:</label>
				<select name="sex" >
					<option value='M'>Male</option>
					<option value='F'>Female</option>
				</select>
			</div>

			<div>
				<label for="phone">Phone number 1:</label>
				<input class="full-width" type="tel" name="phone" id="phone"  />
				<label for="phone2">Phone number 2:</label>
				<input class="full-width" type="tel" name="phone2" id="phone2"  />
			</div>

			<div>
				<label for="note">Notes:</label>
				<textarea name="note" id="note" rows=3 cols=90></textarea>
			</div>

		</div>
		<h3> Extra </h3>
		<div>

			<div>
				<label for="email">Email:</label>
				<input class="full-width" type="email" name="email" id="email"  />
			</div>

			<div>
				<label for="email2">Email 2:</label>
				<input class="full-width" type="email" name="email2" id="email2"  />
			</div>

			<div>
				<label for="address">Address:</label><br />
				<textarea name="address" id="address" rows=3 cols=70></textarea>
			</div>

			<div>
				<label for="father_name">Father's name:</label>
				<input class="full-width" type="text" name="father_name" id="father_name"  />
				

				<label for="father_occ">Father's occupation:</label>
				<input class="full-width" type="text" name="father_occ" id="father_occ"  />
			</div>

			<div>
				<label for="mother_name">Mother's name:</label>
				<input class="full-width" type="text" name="mother_name" id="mother_name"  />
				
				<label for="mother_occ">Mother's occupation:</label>
				<input class="full-width" type="text" name="mother_occ" id="mother_occ"  />
			</div>

			<!-- <p> -->
	    <!-- <label class="grey" for="sibling">Sibling ID:</label>
	    <input class="full-width" type="text" name="sibling" value="0" style="width:20px" id="sibling"/> -->

	    <div class="clear input_container">
	    	<label for="add_sibling">Add sibling:</label>
	    	<input class="full-width" type="text" id = "sibling_id" name ="add_sibling" onkeyup="autocomplet()" />
	    	<ul id="sibling_autocomplet_list"></ul>
	    </div>
	<!--    <select name="sibling" style="margin-right:60px;">
	    <option value=0>None</option>
	    <?php
	// $result = mysqli_query($link, "SELECT name, id FROM patients WHERE 1");
	// while($pat_sib = mysqli_fetch_assoc($result))
	// {
	//     echo "<option value=".$pat_sib['id'].">".$pat_sib['name']."</option>\n";
	// }
	?>
	  </select>
	-->
	<!-- </p> -->
	<div>
		<label class="grey" for="born_at">Birth Time:</label>
		<input class="full-width" type="datetime-local" name="born_at" id="born_at"/>
		
		<label class="grey" for="birth_weight">Birth Weight:</label>
		<input class="full-width" type="number" name="birth_weight" id="birth_weight"/>
	</div>

	<div>
		<label class="grey" for="length">Length:</label>
		<input class="full-width" type="number" name="length" id="length"/>
		
		<label class="grey" for="head_circum">Head Circumference:</label>
		<input class="full-width" type="number" name="head_circum" id="head_circum"/>
	</div>

	<div>
		<label class="grey" for="mode_of_delivery">Mode of Delivery:</label>
		<select name="mode_of_delivery" >
			<option value="Normal">Normal</option>
			<option value="Caesarean">Caesarean</option>
			<option value="Forceps">Forceps</option>
			<option value="Vacuum">Vacuum</option>
		</select>

		<label class="grey" for="gestation">Gestation:</label>
		<select name="gestation" >
			<option value="FT">FT</option>
			<option value="PT">PT</option>
			<option value="LPT">LPT</option>
		</select>
	</div>
	<div>
		<label for="doregistration">Date of Registration:</label>
		<input class="full-width" type="date" name="doregistration" id="doregistration" />
	</div>
	<div>
		<label for="active">Active</label>
		<input type="checkbox" name="active" id="active" value="1" checked="true"/>
	</div>

	<div>
		<label for="place_of_birth">Place of Birth:</label>
		<input class="full-width" type="text" name="place_of_birth" id="place_of_birth"  />
		
		<label for="obstetrician">Obstetrician:</label>
		<input class="full-width" type="text" name="obstetrician" id="obstetrician"  />
	</div>
</div>
</div>
<div>
	<input type="checkbox" name="gen_sched" value="1" checked="true"/> Generate New Schedule
</div>
<div>
	<input type="submit" name="submit" value="Register"/>
</div>

</form>
</div>
</div>
<?php
include('footer.php');
?>