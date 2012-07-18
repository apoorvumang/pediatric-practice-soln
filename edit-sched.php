<?php include('header.php'); 
if($_POST['vac_date'])
{
	$err = array();
	foreach ($_POST['delete_vac'] as $key => $value) {
		mysqli_query($link, "DELETE FROM vac_schedule WHERE id={$value}");		
	}
	// foreach ($_POST['vac_date'] as $key => $value) {
	// 	$value =date('Y-m-d', strtotime($value));
	// 	if(!mysqli_query($link, "UPDATE vac_schedule SET date='{$value}', make={$_POST['make'][$key]} WHERE id={$_POST['vac_id'][$key]}"))
	// 		$err[] = "Unknown error";
	// }
	foreach ($_POST['vac_given_date'] as $key => $value) {
		if($value!="0000-00-00"&&$value!=""&&$value!='nil')
		{
			$value =date('Y-m-d', strtotime($value));
			if(!mysqli_query($link, "UPDATE vac_schedule SET date_given='{$value}', given='Y' WHERE id={$_POST['vac_id'][$key]}"))
				$err[] = "Unknown error";
		}
	}
	foreach ($_POST['given'] as $key => $value) {
		if($value=='Y')
		{
			if(!mysqli_query($link, "UPDATE vac_schedule SET given='Y' WHERE id={$_POST['vac_id'][$key]}"))
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
?>
<script type="text/javascript">
	
	<?php for ($i=0; $i < 51; $i++) { ?>

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
			
	<?php } ?>
	
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
	<input type="text" name="vac_date[]" style="width:80px" <?php echo "id=\"vac_date".$count."\""; ?> value=<?php echo "\"".date('j M Y',strtotime($row['date']))."\"";?>/>
		</td>

		<td>
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
		</td>
		<?php
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