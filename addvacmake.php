<?php include('header.php');
if(isset($_POST['submit']))
{
	if(!$_POST['name'])
	{
		echo "Please enter name!";
	}
	else
	{
		$description = $_POST['description'];
		$name = mysqli_real_escape_string($link, $_POST['name']);
		$price = $_POST['price'];
		$for_invoice = $_POST['for_invoice'];
		$query = "INSERT INTO vac_make(name, price, description, for_invoice) VALUES('{$name}', {$price}, '{$description}', '{$for_invoice}');";
		if(mysqli_query($link, $query))
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

<style>
input[type="checkbox"]{
width: 30px;
height: 30px;
border-width: 0;
}
</style>


<form action="" method="post" enctype="multipart/form-data">
	<h3>Add Product</h3>

	<p>
	<label for="name">Name of product:</label><br />
	<input type="text" name="name" id="name" autofocus="autofocus" />
	</p>
	<p>
	<label for="price">Price:</label><br />
	<input type="number" name="price" id="price"/>
	</p>
	<p>
	<label for="description">Description:</label><br />
	<input type="text" name="description" id="description"/>
	</p>

	<p>
		<label for="for_invoice">Add to invoice list?</label>
		<select name="for_invoice">
		  <option value="Y">Y</option>
		  <option value="N">N</option>
		</select>
	</p>

	<h4>Select vaccines to associate with this product</h4>
	<table>
	<?php
		$query = "SELECT id, name from vaccines WHERE 1";
		$result = mysqli_query($link, $query);
		while($vaccine = mysqli_fetch_assoc($result)) {
			echo "<tr>";
			echo "<td>";
			echo "<input type='checkbox' name='associatedVaccine[]' value='{$vaccine['id']}'>";
			echo "</td>";
			echo "<td>";
			echo "{$vaccine['name']}";
			echo "</td>";
			echo "</tr>";
		}

	?>
</table>

	<p>
	<input type="submit" name="submit" value="Add"/>
	</p>

</form>
<?php include('footer.php'); ?>
