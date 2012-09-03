<?php
function schedule($patient, $vaccine)
{
	global $link;
	if(!(($vaccine['sex']=='B')||($vaccine['sex']==$patient['sex'])))	//Checking if sex is correct
		return 1;
	$temp_nofdays = "+".$vaccine['no_of_days']." days";

	if($vaccine['dependent']==0)	//If dependent on birth
	{
		$date_vac = date("Y-m-d",strtotime($temp_nofdays, strtotime($patient['dob'])));
	}
	else
	{
		//Need to get scheduled date of dependent vaccine. vac_schedule is searched with patient and dep vac id
		$dep_vac_sched = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id = {$patient['id']} AND v_id = {$vaccine['dependent']}"));
		$date_vac = date("Y-m-d",strtotime($temp_nofdays, strtotime($dep_vac_sched['date'])));
	}
	if((date('D', strtotime($date_vac)))=='Sun')
	{
		$date_vac = date("Y-m-d", strtotime("+1 days", strtotime($date_vac)));
	}
	if(!mysqli_query($link, "INSERT INTO vac_schedule(p_id, v_id, date) VALUES({$patient['id']}, {$vaccine['id']}, '{$date_vac}')"))
	{
		return -1;
	}
	else
		return 1;
}

function generate_patient_schedule($patient_id)
{
	$err = "";
	global $link;
	mysqli_query($link, "DELETE FROM vac_schedule WHERE p_id = {$patient_id}");
	$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM patients WHERE id = {$patient_id}"));
	$result = mysqli_query($link, "SELECT * FROM vaccines WHERE 1 ORDER BY dependent ASC");	//Those with lower dep come first (very important!)
	while($vaccine = mysqli_fetch_assoc($result))	//Select vaccines one by one from vaccines table
	{
		if(schedule($patient, $vaccine)==-1)
			$err = "Unidentified error";
	}
	if($err=="")
	{
		echo "Successfully created vaccination schedule for {$patient['name']}! <br />";
	}
	else
	{
		echo $err;
	}
}

function generate_vaccine_schedule($vaccine)
{
	$err="";
	global $link;
	$result = mysqli_query($link, "SELECT id,sex,dob FROM patients WHERE 1");
	while($patient = mysqli_fetch_assoc($result))
	{
		if(schedule($patient, $vaccine)==-1)
			$err = "Unidentified error";
	}
	if($err=="")
	{
		echo "Successfully created vaccination schedule for {$vaccine['name']}! <br />";
	}
	else
	{
		echo $err;
	}
}
?>