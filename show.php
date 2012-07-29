<?php include('header.php'); 
if($_POST['id'])	//id posted and not 0? weird TODO change this maybe?
{
	Redirect("edit-sched.php?id={$_POST['id']}");
	exit;
}
?>

<h3>Patient Information</h3>
<form action="" method="post">
	<label for="id">Enter ID:</label>
	<input type="text" name="id" id="id" />
	<input type="submit" name="submit" value="Go" />
</form>
<table>
	<tbody>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Date of Birth</th>
			<th>Phone</th>
			<th>Sex</th>
		</tr>
		<?php $result = mysqli_query($link, "SELECT * FROM patients WHERE 1");
		while($row = mysqli_fetch_assoc($result))
		{
			
			echo "<tr>";
			echo "<td>".$row['id']."</td>";
			echo "<td><a href=edit-sched.php?id=".$row['id'].">";
			echo $row['name'];
			echo "</a></td>";
			echo "<td>".$row['dob']."</td>";
			echo "<td>".$row['phone']."</td>";
			echo "<td>".$row['sex']."</td>";
			echo "</tr>";
			
		}
		?>
	</tbody>
</table>


<?php include('footer.php'); ?>