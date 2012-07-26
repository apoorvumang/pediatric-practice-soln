<?php include('header.php'); 
include_once('gen-sched-func.php');
include_once('add-patient-func.php');

if(isset($_POST['submit']))
{  //If the Register form has been submitted
	//Returns 0 on error, otherwise new patient id
	$retval = addPatient($_POST);
	if($retval)
	{
		header("Location: edit-sched.php?id={$retval}");
		exit();
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
		yearRange: "1985:2022",
		dateFormat:"dd/mm/yy",
		altField: "#dob",
		altFormat: "yy-mm-dd"
	});
});
</script>

<form action="" method="post" enctype="multipart/form-data" style="width:auto">
	<h3>Add patient</h3>
	
	<p>
	<label for="first_name">First Name:&nbsp;&nbsp;</label>
	<input type="text" name="first_name" id="first_name" style="width:477px" />
	</p>

	<p>
	<label for="last_name">Last Name:&nbsp;&nbsp;</label>
	<input type="text" name="last_name" id="last_name" style="width:477px" />
	</p>

	<p>
	<label for="dob_show">Date of Birth:&nbsp;&nbsp;</label>
	<input type="hidden" name="dob" id="dob" />
	<input type="text" name="dob_show" id="dob_show" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

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

	<p>
	<label class="grey" for="sibling">Sibling ID:&nbsp;&nbsp;</label>
	<input type="text" name="sibling" value="0" style="width:20px" id="sibling"/>
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
	<input type="checkbox" name="gen_sched" value="1" checked="true"/> Generate New Schedule
	</p>
	<p>
	<input type="submit" name="submit" value="Register"/>
	</p>

</form>
<?php include('footer.php'); ?>
