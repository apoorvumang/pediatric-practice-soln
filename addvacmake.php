<?php include('header.php'); 
if(isset($_POST['submit']))
{
	if(!$_POST['name'])
	{
		echo "Please enter name!";
	}
	else
	{
		$_POST['name'] = mysqli_real_escape_string($link, $_POST['name']);
		if(mysqli_query($link, "INSERT INTO vac_make(name) VALUES('{$_POST['name']}')"))
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
	<h3>Add Product</h3>
	
	<p>
	<label for="name">Name of product:</label><br />
	<input type="text" name="name" id="name" autofocus="autofocus" />
	</p>

	<p>
	<input type="submit" name="submit" value="Add"/>
	</p>

</form>
<?php include('footer.php'); ?>