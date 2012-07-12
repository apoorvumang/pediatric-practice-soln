<?php include('header.php'); 
if($_POST['choice']=="delete")
{
	if(mysql_query("DELETE FROM vaccines WHERE id={$_POST['vac_id']}"))
	{
		if(mysql_query("DELETE FROM vac_schedule WHERE v_id={$_POST['vac_id']}"))
			echo "Deletion successful!";
		else
			echo "Error deleting from vac_schedule";
	}
	else
		echo "Error deleting from vaccines";
}
else if($_POST['choice']=="edit")
{
	$vaccine = mysql_fetch_assoc(mysql_query("SELECT * FROM vaccines WHERE id={$_POST['vac_id']}"));
?>

<form action="addvac.php" method="post" enctype="multipart/form-data">
	<h3>Edit Vaccine</h3>
	<input type="hidden" name="id" value=<?php echo "\"{$vaccine['id']}\"";?>>
	<p>
	<label for="name">Vaccine:</label><br />
	<input type="text" name="name" id="name" value=<?php echo "\"{$vaccine['name']}\"";?>/>
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
	<label class="grey" for="dependent">Dependent on:&nbsp;&nbsp;</label>
	<select name="dependent" style="margin-right:60px;" >
	<option value=0 <?php if($vaccine['dependent']==0) echo "selected"; ?> >Birth</option>
	<?php
	$result = mysql_query("SELECT name, id FROM vaccines WHERE 1");
	while($vac = mysql_fetch_assoc($result))
	{
		echo "<option value=".$vac['id']." ";
		if($vaccine['dependent']==$vac['id']) echo "selected";
		echo " >".$vac['name']."</option>\n";
	}
	?>
	</select>
	</p>

	<p>
	<label class="grey" for="sex">Sex:&nbsp;&nbsp;</label>
	<select name="sex" style="margin-right:60px;">
	<option value='B' <?php if($vaccine['sex']=='B') echo "selected"; ?> >Both</option>
	<option value='M' <?php if($vaccine['sex']=='M') echo "selected"; ?> >Male</option>
	<option value='F' <?php if($vaccine['sex']=='F') echo "selected"; ?> >Female</option>
	</select>

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
	$result = mysql_query("SELECT name, id FROM vaccines WHERE 1");
	while($vac = mysql_fetch_assoc($result))
	{
		echo "<option value=".$vac['id'].">".$vac['name']."</option>\n";
	}
	?>
	</select>
	<br />
	<input type="radio" name="choice" value="edit" checked="true" /> Edit
	<input type="radio" name="choice" value="delete" /> Delete
	<input type="submit" name="submit" value="Go" />
	</p>
</form>

<?php 
}
include('footer.php'); ?>