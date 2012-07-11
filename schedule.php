<?php include('header.php');
if($_POST['id'])
{
foreach ($_POST['id'] as $key => $patient_id) 
{
	
	$err = "";
	mysql_query("DELETE FROM vac_schedule WHERE p_id = {$patient_id}");
	$patient = mysql_fetch_assoc(mysql_query("SELECT * FROM patients WHERE id = {$patient_id}"));
	$result = mysql_query("SELECT * FROM vaccines WHERE 1");
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
}
?>

<form action="" method="post" enctype="multipart/form-data" style="width:auto">
<h3>Schedule Patients</h3>
<table>
	<tbody>
		<tr>
			<th>Select</th>
			<th>ID</th>
			<th>Name</th>
			<th>Date of Birth</th>
			<th>Phone</th>
			<th>Sex</th>
		</tr>
		<?php $result = mysql_query("SELECT * FROM patients WHERE 1");
		while($row = mysql_fetch_assoc($result))
		{
			echo "<tr>";
			echo "<td><input type=\"checkbox\" name=\"id[]\" value=".$row['id']." /></td>";
			echo "<td>".$row['id']."</td>";
			echo "<td>".$row['name']."</td>";
			echo "<td>".$row['dob']."</td>";
			echo "<td>".$row['phone']."</td>";
			echo "<td>".$row['sex']."</td>";
			echo "</tr>";
		}
		?>
	</tbody>
</table>

<p>
<input type="submit" name="submit" value="Generate Schedule"/>
</p>

</form>
<?php include('footer.php'); ?>