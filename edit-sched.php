<?php include('header.php'); 
if($_POST['vac_date'])
{
	$err = array();
	foreach ($_POST['delete_vac'] as $key => $value) {
		mysqli_query($link, "DELETE FROM vac_schedule WHERE id={$value}");		
	}
	//Old values are in same format as new values (slight overhead)
	foreach ($_POST['vac_given_date'] as $key => $value) {
		if($value!="0000-00-00"&&$value!=""&&$value!='nil')
		{
			if($value!=$_POST['vac_given_date_hidden'][$key])			//if changed
			{
				$value = date('Y-m-d', strtotime($value));
				if(!mysqli_query($link, "UPDATE vac_schedule SET date_given='{$value}', given='Y' WHERE id={$_POST['vac_sched_id'][$key]}"))
					$err[] = "Unknown error";	//set own given_date first
				//Now begins setting of dates of those dependent on this
				//If some vaccination from vac_schedule has been deleted, this code should not cause problem, as no value from vac_schedule is being read to 
				//calculate further values
				//TODO: optimize this thing, only select things that are needed
				$vaccine = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vaccines WHERE dependent ={$_POST['v_id'][$key]}"));
				//loop start
				while($vaccine)
				{
					$vaccine_schedule = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vac_schedule WHERE v_id ={$vaccine['id']} AND p_id={$_POST['p_id']}"));
					if($vaccine_schedule['given']=='Y')
						break;//if vaccine given, exit loop
					$date_temp = date("Y-m-d", strtotime("+".$vaccine['no_of_days']." days", strtotime($value)));
					mysqli_query($link, "UPDATE vac_schedule SET date = '{$date_temp}' WHERE v_id ={$vaccine['id']} AND p_id={$_POST['p_id']}"); //set its date accordingly
					$value = $date_temp;//$value = $date_temp
					$vaccine = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vaccines WHERE dependent ={$vaccine['id']}"));//$vaccine = new vaccine with dep as $vaccine['id']
				}//loop end
				
				
			}
		}
		else
		{
			if($value!=$_POST['vac_given_date_hidden'][$key])	//if value changed
				if(!mysqli_query($link, "UPDATE vac_schedule SET date_given='', given='N' WHERE id={$_POST['vac_sched_id'][$key]}"))
					$err[] = "Unknown error";	//set given date to null, set given to N
		}
	}
	foreach ($_POST['vac_date'] as $key => $value) {
		//if changed
		if($value!=$_POST['vac_date_hidden'][$key])
		{
			$value =date('Y-m-d', strtotime($value));
			if(!mysqli_query($link, "UPDATE vac_schedule SET date='{$value}', make={$_POST['make'][$key]} WHERE id={$_POST['vac_sched_id'][$key]}"))
				$err[] = "Unknown error";
		}
	}
	foreach ($_POST['make'] as $key => $value) {
		//if changed
		if($value!=$_POST['make_hidden'][$key])
		{
			if(!mysqli_query($link, "UPDATE vac_schedule SET make={$value} WHERE id={$_POST['vac_sched_id'][$key]}"))
				$err[] = "Unknown error";
		}
	}
	foreach ($_POST['given'] as $key => $value) {
		if($value=='Y')
		{
			if(!mysqli_query($link, "UPDATE vac_schedule SET given='Y' WHERE id={$_POST['vac_sched_id'][$key]}"))
				$err[] = "Unknown error";
		}
	}
	if(!$err)
	{
		echo "Changes saved successfully!";
	}
	else
	{
		implode($err_total, $err);
		echo $err_total;
	}
}
if($_GET['id'])
{
	$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM patients WHERE id = {$_GET['id']}"));
	$temp_result = mysqli_query($link, "SELECT id FROM vaccines WHERE 1");
	$temp_nrows = mysqli_num_rows($temp_result);
?>
<script type="text/javascript">
	//TODO 51 is total vaccines right now. This should actually be total number of vaccines in table vaccines
	<?php for ($i=0; $i < $temp_nrows; $i++) { ?>

			$(function() {
				$( <?php echo "\"#vac_given_date".$i."\""; ?> ).datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: "1985:2022",
					dateFormat:"d M yy"
				});
			});
			$(function() {
				$( <?php echo "\"#vac_date".$i."\""; ?> ).datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: "1985:2022",
					dateFormat:"d M yy"
				});
			});
			
	<?php } 
	unset($temp_result);
	unset($temp_nrows); 
	?>
	
</script>

<h4>Patient Information</h4>
<div style="float:right"> <a href= <?php echo "editpatient.php?id={$patient['id']}" ?> ><strong> Edit patient </strong> </a></div>
<p>
	<strong>Patient ID: <?php echo $patient['id'] ?> </strong>
</p>
<p>
<strong>Name :</strong> <?php echo $patient['name']; ?>
</p>

<p>
<strong>Date of Birth :</strong> <?php echo  date('d-F-Y', strtotime($patient['dob'])); ?>
</p>

<p>
<strong>Sex :</strong> <?php echo $patient['sex']; ?>
</p>

<p>
<strong>Phone 1:</strong> <?php echo $patient['phone']; ?>
</p>
<p>
<strong>Phone 2:</strong> <?php echo $patient['phone2']; ?>
</p>
<p>
<strong>Father's name :</strong> <?php echo $patient['father_name']; ?>
</p>
<p>
<strong>Mother's name :</strong> <?php echo $patient['mother_name']; ?>
</p>
<p>
<strong>Address :</strong> <?php echo $patient['address']; ?>
</p>

<?php 
if($patient['sibling']==0)
	echo "<p><strong>Sibling: None</strong></p>";
