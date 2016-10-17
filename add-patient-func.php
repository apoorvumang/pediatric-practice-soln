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

	if(($patient_var['email2']))
	{
		if(!checkEmail($patient_var['email2']))
		{
			$err[]='Your email 2 is not valid!';
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
	$patient_var['obstetrician'] = ucwords($patient_var['obstetrician']);
	$patient_var['place_of_birth'] = ucwords($patient_var['place_of_birth']);
	if(!$patient_var['active'])
	{
		$patient_var['active'] = '0';
	}
	if((!$patient_var['doregistration'])||($patient_var['doregistration']=='0000-00-00'))
	{
		$patient_var['doregistration'] = date('Y-m-d');
	}
	if($patient_var['phone'][0]!='0')
			$patient_var['phone']='0'.$patient_var['phone'];
	if($patient_var['phone2'])
	{	
		if($patient_var['phone2'][0]!='0')
			$patient_var['phone2']='0'.$patient_var['phone2'];
	}
}



function populateSibling($oldPatientID, $newPatientID) {
	global $link;
	$query = "UPDATE patients p1, patients p2 SET p1.phone = p2.phone, p1.phone2 = p2.phone2, p1.father_name = p2.father_name, p1.father_occ = p2.father_occ, p1.mother_name = p2.mother_name, p1.mother_occ = p2.mother_occ, p1.address = p2.address, p1.email = p2.email, p1.email2 = p2.email2 WHERE p1.id = {$newPatientID} AND p2.id = {$oldPatientID};";
	$result = mysqli_query($link, $query);
	if($result)
		return true;
	else
		return false;
}



function addPatient($patient_var)
{
	global $link;
	prePatient($patient_var);
	$err = validatePatient($patient_var);
	if(!count($err))
	{
		$patient_var['email'] = mysqli_real_escape_string($link, $patient_var['email']);
		$patient_var['email2'] = mysqli_real_escape_string($link, $patient_var['email2']);
		$patient_var['name'] = mysqli_real_escape_string($link, $patient_var['name']);
		$patient_var['phone'] = mysqli_real_escape_string($link, $patient_var['phone']);
		$patient_var['dob'] = mysqli_real_escape_string($link, $patient_var['dob']);
		$patient_var['note'] = mysqli_real_escape_string($link, $patient_var['note']);

		// Escape the input data
		if(mysqli_query($link, "INSERT INTO patients(name,first_name,last_name,email,email2,dob,phone,phone2,sex,father_name,father_occ,mother_name,mother_occ,address,
			birth_weight,born_at,head_circum,length,mode_of_delivery,gestation,sibling,active,date_of_registration,obstetrician,place_of_birth)
					VALUES(
					'".$patient_var['name']."', '".$patient_var['first_name']."', '".$patient_var['last_name']."',
					'".$patient_var['email']."',
					'".$patient_var['email2']."',
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
					'".$patient_var['sibling']."',
					".$patient_var['active'].",
					'".$patient_var['doregistration']."',
					'".$patient_var['obstetrician']."',
					'".$patient_var['place_of_birth']."')"))
		{
			$new_patient_id = mysqli_insert_id($link);
			$_SESSION['msg']['reg-success']="Patient successfully added! Patient id is <strong>".$new_patient_id."</strong>";
			// add not for patient if it exists
			if($patient_var['note']) {
				$q =  "INSERT into notes(p_id, date, note)
						VALUES(
						{$new_patient_id},
						'".date("Y-m-d")."',
						'{$patient_var['note']}')";
				if(mysqli_query($link, $q)) {
					$_SESSION['msg']['reg-success']="Note successfully added!";
				} else {
					
					$err[] = "Error in adding note. Query: ".$q;
				}
			}
			if($patient_var['add_sibling']!=0)
			{
				$siblings_result = mysqli_query($link, "SELECT * FROM siblings WHERE p_id = {$patient_var['add_sibling']}");
				$total_string = " ";
				while($row = mysqli_fetch_assoc($siblings_result))
				{
					$total_string = $total_string."(".$row['s_id'].",".$new_patient_id."),";
					$total_string = $total_string."(".$new_patient_id.",".$row['s_id']."),";
				}
				$total_string = $total_string."(".$new_patient_id.",".$patient_var['add_sibling']."),";
				$total_string = $total_string."(".$patient_var['add_sibling'].",".$new_patient_id.")";
				if(mysqli_query($link, "INSERT INTO siblings(p_id, s_id) VALUES ".$total_string))
				{
					$_SESSION['msg']['reg-success'] = $_SESSION['msg']['reg-success']."<br>Sibling added!";
					if(!populateSibling($patient_var['add_sibling'], $new_patient_id))
						$err[] = 'Error populating sibling';
				}
				else
					$err[] = "Error adding sibling";
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
		$patient_var['email2'] = mysqli_real_escape_string($link, $patient_var['email2']);
		$patient_var['name'] = mysqli_real_escape_string($link, $patient_var['name']);
		$patient_var['phone'] = mysqli_real_escape_string($link, $patient_var['phone']);
		$patient_var['dob'] = mysqli_real_escape_string($link, $patient_var['dob']);
		$patient_var['add_sibling'] = mysqli_real_escape_string($link, $patient_var['add_sibling']);
		// Escape the input data

		if(mysqli_query($link, "UPDATE patients SET 
			name = '{$patient_var['name']}',
			first_name = '".$patient_var['first_name']."',
			last_name =  '".$patient_var['last_name']."',
			email = '".$patient_var['email']."',
			email2 = '".$patient_var['email2']."',
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
			active = '".$patient_var['active']."',
			date_of_registration = '".$patient_var['date_of_registration']."',
			obstetrician = '".$patient_var['obstetrician']."',
			place_of_birth = '".$patient_var['place_of_birth']."',
			address = '".$patient_var['address']."' WHERE id = {$patient_var['id']}"))
		{
			$_SESSION['msg']['reg-success']="Patient successfully edited!";
		}
		else
			$err[]='An unknown error has occured.';
		if($patient_var['delete_siblings'])
		{
			if(mysqli_query($link, "DELETE FROM siblings WHERE p_id = {$patient_var['id']} OR s_id = {$patient_var['id']}"))
			{
				$_SESSION['msg']['reg-success'] = $_SESSION['msg']['reg-success']."<br>Sibling(s) deleted!";
			}
			else
				$err[] = 'Error deleting sibling(s)';
		}
		if($patient_var['add_sibling'] > 0)
		{
			// get sibling lists for both patients
			// add patients to those lists also
			// cross multiply and insert if not exists
			$result1 = mysqli_query($link, "SELECT * FROM siblings WHERE p_id = {$patient_var['id']}");
			$result2 = mysqli_query($link, "SELECT * FROM siblings WHERE p_id = {$patient_var['add_sibling']}");
			$total_string = " ";
			$arr1 = [];
			$arr2 = [];
			while($row = mysqli_fetch_assoc($result1))
			{
				$arr1[] = $row['s_id'];
			}
			while($row = mysqli_fetch_assoc($result2))
			{
				$arr2[] = $row['s_id'];
			}
			$arr1[] = $patient_var['id'];
			$arr2[] = $patient_var['add_sibling'];
			foreach ($arr1 as $key1 => $value1) {
				foreach ($arr2 as $key2 => $value2) {
					$total_string = $total_string."(".$value1.",".$value2."),";
					$total_string = $total_string."(".$value2.",".$value1."),";
				}
			}
			$total_string = rtrim($total_string, ",");
			if(mysqli_query($link, "INSERT IGNORE INTO siblings(p_id, s_id) VALUES ".$total_string))
			{
				$_SESSION['msg']['reg-success'] = $_SESSION['msg']['reg-success']."<br>Sibling added!";
				$oldPatientID = 0;
				$newPatientID = 0;
				if($patient_var['id'] > $patient_var['add_sibling']) {
					$oldPatientID = $patient_var['add_sibling'];
					$newPatientID = $patient_var['id'];
				}
				else {
					$newPatientID = $patient_var['add_sibling'];
					$oldPatientID = $patient_var['id'];
				}
				if(populateSibling($oldPatientID, $newPatientID)) {
					$_SESSION['msg']['reg-success'] = $_SESSION['msg']['reg-success']."<br>Sibling populated!";
				}
				else {
					$err[] = 'Error populating sibling';
				}
			}
			else
				$err[] = 'Error adding sibling';
		}
	}

	if(count($err))
	{
		$_SESSION['msg']['reg-err'] = implode('<br />',$err);
	}
}
?>
