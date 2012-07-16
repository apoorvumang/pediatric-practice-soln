<?php include('header.php'); ?>

<h3>Patient Information</h3>
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