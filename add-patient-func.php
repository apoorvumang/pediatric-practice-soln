<?php
include_once('gen-sched-func.php');
include_once('header.php');

function checkEmail($str)
{
	return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
}



function validatePatient($patient_var)
{
	$err = array();
	
	if(($patient_var['email']))
	{
		if(!checkEmail($patient_var['email']))
		{
			$err[]='Your email is not valid!';
		}
	}

	if(($patient_var['phone']))
	{
		if( !preg_match("/^[0-9]{1,}$/", $patient_var['phone']) )
		{
			$err[]='Your phone number 1 is not valid!';
		}
	}

	if(($patient_var['phone2']))
	{
		if( !preg_match("/^[0-9]{1,}$/", $patient_var['phone2']) )
		{
			$err[]='Your phone number 2 is not valid!';
		}
	}
	
	if(!$patient_var['name'] || !$patient_var['dob'])
	{
		$err[] = 'First name and date of birth must be filled!';
	}

	if(strtotime($patient_var['dob']) > strtotime('now'))
	{
		$err[] = 'Enter a valid date!';
	}

	return $err;
}



function prePatient(&$patient_var)
{
	$patient_var['first_name'] = ucwords($patient_var['first_name']);
	$patient_var['last_name'] = ucwords($patient_var['last_name']);
	$patient_var['address'] = ucwords($patient_var['address']);
	$patient_var['name'] = $patient_var['first_name']." ".$patient_var['last_name'];
	$patient_var['father_name'] = ucwords($patient_var['father_name']);
	$patient_var['mother_name'] = ucwords($patient_var['mother_name']);
	if($patient_var['phone'][0]!='0')
			$patient_var['phone']='0'.$patient_var['phone'];
	if($patient_var['phone2'])
	{	
		if($patient_var['phone2'][0]!='0')
			$patient_var['phone2']='0'.$patient_var['phone2'];
	}
}



function addPatient($patient_var)
{
	global $link;
	prePatient($patient_var);
	$err = validatePatient($patient_var);
	if(!count($err))
	{
		$patient_var['email'] = mysqli_real_escape_string($link, $patient_var['email']);
		$patient_var['name'] = mysqli_real_escape_string($link, $patient_var['name']);
		$patient_var['phone'] = mysqli_real_escape_string($link, $patient_var['phone']);
		$patient_var['dob'] = mysqli_real_escape_string($link, $patient_var['dob']);
		
		// Escape the input data
		if(mysqli_query($link, "INSERT INTO patients(name,first_name,last_name,email,dob,phone,phone2,sex,father_name,father_occ,mother_name,mother_occ,address,
			birth_weight,born_at,head_circum,length,mode_of_delivery,gestation,sibling)
					VALUES(
					'".$patient_var['name']."', '".$patient_var['first_name']."', '".$patient_var['last_name']."',
					'".$patient_var['email']."',
					'".$patient_var['dob']."',
					'".$patient_var['phone']."',
					'".$patient_var['phone2']."',
					'".$patient_var['sex']."',
					'".$patient_var['father_name']."',
					'".$patient_var['father_occ']."',
					'".$patient_var['mother_name']."',
					'".$patient_var['mother_occ']."',
					'".$patient_var['address']."',
					'".$patient_var['birth_weight']."',
					'".$patient_var['born_at']."',
					'".$patient_var['head_circum']."',
					'".$patient_var['length']."',
					'".$patient_var['mode_of_delivery']."',
					'".$patient_var['gestation']."',
					'".$patient_var['sibling']."')"))
		{	
			$new_patient_id = mysqli_insert_id($link);
			$_SESSION['msg']['reg-success']="Patient successfully added! Patient id is <strong>".$new_patient_id."</strong>";
			//The previous code for sibling was COMPLETELY WRONG
			//Implementing timestamp based method
			if($patient_var['sibling']!=0)
			{
				$row_sibling = mysqli_fetch_assoc(mysqli_query($link, "SELECT sibling FROM patients WHERE id={$patient_var['sibling']}"));
				// if(!$row_sibling['sibling'])	//If sibling does not have any other sibling
				// {
					if(!mysqli_query($link, "UPDATE patients SET sibling='{$new_patient_id}' WHERE id={$patient_var['sibling']}"))
						$err[]="Some error in adding sibling";
				// }
				// else //If sibling has other sibling(s)
				// {
				// 	$new_sibling = $row_sibling['sibling'].",".$patient_var['sibling'];
				// 	echo "UPDATE patients SET sibling={$new_sibling} WHERE id={$patient_var['sibling']}";
				// 	if(!mysqli_query($link, "UPDATE patients SET sibling='{$new_sibling}' WHERE id={$patient_var['sibling']}"))
				// 		$err[]="Some error in adding sibling";
				// 	if(!mysqli_query($link, "UPDATE patients SET sibling='{$new_sibling}' WHERE id={$new_patient_id}"))
				// 		$err[]="Some error in adding sibling";
				// }
			}
			if($patient_var['gen_sched']=='1')
			{
				generate_patient_schedule($new_patient_id);
			}
		}
		else $err[]='An unknown error has occured.';
	}
	
	if(count($err))
	{
		$_SESSION['msg']['reg-err'] = implode('<br />',$err);
		return 0;
	}
	else
	{
		return $new_patient_id;
	}
}



function editPatient($patient_var)
{
	global $link;
	prePatient($patient_var);
	$err = validatePatient($patient_var);
	if(!count($err))
	{
		$patient_var['email'] = mysqli_real_escape_string($link, $patient_var['email']);
		$patient_var['name'] = mysqli_real_escape_string($link, $patient_var['name']);
		$patient_var['phone'] = mysqli_real_escape_string($link, $patient_var['phone']);
		$patient_var['dob'] = mysqli_real_escape_string($link, $patient_var['dob']);
		
		// Escape the input data

		if(mysqli_query($link, "UPDATE patients SET 
			name = '{$patient_var['name']}',
			first_name = '".$patient_var['first_name']."',
			last_name =  '".$patient_var['last_name']."',
			email = '".$patient_var['email']."',
			dob = '".$patient_var['dob']."',
			phone = '".$patient_var['phone']."',
			phone2 = '".$patient_var['phone2']."',
			sex = '".$patient_var['sex']."',
			father_name = '".$patient_var['father_name']."',
			father_occ = '".$patient_var['father_occ']."',
			mother_name = '".$patient_var['mother_name']."',
			mother_occ = '".$patient_var['mother_occ']."',
			birth_weight = '".$patient_var['birth_weight']."',
			born_at = '".$patient_var['born_at']."',
			head_circum = '".$patient_var['head_circum']."',
			length = '".$patient_var['length']."',
			mode_of_delivery = '".$patient_var['mode_of_delivery']."',
			gestation = '".$patient_var['gestation']."',
			address = '".$patient_var['address']."' WHERE id = {$patient_var['id']}"))
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
?>