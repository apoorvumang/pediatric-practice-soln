<?php
include('header.php');


function schedule($patient, $vaccine)
{
	global $link;

	$temp_nofdays = "+".$vaccine['no_of_days']." days";

	if($vaccine['dependent']==0)	//If dependent on birth
	{
		$date_vac = date("Y-m-d",strtotime($temp_nofdays, strtotime($patient['dob'])));
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



function generate_vaccine_schedule()
{
	$err="";
	global $link;
  $vaccine = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vaccines WHERE id=73"));
	$query = "SELECT id, sex, dob FROM patients WHERE id NOT IN (SELECT p.id FROM patients p, vac_schedule vs WHERE p.id = vs.p_id AND vs.v_id = 73)";
	$result = mysqli_query($link, $query);
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

generate_vaccine_schedule();
?>
