<?php include('header.php');
if(isset($_POST['submit']))
{
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

			if(mysqli_query($link, "UPDATE patients SET 
				name = '{$_POST['name']}',
				first_name = '".$_POST['first_name']."',
				last_name =  '".$_POST['last_name']."',
				email = '".$_POST['email']."',
				dob = '".$_POST['dob']."',
				phone = '".$_POST['phone']."',
				sex = '".$_POST['sex']."',
				father_name = '".$_POST['father_name']."',
				father_occ = '".$_POST['father_occ']."',
				mother_name = '".$_POST['mother_name']."',
				mother_occ = '".$_POST['mother_occ']."',
				address = '".$_POST['address']."' WHERE id = {$_POST['id']}"))
			{	
				
				$_SESSION['msg']['reg-success']="Patient successfully edited!";
				
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
}
if(isset($_GET['id']))
{
	$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM patients WHERE id={$_GET['id']}"));
?>

<script>
$(function() {
	$( "#dob" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1985:2022",
		dateFormat:"yy-mm-dd"
	});
});
</script>

<form action="" method="post" enctype="multipart/form-data" style="width:auto">
	<h3>Add patient</h3>
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
	<input type="text" name="dob" id="dob" <?php echo "value=\"{$patient['dob']}\""; ?> />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

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
	<label for="phone">Mobile number:&nbsp;&nbsp;</label>
	<input type="text" name="phone" id="phone" <?php echo "value=\"{$patient['phone']}\""; ?> />
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
	<input type="submit" name="submit" value="Save"/>
	</p>

</form>
<?php
}
else 
{
	echo "<h3>You cannot access this page directly!</h3>";
} 
include('footer.php'); ?>