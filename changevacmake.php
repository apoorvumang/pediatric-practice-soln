<?php include('header.php');
if($_POST['makechange']=='1')
{
	$name = $_POST['name'];
	$id = $_POST['id']; // product id
	$price = $_POST['price'];
	$description = $_POST['description'];
	$for_invoice = $_POST['for_invoice'];
	$query = "UPDATE vac_make SET name='{$name}', price = {$price}, description='{$description}', for_invoice='{$for_invoice}' WHERE id={$_POST['id']}";
	if(mysqli_query($link, $query))
		echo "Edited vac_make successfully!";
	else
		echo "Error making changes in vac_make :(";
	$query = "DELETE from vac_to_make WHERE vm_id = {$id};"; // deleting old connections because we will be adding them all later
	if(mysqli_query($link, $query)) {
		echo "Deleted from vac_to_make successfully!";
	} else {
		echo "Error deleting from vac_to_make :(";
	}
	$vaccine_id_array = $_POST['associatedVaccine'];
	foreach ($vaccine_id_array as $key => $vaccine_id) {
		$query = "INSERT into vac_to_make(v_id, vm_id) VALUES ('{$vaccine_id}', '{$id}')";
		if(!mysqli_query($link, $query)) {
			echo "Some error adding vac_to_make row :(";
		}
	}
}
else if($_POST['choice']=="delete")
{
	if(mysqli_query($link, "DELETE FROM vac_make WHERE id={$_POST['id']}"))
	{
		if(mysqli_query($link, "UPDATE vac_schedule SET make=0 WHERE make={$_POST['id']}"))
			echo "Deletion successful!";
		else
			echo "Error updating vac_schedule";
	}
	else
		echo "Error deleting from vac_make";
}
else if($_POST['choice']=="edit")
{
	$vac_make_row = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vac_make WHERE id={$_POST['id']}"));
	$id = $_POST['id'];
	$price = $vac_make_row['price'];
	$description = $vac_make_row['description'];
	$for_invoice = $vac_make_row['for_invoice'];
?>

<form action="" method="post" enctype="multipart/form-data">
	<h3>Edit Product</h3>


	<p>
	<label for="name">Product:</label><br />
	<input type="text" name="name" id="name" value=<?php echo "\"{$vac_make_row['name']}\"";?>/>
	<input type="hidden" name="id" value=<?php echo "\"{$vac_make_row['id']}\"";?>/>
	<input type="hidden" name="makechange" value="1"/>
	</p>
	<p>
	<label for="price">Price:</label><br />
	<input type="number" name="price" id="price" value=<?php echo "'{$price}'"; ?>/>
	</p>
	<p>
	<label for="description">Description:</label><br />
	<input type="text" name="description" id="description" value=<?php echo "'{$description}'"; ?>/>
	</p>

	<p>
		<label for="for_invoice">Add to invoice list?</label>
		<select name="for_invoice">
		  <option value="Y" <?php if($for_invoice == 'Y') {echo 'selected';} ?>>Y</option>
		  <option value="N" <?php if($for_invoice == 'N') {echo 'selected';} ?>>N</option>
		</select>
	</p>
	<h4>Select vaccines to associate with this product</h4>
	<table>
	<?php
		$query = "SELECT id, name from vaccines WHERE 1";
		$result = mysqli_query($link, $query);
		$query = "SELECT * FROM vac_to_make WHERE vm_id = {$id}";
		$result2 = mysqli_query($link, $query);
		$v_id_array = [];
		while($vaccine_associated_with_vm = mysqli_fetch_assoc($result2)) {
			$v_id_array[] = $vaccine_associated_with_vm['v_id'];
		}
		while($vaccine = mysqli_fetch_assoc($result)) {
			echo "<tr>";
			echo "<td>";
			echo "<input type='checkbox' name='associatedVaccine[]' value='{$vaccine['id']}'";
			if(in_array($vaccine['id'], $v_id_array)) {
				echo " checked ";
			}
			echo ">";
			echo "</td>";
			echo "<td>";
			echo "{$vaccine['name']}";
			echo "</td>";
			echo "</tr>";
		}

	?>
	</table>
	<p>
	<input type="submit" name="submit" value="Save changes"/>
	</p>

</form>



<?php
}
else {
?>
<form action="" method = "post">
	<h3>Edit/Delete Product</h3>

	<p>
	<label class="grey" for="id">Choose Product :&nbsp;&nbsp;</label>
	<select name="id" style="margin-right:60px;">
	<?php
	$result = mysqli_query($link, "SELECT name, id FROM vac_make WHERE 1 ORDER BY name ASC");
	while($vac_make = mysqli_fetch_assoc($result))
	{
		echo "<option value=".$vac_make['id'].">".$vac_make['name']."</option>\n";
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
