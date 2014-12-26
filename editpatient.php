<?php include('header.php');
include_once('add-patient-func.php');
include_once('gen-sched-func.php');
if($_GET['delete']=='999'&&isset($_GET['id']))
{
	//Delete patient from patients, and corresponding records from vac_schedule
	if(mysqli_query($link, "DELETE FROM patients WHERE id = {$_GET['id']}")&&mysqli_query($link, "DELETE FROM vac_schedule WHERE p_id = {$_GET['id']}"))
		echo "Deletion successful.";
	else
		echo "Some problem in deleting.";
	exit();
}
else if($_GET['reschedule']=='999'&&isset($_GET['id']))
{
	generate_patient_schedule($_GET['id']);
	echo "Rescheduling successful.";
	Redirect("edit-sched.php?id={$_GET['id']}");
}
if(isset($_POST['submit']))
{
	editPatient($_POST);
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
}
if(isset($_GET['id']))
{
	$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM patients WHERE id={$_GET['id']}"));
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
		altField: "#date_of_registration",
		altFormat: "yy-mm-dd"
	});
});
</script>

<form action="" method="post" enctype="multipart/form-data" style="width:auto">
	<a href= <?php echo "\"edit-sched.php?id={$patient['id']}\""?> >Back to patient schedule</a>
	<h3>Edit patient</h3>
	<input type="hidden" name="id" value=<?php echo "\"".$patient['id']."\""; ?> />
	<p>
	<label for="name">First Name:&nbsp;&nbsp;</label>
	<input type="text" name="first_name" id="name" <?php echo "value=\"{$patient['first_name']}\""; ?>/>
	</p>

	<p>
	<label for="name">Last Name:&nbsp;&nbsp;</label>
	<input type="text" name="last_name" id="name" <?php echo "value=\"{$patient['last_name']}\""; ?> />
	</p>

	<p>
	<label for="dob">Date of Birth:&nbsp;&nbsp;</label>
	<input type="hidden" name="dob" id="dob" <?php echo "value=\"".$patient['dob']."\""; ?> />
	<input type="text" name="dob_show" id="dob_show" <?php echo "value=\"".date('d/m/Y',strtotime($patient['dob']))."\""; ?> />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	<label class="grey" for="sex">Sex:&nbsp;&nbsp;</label>
	<select name="sex" style="margin-right:60px;">
	<option value='M' <?php if($patient['sex']=='M') echo "selected";?> >Male</option>
	<option value='F' <?php if($patient['sex']=='F') echo "selected";?>>Female</option>
	</select>
	</p>

	<p>
	<label for="email">Email:&nbsp;&nbsp;</label>
	<input type="text" name="email" id="email" <?php echo "value=\"{$patient['email']}\""; ?> />
	</p>

	<p>
	<label for="phone">Phone number 1:&nbsp;&nbsp;</label>
	<input type="text" name="phone" id="phone" <?php echo "value=\"{$patient['phone']}\""; ?> />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<label for="phone2">Phone number 2:&nbsp;&nbsp;</label>
	<input type="text" name="phone2" id="phone2" <?php echo "value=\"{$patient['phone2']}\""; ?> />
	</p>

	<p>
	<label for="address">Address:&nbsp;&nbsp;</label><br />
	<textarea name="address" id="address" rows=3 cols=70><?php echo $patient['address']; ?></textarea>
	</p>

	<p>
	<label for="father_name">Father's name:&nbsp;&nbsp;</label>
	<input type="text" name="father_name" id="father_name" <?php echo "value=\"{$patient['father_name']}\""; ?> />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	<label for="father_occ">Father's occupation:&nbsp;&nbsp;</label>
	<input type="text" name="father_occ" id="father_occ" <?php echo "value=\"{$patient['father_occ']}\""; ?> />
	</p>

	<p>
	<label for="mother_name">Mother's name:&nbsp;&nbsp;</label>
	<input type="text" name="mother_name" id="mother_name" <?php echo "value=\"{$patient['mother_name']}\""; ?> />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<label for="mother_occ">Mother's occupation:&nbsp;&nbsp;</label>
	<input type="text" name="mother_occ" id="mother_occ" <?php echo "value=\"{$patient['mother_occ']}\""; ?> />
	</p>

	<p>
	<label class="grey" for="birth_weight">Birth Weight:&nbsp;&nbsp;</label>
	<input type="text" name="birth_weight" id="birth_weight" <?php echo "value=\"{$patient['birth_weight']}\""; ?>/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<label class="grey" for="born_at">Birth Time:&nbsp;&nbsp;</label>
	<input type="text" name="born_at" id="born_at" <?php echo "value=\"{$patient['born_at']}\""; ?>/>
	</p>

	<p>
	<label class="grey" for="head_circum">Head Circumference:&nbsp;&nbsp;</label>
	<input type="text" name="head_circum" id="head_circum" <?php echo "value=\"{$patient['head_circum']}\""; ?>/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<label class="grey" for="length">Length:&nbsp;&nbsp;</label>
	<input type="text" name="length" id="length" <?php echo "value=\"{$patient['length']}\""; ?>/>
	</p>

	<p>
	<label class="grey" for="mode_of_delivery">Mode of Delivery:&nbsp;&nbsp;</label>
	<select name="mode_of_delivery" style="margin-right:60px;">
		<option value="Normal" <?php if($patient['mode_of_delivery']=='Normal') echo "selected";?>>Normal</option>
		<option value="Caesarean" <?php if($patient['mode_of_delivery']=='Caesarean') echo "selected";?>>Caesarean</option>
		<option value="Forceps" <?php if($patient['mode_of_delivery']=='Forceps') echo "selected";?>>Forceps</option>
		<option value="Vacuum" <?php if($patient['mode_of_delivery']=='Vacuum') echo "selected";?>>Vacuum</option>
	</select>

	<label class="grey" for="gestation">Gestation:&nbsp;&nbsp;</label>
	<select name="gestation" style="margin-right:60px;">
		<option value="FT" <?php if($patient['gestation']=='FT') echo "selected";?>>FT</option>
		<option value="PT" <?php if($patient['gestation']=='PT') echo "selected";?>>PT</option>
		<option value="LPT" <?php if($patient['gestation']=='LPT') echo "selected";?>>LPT</option>
	</select>
	</p>

	<p>
		<label for="doregistration_show">Date of Registration:&nbsp;&nbsp;</label>
		<input type="hidden" name="date_of_registration" id="date_of_registration" <?php echo "value=\"".$patient['date_of_registration']."\""; ?> />
		<input type="text" name="doregistration_show" id="doregistration_show" <?php echo "value=\"".date('d/m/Y',strtotime($patient['date_of_registration']))."\""; ?> />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

		<input type="checkbox" name="active" id="active" value="1" <?php if($patient['active']) echo "checked=\"true\""; ?>/> 
		<label for="active">Active</label>
	</p>

	<p>
		<label for="place_of_birth">Place of Birth:&nbsp;&nbsp;</label>
		<input type="text" name="place_of_birth" id="place_of_birth" <?php echo "value=\"{$patient['place_of_birth']}\""; ?>/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<label for="obstetrician">Obstetrician:&nbsp;&nbsp;</label>
		<input type="text" name="obstetrician" id="obstetrician" <?php echo "value=\"{$patient['obstetrician']}\""; ?> />
	</p>

	<p>
	<input type="submit" name="submit" value="Save"/>
	</p>
	<p>
		<a href=<?php echo "\"editpatient.php?id={$patient[id]}&delete=999\"" ?> onclick="return confirm('Confirm delete?');"><strong><font color="red">Delete patient</font></strong></a>
	</p>
	<p>
		<a href=<?php echo "\"editpatient.php?id={$patient[id]}&reschedule=999\"" ?> onclick="return confirm('Confirm reschedule?');"><strong><font color="blue">Reschedule patient</font></strong></a>
	</p>

</form>
<?php
}
else 
{
	echo "<h3>You cannot access this page directly!</h3>";
} 
include('footer.php'); ?>