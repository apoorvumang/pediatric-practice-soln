<?php include('header.php'); 
if(isset($_POST['submit']))
{
	if(!$_POST['name'])
	{
		echo "Please enter name!";
	}
	else
	{
		$_POST['name'] = mysql_real_escape_string($_POST['name']);
		if(mysql_query("INSERT INTO vac_make(name) VALUES('{$_POST['name']}')"))
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
	<input type="text" name="name" id="name" autofocus="autofocus" />
	</p>

	<p>
	<input type="submit" name="submit" value="Add"/>
	</p>

</form>
<?php include('footer.php'); ?>