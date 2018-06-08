<?php
//optional is when you need to add a vaccine to schedule as directly given
function schedule($patient, $vaccine, $vaccine_is_given_date = "0")
{
	global $link;
	if(!(($vaccine['sex']=='B')||($vaccine['sex']==$patient['sex'])))	//Checking if sex is correct
		return 1;

		if($vaccine_is_given_date != "0") {
			if(!mysqli_query($link, "INSERT INTO vac_schedule(p_id, v_id, date, date_given, given) VALUES({$patient['id']}, {$vaccine['id']}, '{$vaccine_is_given_date}', '{$vaccine_is_given_date}', 'Y')"))
			{
				return -1;
			}
			else
				return 1;
		}

	$temp_nofdays = "+".$vaccine['no_of_days']." days";

	if($vaccine['dependent']==0)	//If dependent on birth
	{
		$date_vac = date("Y-m-d",strtotime($temp_nofdays, strtotime($patient['dob'])));
	}
	else
	{
		//Need to get scheduled date of dependent vaccine. vac_schedule is searched with patient and dep vac id
		$dep_vac_sched = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id = {$patient['id']} AND v_id = {$vaccine['dependent']}"));
		//If dependent vaccine has already been given, scheduled date is given date + no. of days
		//otherwise it is scheduled date + no. of days
		if($dep_vac_sched['given'] == 'Y')
			$date_vac = date("Y-m-d",strtotime($temp_nofdays, strtotime($dep_vac_sched['date_given'])));
		else
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

function schedule_tuesday($patient, $vaccine) {
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
		//If dependent vaccine has already been given, scheduled date is given date + no. of days
		//otherwise it is scheduled date + no. of days
		if($dep_vac_sched['given'] == 'Y')
			$date_vac = date("Y-m-d",strtotime($temp_nofdays, strtotime($dep_vac_sched['date_given'])));
		else
			$date_vac = date("Y-m-d",strtotime($temp_nofdays, strtotime($dep_vac_sched['date'])));
	}
	while((date('D', strtotime($date_vac)))!='Tue') {
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
	// optional vaccines should not be added in generated schedule
	$result = mysqli_query($link, "SELECT * FROM vaccines WHERE optional = 'N' ORDER BY dependent ASC");	//Those with lower dep come first (very important!)
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

function regen_patient_schedule_tuesday($patient_id)
{
	// Remove all from vac_sched that have not been given yet
	// get list of those that haven't been given yet
	// order by dependency ascending
	// for each not given
	// get dep's date given/date sched from vac_sched
	// calculate new date
	// add to vac_sched
	$err = "";
	global $link;
	$vacs_not_given = mysqli_query($link, "SELECT v.optional as optional, v.id as id, v.name as name, v.no_of_days as no_of_days, v.dependent as dependent, v.sex as sex, v.lower_limit as lower_limit, v.upper_limit as upper_limit FROM vac_schedule vs, vaccines v WHERE vs.v_id = v.id AND vs.p_id = {$patient_id} AND vs.given = 'N' AND v.optional = 'N' ORDER BY dependent ASC");

	mysqli_query($link, "DELETE FROM vac_schedule WHERE p_id = {$patient_id} AND given = 'N'");
	$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM patients WHERE id = {$patient_id}"));
	while($vaccine = mysqli_fetch_assoc($vacs_not_given))	//Select vaccines one by one from those not given
	{
		if(schedule_tuesday($patient, $vaccine)==-1)
			$err = "Unidentified error";
	}
	if($err=="")
	{
		echo "Successfully created tuesday vaccination schedule for {$patient['name']}! <br />";
	}
	else
	{
		echo $err;
	}

}

function generate_vaccine_schedule($vaccine, $after_dob)
{
	$err="";
	global $link;
	if($after_dob) {
		$query = "SELECT id, sex, dob FROM patients WHERE dob > '{$after_dob}'";
	} else {
		$query = "SELECT id, sex, dob FROM patients WHERE 1";
	}
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
?>
