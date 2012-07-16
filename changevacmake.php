<?php include('header.php');
if($_POST['makechange']=='1')
{
	if(!$_POST['name'])
		echo "Please enter a name!";
	else if(mysqli_query($link, "UPDATE vac_make SET name='{$_POST['name']}' WHERE id={$_POST['id']}"))
		echo "Changes save successfully!";
	else
		echo "Error making changes.";
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