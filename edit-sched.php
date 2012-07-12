<?php include('header.php'); 
if($_POST['vac_date'])
{
	$err = array();
	foreach ($_POST['delete_vac'] as $key => $value) {
		mysql_query("DELETE FROM vac_schedule WHERE id={$value}");		
	}
	foreach ($_POST['vac_date'] as $key => $value) {
		if(!mysql_query("UPDATE vac_schedule SET date='{$value}' WHERE id={$_POST['vac_id'][$key]}"))
			$err[] = "Unknown error";
	}
	foreach ($_POST['vac_given_date'] as $key => $value) {
		if($value!="0000-00-00"&&$value!="")
		{
			if(!mysql_query("UPDATE vac_schedule SET date_given='{$value}' WHERE id={$_POST['vac_id'][$key]}"))
				$err[] = "Unknown error";
			if(!mysql_query("UPDATE vac_schedule SET given='Y' WHERE id={$_POST['vac_id'][$key]}"))
				$err[] = "Unknown error";
		}
	}
	foreach ($_POST['given'] as $key => $value) {
		if($value=='Y')
		{
			if(!mysql_query("UPDATE vac_schedule SET given='Y' WHERE id={$_POST['vac_id'][$key]}"))
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
	$patient = mysql_fetch_assoc(mysql_query("SELECT * FROM patients WHERE id = {$_GET['id']}"));
?>
<script type="text/javascript">
	
	<?php for ($i=0; $i < 51; $i++) { ?>

			$(function() {
				$( <?php echo "\"#vac_given_date_show".$i."\""; ?> ).datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: "1985:2022",
					dateFormat:"d M yy",
					altField: <?php echo "\"#vac_given_date".$i."\"" ?> ,
					altFormat: "yy-mm-dd"
				});
			});
			$(function() {
				$( <?php echo "\"#vac_date_show".$i."\""; ?> ).datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: "1985:2022",
					dateFormat:"d M yy",
					altField: <?php echo "\"#vac_date".$i."\"" ?> ,
					altFormat: "yy-mm-dd"
				});
			});
			
	<?php } ?>
	
</script>

<h4>Patient Information</h4>

<p>
<strong>Name :</strong> <?php echo $patient['name']; ?>
</p>

<p>
<strong>Date of Birth :</strong> <?php echo $patient['dob']; ?>
</p>

<p>
<strong>Sex :</strong> <?php echo $patient['sex']; ?>
</p>

<p>
<strong>Phone :</strong> <?php echo $patient['phone']; ?>
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
<p>
<strong>Sibling :</strong> <?php 
if($patient['sibling']==0)
	echo "None";
else
{
	echo "<a href=edit-sched.php?id=".$patient['sibling'].">";

	$sibling_row = mysql_fetch_assoc(mysql_query("SELECT name FROM patients WHERE id={$patient['sibling']}"));
	echo $sibling_row['name'];
	
	echo "</a>";
}

?>
</p>
<h4>Schedule</h4>
<form action="" method="post" style="width:800px;">
<table>
	<tbody>
		<tr>
			<th>Given</th>
			<th>Vaccine</th>
			<th>Sched Date</th>
			<th>Given Date</th>
			<th>Lower Limit</th>
			<th>Upper Limit</th>
			<th>Remove</th>
		</tr>

	<?php
	$result = mysql_query("SELECT * FROM vac_schedule WHERE p_id = {$_GET['id']} ORDER BY date, v_id");
	//To show lower and upper limit, we add them to birth date 
	$count = 0;
	while($row = mysql_fetch_assoc($result))
	{
		$vac = mysql_fetch_assoc(mysql_query("SELECT * FROM vaccines WHERE id = {$row['v_id']}"));
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
	<input type="hidden" name="vac_date[]" <?php echo "id=\"vac_date".$count."\""; ?> value=<?php echo "\"{$row['date']}\"";?>/>
	<input type="text" style="width:80px" <?php echo "id=\"vac_date_show".$count."\""; ?> value=<?php echo "\"".date('j M Y',strtotime($row['date']))."\"";?>/>
		</td>

		<td>
	<input type="hidden" name="vac_given_date[]" <?php echo "id=\"vac_given_date".$count."\""; ?> value=<?php echo "\"{$row['date_given']}\"";?>/>
	<input type="text" style="width:80px" <?php echo "id=\"vac_given_date_show".$count."\""; ?> value=<?php 
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
		echo "<td>";
		echo "<input type=\"checkbox\" value=\"".$row['id']."\" name=\"delete_vac[]\">";
		echo "<input type=\"hidden\" value=\"".$row['id']."\" name=\"vac_id[]\">";
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