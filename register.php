<?php include('header.php'); 
include('gen-sched-func.php');
function checkEmail($str)
{
	return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
}

if(isset($_POST['submit']))
{  //If the Register form has been submitted
	$err = array();
	$_POST['name'] = $_POST['first_name']." ".$_POST['last_name'];
	if(($_POST['email']))
	{
		if(!checkEmail($_POST['email']))
		{
			$err[]='Your email is not valid!';
		}
	}

	if(($_POST['phone']))
	{
		if( !preg_match("/^[0-9]{10}$/", $_POST['phone']) )
		{
			$err[]='Your mobile phone number is not valid!';
		}
	}
	
	if(!$_POST['name'] || !$_POST['dob'])
	{
		$err[] = 'All fields must be filled!';
	}

	$tempdate = $_POST['dob'] . "12:00";
	if(strtotime($tempdate) > strtotime('now'))
	{
		$err[] = 'Enter a valid date!';
	}

	if(!count($err))
	{
		$_POST['email'] = mysqli_real_escape_string($link, $_POST['email']);
		$_POST['name'] = mysqli_real_escape_string($link, $_POST['name']);
		$_POST['phone'] = mysqli_real_escape_string($link, $_POST['phone']);
		$_POST['dob'] = mysqli_real_escape_string($link, $_POST['dob']);
		
		// Escape the input data

		if(mysqli_query($link, "INSERT INTO patients(name,first_name,last_name,email,dob,phone,sex,father_name,father_occ,mother_name,mother_occ,address,sibling)
					VALUES(
					'".$_POST['name']."', '".$_POST['first_name']."', '".$_POST['last_name']."',
					'".$_POST['email']."',
					'".$_POST['dob']."',
					'".$_POST['phone']."',
					'".$_POST['sex']."',
					'".$_POST['father_name']."',
					'".$_POST['father_occ']."',
					'".$_POST['mother_name']."',
					'".$_POST['mother_occ']."',
					'".$_POST['address']."',
					'".$_POST['sibling']."')"))
		{	
			$new_patient_id = mysqli_insert_id($link);
			$_SESSION['msg']['reg-success']="Patient successfully added! Patient id is <strong>".$new_patient_id."</strong>";
			if($_POST['sibling']!=0)
			{
				$row_sibling = mysqli_fetch_assoc(mysqli_query($link, "SELECT sibling FROM patients WHERE id={$_POST['sibling']}"));
				if(!$row_sibling['sibling'])	//If sibling does not have any other sibling
				{
					if(!mysqli_query($link, "UPDATE patients SET sibling='{$new_patient_id}' WHERE id={$_POST['sibling']}"))
						$err[]="Some error in adding sibling";
				}
				else //If sibling has other sibling(s)
				{
					$new_sibling = $row_sibling['sibling'].",".$_POST['sibling'];
					echo "UPDATE patients SET sibling={$new_sibling} WHERE id={$_POST['sibling']}";
					if(!mysqli_query($link, "UPDATE patients SET sibling='{$new_sibling}' WHERE id={$_POST['sibling']}"))
						$err[]="Some error in adding sibling";
					if(!mysqli_query($link, "UPDATE patients SET sibling='{$new_sibling}' WHERE id={$new_patient_id}"))
						$err[]="Some error in adding sibling";
				}
			}
			if($_POST['gen_sched']=='1')
			{
				generate_patient_schedule($new_patient_id);
			}
		}
		else $err[]='An unknown error has occured.';
	}
	
	if(count($err))
	{
		$_SESSION['msg']['reg-err'] = implode('<br />',$err);
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
	<label for="name">First Name:&nbsp;&nbsp;</label>
	<input type="text" name="first_name" id="name"  />
	</p>

	<p>
	<label for="name">Last Name:&nbsp;&nbsp;</label>
	<input type="text" name="last_name" id="name"  />
	</p>

	<p>
	<label for="dob">Date of Birth:&nbsp;&nbsp;</label>
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
	<label for="phone">Mobile number:&nbsp;&nbsp;</label>
	<input type="text" name="phone" id="phone"  />
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
	<label class="grey" for="sibling">Sibling:&nbsp;&nbsp;</label>
	<select name="sibling" style="margin-right:60px;">
	<option value=0>None</option>
	<?php
	$result = mysqli_query($link, "SELECT name, id FROM patients WHERE 1");
	while($pat_sib = mysqli_fetch_assoc($result))
	{
		echo "<option value=".$pat_sib['id'].">".$pat_sib['name']."</option>\n";
	}
	?>
	</select>
	<input type="checkbox" name="gen_sched" value="1" checked="true"/> Generate New Schedule
	</p>
	<p>
	<input type="submit" name="submit" value="Register"/>
	</p>

</form>
<?php include('footer.php'); ?>
