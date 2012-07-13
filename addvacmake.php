<?php include('header.php'); 
if(isset($_POST['submit']))
{
	if(!$_POST['name'])
	{
		echo "Please enter name";
	}

	if(!count($err))
	{
		$_POST['name'] = mysql_real_escape_string($_POST['name']);
		if(mysql_query("INSERT INTO vac_make(v_id,name) VALUES({$_POST['v_id']}, '{$_POST['name']}')"))
		{
			echo "Successfully added vaccine make {$_POST['name']}!";
		}
		else
		{
			echo "Error adding new record.";
		}
	}

}
?>


<form action="" method="post" enctype="multipart/form-data">
	<h3>Add Vaccine Make</h3>
	
	<p>
	<label for="name">Name of make:</label><br />
	<input type="text" name="name" id="name"  />
	</p>

	<p>
	<label class="grey" for="v_id">Vaccine:&nbsp;&nbsp;</label>
	<select name="v_id" style="margin-right:60px;">
	<?php
	$result = mysql_query("SELECT name, id FROM vaccines WHERE 1");
	while($vac = mysql_fetch_assoc($result))
	{
		echo "<option value=".$vac['id'].">".$vac['name']."</option>\n";
	}
	?>
	</select>
	</p>

	<p>
	<input type="submit" name="submit" value="Add"/>
	</p>

</form>
<?php include('footer.php'); ?>