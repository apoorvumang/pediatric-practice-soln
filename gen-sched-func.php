<?php
function generate_patient_schedule($patient_id)
{
	$err = "";
	mysql_query("DELETE FROM vac_schedule WHERE p_id = {$patient_id}");
	$patient = mysql_fetch_assoc(mysql_query("SELECT * FROM patients WHERE id = {$patient_id}"));
	$result = mysql_query("SELECT * FROM vaccines WHERE 1 ORDER BY dependent ASC");	//Those with lower dep come first (very important!)
	while($vaccine = mysql_fetch_assoc($result))	//Select vaccines one by one from vaccines table
	{
		if(!(($vaccine['sex']=='B')||($vaccine['sex']==$patient['sex'])))	//Checking if sex is correct
			continue;
		$temp_nofdays = "+".$vaccine['no_of_days']." days";

		if($vaccine['dependent']==0)	//If dependent on birth
		{
			$date_vac = date("Y-m-d",strtotime($temp_nofdays, strtotime($patient['dob'])));
		}
		else
		{
			//Need to get scheduled date of dependent vaccine. vac_schedule is searched with patient and dep vac id
			$dep_vac_sched = mysql_fetch_assoc(mysql_query("SELECT * FROM vac_schedule WHERE p_id = {$patient_id} AND v_id = {$vaccine['dependent']}"));
			$date_vac = date("Y-m-d",strtotime($temp_nofdays, strtotime($dep_vac_sched['date'])));
		}

		if(!mysql_query("INSERT INTO vac_schedule(p_id, v_id, date) VALUES({$patient_id}, {$vaccine['id']}, '{$date_vac}')"))
		{
			$err = "Unidentified error";
		}
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
?>