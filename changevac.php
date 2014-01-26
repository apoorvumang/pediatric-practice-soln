<?php include('header.php'); 
if($_POST['choice']=="delete")
{
	if(mysqli_query($link, "DELETE FROM vaccines WHERE id={$_POST['vac_id']}"))
	{
		if(mysqli_query($link, "DELETE FROM vac_schedule WHERE v_id={$_POST['vac_id']}"))
			echo "Deletion successful!";
		else
			echo "Error deleting from vac_schedule";
	}
	else
		echo "Error deleting from vaccines";
}
else if($_POST['choice']=="edit")
{
	$vaccine = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vaccines WHERE id={$_POST['vac_id']}"));
?>

<form action="addvac.php" method="post" enctype="multipart/form-data">
	<h3>Edit Vaccine</h3>
	<input type="hidden" name="id" value=<?php echo "\"{$vaccine['id']}\"";?>>
	<p>
	<label for="name">Vaccine:</label><br />
	<input type="text" name="name" id="name" value=<?php echo "\"{$vaccine['name']}\"";?>/>
	</p>
	<p>
		<label>Linked to Vaccine: </label>
		<?php 
		if($vaccine['dependent']==0)
			echo "Birth";
		else
		{
			$depvac = mysqli_fetch_assoc(mysqli_query($link, "SELECT name FROM vaccines WHERE id = {$vaccine['dependent']}"));
			echo $depvac['name'];
		}
		?>
	</p>
	<p>
	<label for="no_of_days">Recommended interval to next dose (days):</label>
	<input type="text" name="no_of_days" id="no_of_days" value=<?php echo "\"{$vaccine['no_of_days']}\"";?>/>
	</p>
	<p>
	<label for="lower_limit">Recommended min age (days):</label>
	<input type="text" name="lower_limit" id="lower_limit" value=<?php echo "\"{$vaccine['lower_limit']}\"";?>/>
	</p>
	<p>
	<label for="upper_limit">Recommended max age (days):</label>
	<input type="text" name="upper_limit" id="upper_limit" value=<?php echo "\"{$vaccine['upper_limit']}\"";?>/>
	</p>
	<p>
		<input type="checkbox" name="update" value="1" checked="checked" /> 
		<strong> Update for existing patients </strong>
	</p>
	<p>
	<input type="submit" name="submit" value="Save changes"/>
	</p>

</form>



<?php
}
else {
?>
<form action="" method = "post">
	<h3>Edit/Delete Vaccines</h3>

	<p>
	<label class="grey" for="vac_id">Choose Vaccine :&nbsp;&nbsp;</label>
	<select name="vac_id" style="margin-right:60px;">
	<?php
	$result = mysqli_query($link, "SELECT name, id FROM vaccines WHERE 1 ORDER BY name");
	while($vac = mysqli_fetch_assoc($result))
	{
		echo "<option value=".$vac['id'].">".$vac['name']."</option>\n";
	}
	?>
	</select>
	<br />
	<input type="radio" name="choice" value="edit" checked="checked" /> Edit
	<input type="radio" name="choice" value="delete" /> Delete
	<input type="submit" name="submit" value="Go" />
	</p>
</form>

<?php 
}
include('footer.php'); ?>