else
{
	$siblist = explode(",", $patient['sibling']);
	foreach ($siblist as $key => $value) 
	{
		?>
		<p>
		<strong>Sibling :</strong> 
		<?php
		echo "<a href=edit-sched.php?id=".$value.">";

		$sibling_row = mysqli_fetch_assoc(mysqli_query($link, "SELECT name,dob,sex FROM patients WHERE id={$value}"));
		echo $sibling_row['name'];
		
		echo "</a>";
		?>
		</p>
		<p>
			<strong>Sibling dob:</strong> <?php echo date('d-F-Y', strtotime($sibling_row['dob'])); ?>
		</p>
		<p>
			<strong>Sibling sex:</strong> <?php echo $sibling_row['sex']; ?>
		</p>
		<?php
	}
}

?>

<h4>Schedule</h4>
<form action="" method="post" style="width:800px;background:none;border:none">
	<input type="hidden" name="p_id" value=<?php echo $patient['id'] ?> />
<input type="submit" name="submit" value="Save Changes" />
<table>
	<tbody>
		<tr>
			<th>Given</th>
			<th>Vaccine</th>
			<th>Sched Date</th>
			<th>Given Date</th>
			<th>Lower Limit</th>
			<th>Upper Limit</th>
			<th>Product Name</th>
			<th>Remove</th>
		</tr>

	<?php
	$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id = {$_GET['id']} ORDER BY date, v_id");
	//To show lower and upper limit, we add them to birth date 
	$count = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$vac = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vaccines WHERE id = {$row['v_id']}"));
		$temp_nofdays = "+".$vac['lower_limit']." days";
		$lower_limit = date('d-F-Y', strtotime($temp_nofdays, strtotime($patient['dob'])));
		if($vac['upper_limit'] > 36500)
			$upper_limit = "None";
		else
		{
			$temp_nofdays = "+".$vac['upper_limit']." days";
			$upper_limit = date('d-F-Y', strtotime($temp_nofdays, strtotime($patient['dob'])));
		}
		echo "<tr ";
		if ($row['given']=='Y')
			echo "id=\"focus_green\"";	//green focus if vaccine has been given
		else if (strtotime("now") < strtotime($row['date']))
			echo "id=\"focus_yellow\"";	//yellow focus if sched date is yet to come
		else if (($vac['upper_limit'] > 36500)||(strtotime("now") < strtotime("+".$vac['upper_limit']." days", strtotime($patient['dob']))))	//strtotime causes error if too large value is given
			echo "id=\"focus_orange\"";	//orange focus if sched date has gone but vac can still be given
		else
			echo "id=\"focus_red\"";	//red focus if vaccine cant be given now
		echo " >";
		echo "<td>";
		?>
		<select name="given[]" style="">
		<option value='Y' <?php if($row['given']=='Y') echo "selected"; ?> >Y</option>
		<option value='N' <?php if($row['given']=='N') echo "selected"; ?> >N</option>
		</select>
		<?php
		echo "</td>";
		echo "<td>";
		echo $vac['name'];
		echo "</td>";

		?>

		<td>
	<input type="hidden" name="vac_date_hidden[]" value=<?php echo "\"".date('j M Y',strtotime($row['date']))."\"";?>/>
	<input type="text" name="vac_date[]" style="width:80px" <?php echo "id=\"vac_date".$count."\""; ?> value=<?php echo "\"".date('j M Y',strtotime($row['date']))."\"";?>/>
		</td>

		<td>
	<input type="hidden" name="vac_given_date_hidden[]" value=<?php 
	if($row['date_given']=='0000-00-00'||$row['date_given']=='')
		echo "\"nil\"";
	else
		echo "\"".date('j M Y',strtotime($row['date_given']))."\"";?>/>
	<input type="text" name="vac_given_date[]" style="width:80px" <?php echo "id=\"vac_given_date".$count."\""; ?> value=<?php 
	if($row['date_given']=='0000-00-00'||$row['date_given']=='')
		echo "\"nil\"";
	else
		echo "\"".date('j M Y',strtotime($row['date_given']))."\"";?>/>
		</td>		
		<?php
		echo "<td>";
		echo $lower_limit;
		echo "</td>";
		echo "<td>";
		echo $upper_limit;
		echo "</td>";
		?>
		<td>
			<select name="make[]">
				<option value=0 <?php if($row['make']==0) echo "selected"; ?> >None</option>
				<?php
				$result_make = mysqli_query($link, "SELECT * FROM vac_make WHERE 1 ORDER BY name ASC");
				while($vac_make = mysqli_fetch_assoc($result_make))
				{
					echo "<option value=".$vac_make['id'];
					if($row['make']==$vac_make['id'])
						echo " selected ";
					echo ">".$vac_make['name']."</option>\n";
				}
				?>
			</select>
			<input type="hidden" name="make_hidden[]" value=<?php echo "\"{$row['make']}\"";?> />
		</td>
		<?php
		echo "<td>";
		echo "<input type=\"checkbox\" value=\"".$row['id']."\" name=\"delete_vac[]\">";
		echo "<input type=\"hidden\" value=\"".$row['id']."\" name=\"vac_sched_id[]\">";
		echo "<input type=\"hidden\" value=\"".$row['v_id']."\" name=\"v_id[]\">";
		echo "</td>";
		echo "</tr>";
		$count++;
	}
	?>
	</tbody>
</table>
<input type="submit" name="submit" value="Save Changes" />
<br />
<br />
<br />
<br />
<br />
</form>
<?php
}
else
{
	header("Location: show.php");
	exit;
}
include('footer.php'); ?>