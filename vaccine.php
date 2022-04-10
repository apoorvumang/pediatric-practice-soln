<?php include('header.php'); ?>
<script>
$(function() {
$( "#after_dob" ).datepicker({
changeMonth: true,
changeYear: true,
yearRange: "1970:2032",
dateFormat:"d M yy"
});
});
</script>
<form action="addvac.php" method="post" enctype="multipart/form-data">
	<h3>Add Vaccine</h3>

	<p>
	<label for="name">Vaccine:</label><br />
	<input type="text" name="name" id="name"  />
	</p>

	<p>
	<label for="no_of_days">Recommended interval to next dose (days):</label>
	<input type="text" name="no_of_days" id="no_of_days" />
	</p>
	<p>
	<label for="lower_limit">Recommended min age (days):</label>
	<input type="text" name="lower_limit" id="lower_limit" />
	</p>
	<p>
	<label for="upper_limit">Recommended max age (days):</label>
	<input type="text" name="upper_limit" id="upper_limit" />
	</p>


	<p>
	<label class="grey" for="dependent">Dependent on:&nbsp;&nbsp;</label>
	<select name="dependent" style="margin-right:60px;">
	<option value=0>Birth</option>
	<?php
	$result = mysqli_query($link, "SELECT name, id FROM vaccines WHERE 1 ORDER BY name");
	while($vac = mysqli_fetch_assoc($result))
	{
		echo "<option value=".$vac['id'].">".$vac['name']."</option>\n";
	}
	?>
	</select>
	</p>

	<p>
	<label class="grey" for="sex">Sex:&nbsp;&nbsp;</label>
	<select name="sex" style="margin-right:60px;">
	<option value='B'>Both</option>
	<option value='M'>Male</option>
	<option value='F'>Female</option>
	</select>

	</p>

	<p>
		<input type="checkbox" name="update" value="1" checked="checked" />
		<strong> Update for existing patients after dob </strong>
		<input type="text" name="after_dob" id="after_dob" style="margin-right:40px;" value=<?php echo "\"".date('j M Y')."\"";?>/>
	</p>

	<p>
	<input type="submit" name="submit" value="Add"/>
	</p>

</form>
<?php include('footer.php'); ?>
