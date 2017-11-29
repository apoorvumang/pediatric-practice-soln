<?php include('header.php');
?>
<script>
        // $(document).ready(function() {
        //     // bind 'myForm' and provide a simple callback function
        //     $('#addduepayments').ajaxForm(function() {
				// 			console.log('hello');
        //       alert("Thank you for your comment!");
        //     });
        // });
				$(document).ready(function() {
					$("#submitall").click(function() {
						$('#addduepayments').ajaxSubmit();
						$('#previousdues').ajaxSubmit();
						$('#addvisitnote').ajaxSubmit();
						$('#previousvisits').ajaxSubmit();
						$('#myform').ajaxSubmit();
						alert("Submitted!");
					});
				});
				// wait for the DOM to be loaded
        // $(document).ready(function() {
        //     // bind 'myForm' and provide a simple callback function
        //     $('#myForm2').ajaxForm(function() {
        //         alert("Thank you for your comment!");
        //     });
        // });

</script>
<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="js/jquery.fileupload.js"></script>
<script type="text/javascript" src="js/cloudinary-jquery-file-upload.js"></script>


<script type="text/javascript">
$(document).ready(function() {
  $(".scan-pr").click(function(e) {
    e.preventDefault()
    console.log("scanning!")
    $(".spinner").show()
    $.ajax({
      type: "GET",
      url: "http://localhost:8899/scan",
      success: function(data) {
        console.log("success got scan")
        // console.log(data)
        $("#scanned_img").attr("src", 'data:image/jpg;base64,'+data);
        $(".spinner").hide()
        $(".scan-save").show()
        // alert('ok');
      },
      error: function(data) {
        console.log("error: ")
        console.log(data)
        $(".spinner").hide()
      }
    });
  })
})
</script>

<script type="text/javascript">
$(document).ready(function() {
  $.cloudinary.config({ cloud_name: 'dukqf8fvc', secure: true});
  $('.upload_field').unsigned_cloudinary_upload("uornhdlu",
    { cloud_name: 'dukqf8fvc', tags: 'browser_uploads' },
    { multiple: true }
    ).bind('cloudinarydone', function(e, data) {
      if (e) {
        console.log(e)
      }
      console.log(data);
      console.log("upload done!")
      $(".spinner").hide()
			var visitID = $("#visitIDForPrescriptionScan").text();
      $.ajax({
        type: 'POST',
        url: 'add-picture-prescription.php',
        data: {
          visit_id: visitID,
          url: data.result.url,
        },
        dataType: 'text',
        success: function(result) {
          console.log(result);
					alert('Prescription uploaded!');
          // location.reload()
        },
        error: function(data) {
          alert('error in reaching server: ' + data)
        }
      })
    }).bind('cloudinaryprogress', function(e, data) {
  // console.log(data)
  value = Math.round((data.loaded * 100.0) / data.total) + '%'
  console.log("value = " + value)
  $('.progress_bar').css('width', value);
});
    $(".scan-save").click(function(e) {
      $(".spinner").show()
      console.log("save clicked")
      e.preventDefault()
			var visitID = $(this).attr('class').split(" ").pop();
			console.log("visit id = ", visitID);
			$("#visitIDForPrescriptionScan").text(visitID);
      var data = $("#scanned_img").attr("src")
      $('.cloudinary_fileupload').fileupload('option', 'formData').file = data;
      $('.cloudinary_fileupload').fileupload('add', { files: [ data ]});
    })
  })
</script>

<input type="hidden" name="file" class="upload_field">
<?php
if($_POST['vac_date']) {
	$err = array();

	foreach ($_POST['delete_vac'] as $key => $value) {
		mysqli_query($link, "DELETE FROM vac_schedule WHERE id={$value}");
	}

	//Old values are in same format as new values (slight overhead)
	foreach ($_POST['vac_given_date'] as $key => $value) {
		if($value!="0000-00-00"&&$value!=""&&$value!='nil')
		{
			if($value!=$_POST['vac_given_date_hidden'][$key])			//if changed
			{
				$value = date('Y-m-d', strtotime($value));
				if(!mysqli_query($link, "UPDATE vac_schedule SET date_given='{$value}', given='Y' WHERE id={$_POST['vac_sched_id'][$key]}"))
					$err[] = "Unknown error";	//set own given_date first
				//Now begins setting of dates of those dependent on this
				//If some vaccination from vac_schedule has been deleted, this code should not cause problem, as no value from vac_schedule is being read to
				//calculate further values
				//TODO: optimize this thing, only select things that are needed
				//PROBLEM: multiple vaccines are dependent on same
				//SOLUTION added loop in beginning(assuming multiple dependence only on vaccine whose date has changed)
				//TODO: if multiple dependence down the line also, loop will have to be added inside loop
				$result = mysqli_query($link, "SELECT * FROM vaccines WHERE dependent ={$_POST['v_id'][$key]}");
				$original_date = $value;
				while($vaccine = mysqli_fetch_assoc($result))
				{//loop start
					//Need to save date(ie $value) at this point of time, as it will get replaced in following loop, but original value is needed
					$value = $original_date;
					while($vaccine)
					{
						$vaccine_schedule = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vac_schedule WHERE v_id ={$vaccine['id']} AND p_id={$_POST['p_id']}"));
						if($vaccine_schedule['given']=='Y')
							break;//if vaccine given, exit loop
						$date_temp = date("Y-m-d", strtotime("+".$vaccine['no_of_days']." days", strtotime($value)));
						if((date('D', strtotime($date_temp)))=='Sun')
						{
							$date_temp = date("Y-m-d", strtotime("+1 days", strtotime($date_temp)));
						}
						mysqli_query($link, "UPDATE vac_schedule SET date = '{$date_temp}' WHERE v_id ={$vaccine['id']} AND p_id={$_POST['p_id']}"); //set its date accordingly
						$value = $date_temp;//$value = $date_temp
						$vaccine = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vaccines WHERE dependent ={$vaccine['id']}"));//$vaccine = new vaccine with dep as $vaccine['id']
					}//loop end
				}
			}
		}
		else
		{
			if($value!=$_POST['vac_given_date_hidden'][$key])	//if value changed
			if(!mysqli_query($link, "UPDATE vac_schedule SET date_given='', given='N' WHERE id={$_POST['vac_sched_id'][$key]}"))
					$err[] = "Unknown error";	//set given date to null, set given to N
			}
		}
		foreach ($_POST['vac_date'] as $key => $value) {
		//if changed
			if($value!=$_POST['vac_date_hidden'][$key])
			{
				$value =date('Y-m-d', strtotime($value));
				if(!mysqli_query($link, "UPDATE vac_schedule SET date='{$value}', make={$_POST['make'][$key]} WHERE id={$_POST['vac_sched_id'][$key]}"))
					$err[] = "Unknown error";
			}
		}
		foreach ($_POST['make'] as $key => $value) {
		//if changed
			if($value!=$_POST['make_hidden'][$key])
			{
				if(!mysqli_query($link, "UPDATE vac_schedule SET make={$value} WHERE id={$_POST['vac_sched_id'][$key]}"))
					$err[] = "Unknown error";
			}
		}
		foreach ($_POST['given'] as $key => $value) {
			if($value=='Y')
			{
				if(!mysqli_query($link, "UPDATE vac_schedule SET given='Y' WHERE id={$_POST['vac_sched_id'][$key]}"))
					$err[] = "Unknown error";
			}
		}
		if(!$err)
		{
			echo "Changes saved successfully!";
		}
		else
		{
			implode($err_total, $err);
			echo $err_total;
		}
	} else if ($_POST['payment_date']) {
		$value = date('Y-m-d', strtotime($_POST['payment_date']));

		if(!mysqli_query($link,
			"INSERT INTO payment_due (p_id, date, amount, comment)VALUES ({$_GET['id']}, '{$value}', {$_POST['payment_amount']}, '{$_POST['payment_comment']}');"))
			$err[] = "Unknown error";
		if(!$err)
		{
			echo "Changes saved successfully!";
		}
		else
		{
			implode($err_total, $err);
			echo $err_total;
		}
	} else if ($_POST['visit_date']) {
		$value = date('Y-m-d', strtotime($_POST['visit_date']));
		$height = $_POST['height'];
		$weight = $_POST['weight'];
    if($_POST['invoice_id']) {
      $invoice_id = $_POST['invoice_id'];
    } else {
      $invoice_id = 0;
    }

		if($_POST['note'] != "" || $weight!=0 || $height!=0) {
			$q = "INSERT INTO notes (p_id, date, note, height, weight, invoice_id) VALUES ({$_GET['id']}, '{$value}', '{$_POST['note']}', '{$height}', {$weight}, {$invoice_id});";
			if(!mysqli_query($link, $q))
				$err[] = "Error adding visit";
			if(!$err)
			{
				echo "Note added  successfully!";
			}
			else
			{
				implode($err_total, $err);
				echo $err_total;
			}
		}
	} else if ($_POST['delete_visit'] || $_POST['note_id']) {

		if($_POST['note_id']) {
			foreach ($_POST['note_id'] as $key => $value) {
				$weight = $_POST['change_weight'][$key];
				$height = $_POST['change_height'][$key];
				$query = "UPDATE notes SET note = '".$_POST['change_note'][$key]."', weight='{$weight}', height='{$height}' WHERE id = ".$value;
				if(!mysqli_query($link, $query))
					$err[] = "Error while updating vists";
			}
		}

		foreach ($_POST['delete_visit'] as $key => $value) {
			$query = "DELETE from notes WHERE id = {$value};";
			if(!mysqli_query($link, $query))
				$err[] = "Error while deleting vists";
		}
		if(!$err)
		{
			echo "Updated/deleted visits successfully!";
		}
		else
		{
			implode($err_total, $err);
			echo $err_total;
		}
	} else if($_POST['sendsms']) {

		$message = $_POST['message'];
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
		$headers .= "From: ".$dr_name." <".$dr_email.">\r\n";
		if($_POST['phone'])
			mail($dr_email_sms, $_POST['phone'], $message, $headers);
		else
			echo "Problem sending SMS to phone number 1 <br>";
		if($_POST['phone2'])
			mail($dr_email_sms, $_POST['phone2'], $message, $headers);
		echo "SMS sent! <br>";
	} else if($_POST['save_dues']) {
		foreach ($_POST['due_paid_date'] as $key => $value) {
			if($value != 'nil') {
				$query = "UPDATE payment_due SET paid='Y', date_paid='".date('Y-m-d', strtotime($value))."' WHERE id = ".$_POST['due_paid_id'][$key];
				if(!mysqli_query($link, $query))
					$err[] = "Error while saving dues";
			}
		}
		if(!$err)
		{
			echo "Saved dues successfully!";
		}
		else
		{
			implode($err_total, $err);
			echo $err_total;
		}
	}
	if($_GET['id'])
	{
		$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM patients WHERE id = {$_GET['id']}"));
		$siblings_result = mysqli_query($link, "SELECT * FROM siblings WHERE p_id = {$_GET['id']}");
		if(!$patient)
		{
			echo "<h3>No patient with given ID found</h3>";
			include("footer.php");
			exit;
		}
		$temp_result = mysqli_query($link, "SELECT id FROM vaccines WHERE 1");
		$temp_nrows = mysqli_num_rows($temp_result);
		?>
		<?php
		$result = mysqli_query($link, "SELECT * FROM payment_due WHERE p_id = {$_GET['id']} AND paid = 'N' ORDER BY date;");
		$total = 0;
		while ($row = mysqli_fetch_assoc($result)) {
			$total += $row['amount'];
		}
		if ($total > 0) {
			?>
			<marquee id="flash_text" >
				<h3><font color="red"><strong>Due amount: Rs. <?php echo $total; ?>!</strong></font></h3>
			</marquee>
			<?php

		}

		?>
		<script type="text/javascript">
			// $(function() {
			//     $(".pagination").pagination({
			//         items: 100,
			//         itemsOnPage: 10,
			//         cssStyle: 'light-theme'
			//     });
			// });
			// function checkRed()
			{
				var array = document.getElementsByTagName("input");
				for(var ii = 0; ii < array.length; ii++)
				{
					if(array[ii].type == "checkbox")
					{
						if(array[ii].className == "focus_red")
						{
							array[ii].checked = true;
						}
					}
				}
			};

			$( function() {
				$( "#accordion" ).accordion({
					heightStyle: "content",
					collapsible: true,
					active:
					<?php if(strcmp($_SESSION['username'], 'mahima') == 0) {
							echo '3';
						}
						else {
							echo '0';
						}
					?>
				});
			} );

			jQuery(function($) {
			    // consider adding an id to your table,
			    // just incase a second table ever enters the picture..?
			    var items = $("#visits_section  > tr");
			    console.log(items);
			    var numItems = items.length;
			    var perPage = 6;

			    // only show the first 2 (or "first per_page") items initially
			    items.slice(perPage).hide();

			    // now setup your pagination
			    // you need that .pagination-page div before/after your table
			    $(".pagination-page").pagination({
			        items: numItems,
			        itemsOnPage: perPage,
			        cssStyle: "light-theme",
			        onPageClick: function(pageNumber) { // this is where the magic happens
			            // someone changed page, lets hide/show trs appropriately
			            var showFrom = perPage * (pageNumber - 1);
			            var showTo = showFrom + perPage;

			            items.hide() // first hide everything, then show for the new page
			                 .slice(showFrom, showTo).show();


			        }
			    });
			});


			<?php for ($i=0; $i < $temp_nrows; $i++) { ?>

				$(function() {
					$( <?php echo "\"#vac_given_date".$i."\""; ?> ).datepicker({
						changeMonth: true,
						changeYear: true,
						yearRange: "1970:2032",
						dateFormat:"d M yy"
					});
				});
				$(function() {
					$( <?php echo "\"#vac_date".$i."\""; ?> ).datepicker({
						changeMonth: true,
						changeYear: true,
						yearRange: "1970:2032",
						dateFormat:"d M yy"
					});
				});

				<?php }
				// LOL hardcoding 100, should never be more than 100
				for ($i=0; $i < 100; $i++) { ?>

				$(function() {
					$( <?php echo "\"#due_paid_date".$i."\""; ?> ).datepicker({
						changeMonth: true,
						changeYear: true,
						yearRange: "1970:2032",
						dateFormat:"d M yy"
					});
				});

				<?php
				}
				unset($temp_result);
				unset($temp_nrows);
				?>
				$(function() {
					$("#payment_date").datepicker({
						changeMonth: true,
						changeYear: true,
						yearRange: "1970:2032",
						dateFormat:"d M yy"
					});
				});
				$(function() {
					$("#visit_date").datepicker({
						changeMonth: true,
						changeYear: true,
						yearRange: "1970:2032",
						dateFormat:"d M yy"
					});
				});
			</script>
			<div class='to_hide_from_employee'
			<?php
				if(strcmp($_SESSION['username'],'mahima') != 0) {
					echo " style='display:none' ";
				}
			?> >
			<p>
				<button id="submitall" class="btn btn-default" type="submit">Submit all</button>
			</p>
			</div>
			<h4>Patient Information</h4>
			<div style="float:right"> <a href= <?php echo "editpatient.php?id={$patient['id']}" ?> ><strong> Edit patient </strong> </a></div>
			<div class='to_hide_from_employee'
			<?php
				if(strcmp($_SESSION['username'],'mahima') != 0) {
					echo " style='display:none' ";
				}
			?> >
			<p>
				<strong><a href=<?php echo "\""."pdf.php?id=".$patient['id']."\"" ?>>View schedule in print format</a> </strong>
			</p>
			<p>
				<strong><a href=<?php echo "\""."email.php?id=".$patient['id']."&normal=1\"" ?>>Send upcoming vaccination email</a> </strong>
			</p>
			<p>
				<strong><a href=<?php echo "\""."email.php?id=".$patient['id']."\"" ?>>Send vaccination history email (print format)</a> </strong>
			</p>
      <p>
				<strong><a href=<?php echo "\""."email-invoice-ui.php?id=".$patient['id']."\"" ?>>Send invoice email (pdf attachments)</a> </strong>
			</p>
			</div>
			<h4>
				<strong>ID: <?php echo $patient['id'] ?> </strong>
			</h4>

			<table style="margin: 0px 0px 0px 0px;border:none;">
				<tr>
					<td>
						<p>
							<strong>Name :</strong> <?php echo $patient['name']; ?>
						</p>
						<p>
							<strong>Date of Birth :</strong> <?php echo  date('d-F-Y', strtotime($patient['dob'])); ?>
						</p>
						<p>
							<strong>Age :</strong>
							<?php
								$from = new DateTime($patient['dob']);
								$to   = new DateTime('tomorrow');
								$age = $from->diff($to);
								echo $age->y." years ".$age->m." months ".$age->d." days";
							?>
						</p>
						<p>
							<strong>Sex :</strong> <?php echo $patient['sex']; ?>
						</p>
					</td>
					<td>
						<p>
							<strong>Father's name :</strong>
							<?php echo $patient['father_name'];
							if($patient['father_occ']) {
								echo ", ".$patient['father_occ'];
							}
							?>
						</p>
						<p>
							<strong>Mother's name :</strong>
							<?php echo $patient['mother_name'];
							if($patient['mother_occ']) {
								echo ", ".$patient['mother_occ'];
							}
							?>
						</p>
						<p>
							<strong>Phone:</strong> <?php echo $patient['phone']; ?>
						</p>
						<p>
							<strong>Active :</strong> <?php if($patient['active']==1) echo "<font color=green><strong>Yes</strong></font>"; else echo "<font color=red><strong>No</strong></font>"; ?>
						</p>
					</td>
				</tr>
			</table>

			<?php
			if(!$siblings_result)
				echo "<p><strong>Sibling: None</strong></p>";
			else
			{
				while($row = mysqli_fetch_assoc($siblings_result))
				{
					?>
					<p>
						<strong>Sibling :</strong>
						<?php
						echo "<a href=edit-sched.php?id=".$row['s_id'].">";

						$sibling_row = mysqli_fetch_assoc(mysqli_query($link, "SELECT name,dob,sex FROM patients WHERE id={$row['s_id']}"));
						echo $sibling_row['name'];
						echo "</a>";
						?>
					</p>
					<p>
						<strong>Sibling dob:</strong> <?php echo date('d-F-Y', strtotime($sibling_row['dob'])); ?>
					</p>
					<p>
						<strong>Sibling sex:</strong> <?php echo $sibling_row['sex']; ?>
					</p>
					<?php
				}
			}

			?>
			<div id="accordion">
				<h3> Advanced details </h3>
				<div>

					<p>
						<strong><em>Email :</em></strong> <?php echo $patient['email']; ?>
					</p>

					<p>
						<strong><em>Email 2:</em></strong> <?php echo $patient['email2']; ?>
					</p>

					<p>
						<strong>Birth Weight :</strong> <?php echo $patient['birth_weight']." grams"; ?>
					</p>

					<p>
						<strong>Birth Time :</strong> <?php echo $patient['born_at']; ?>
					</p>

					<p>
						<strong>Head Circumference :</strong> <?php echo $patient['head_circum']; ?> cm
					</p>

					<p>
						<strong>Length :</strong> <?php echo $patient['length']; ?> cm
					</p>

					<p>
						<strong>Mode of Delivery :</strong> <?php echo $patient['mode_of_delivery']; ?>
					</p>

					<p>
						<strong>Gestation :</strong> <?php echo $patient['gestation']; ?>
					</p>
					<p>
						<strong>Address :</strong> <?php echo $patient['address']; ?>
					</p>

					<p>
						<strong>Phone 2:</strong> <?php echo $patient['phone2']; ?>
					</p>

					<p>
						<strong>Obstetrician :</strong> <?php echo $patient['obstetrician']; ?>
					</p>
					<p>
						<strong>Place of Birth :</strong> <?php echo $patient['place_of_birth']; ?>
					</p>
					<p>
						<strong>Date of Registration :</strong> <?php echo date('d-F-Y', strtotime($patient['date_of_registration'])); ?>
					</p>

				</div>
				<h3 class='to_hide_from_employee'
				<?php
					if(strcmp($_SESSION['username'],'mahima') != 0) {
						echo " style='display:none' ";
					}
				?> > Due Payment </h3>
				<div class='to_hide_from_employee'
				<?php
					if(strcmp($_SESSION['username'],'mahima') != 0) {
						echo " style='display:none' ";
					}
				?> >
					<h4> Add due payment </h4>
					<form id="addduepayments" role="form" action="" method="post">
						<div class="form-group">
							<label for="payment_amount">Amount:</label>
							<input type="number" class="form-control" id="payment_amount" name="payment_amount">
						</div>
						<div class="form-group">
							<label for="payment_date">Date:</label>
							<!-- <input type="text" class="form-control" id="due_date" name="due_date"> -->
							<input type="text" name="payment_date" style="width:80px" id="payment_date" value=<?php echo "\"".date('j M Y')."\"";?>/>
						</div>
						<div class="form-group">
							<label for="payment_comment">Comment:</label>
							<input type="textbox" id="payment_comment" name="payment_comment" style="width:400px"> </label>
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					</form>
					<h4> Previous dues </h4>
					<form id="previousdues" role="form" action="" method="post">
						<table border="1">
							<tr>
								<th>S.No.</th>
								<th>Amount</th>
								<th>Date</th>
								<th>Comment</th>
								<th>Paid</th>
								<th>Date paid</th>
							</tr>
							<?php
							$result = mysqli_query($link, "SELECT * FROM payment_due WHERE p_id = {$_GET['id']} ORDER BY date;");
							$i=1;
							$total = 0;
							$count = 0;
							while($row = mysqli_fetch_assoc($result)) {
								$count++;
								if($row['paid'] == 'N')
									$total += $row['amount'];
								?>
								<tr>
									<td><?php echo "{$i}"; $i += 1; ?>  </td>
									<td><?php echo "{$row['amount']}";?> </td>
									<td><?php echo date('j M Y',strtotime($row['date']))?> </td>
									<td><?php echo $row['comment']?> </td>
									<td><?php echo $row['paid']?> </td>
									<td>
									<input type="text" name="due_paid_date[]" style="width:80px" <?php echo "id=\"due_paid_date".$count."\""; ?> value=<?php
									if($row['date_paid']=='0000-00-00'||$row['date_paid']=='')
										echo "\"nil\"";
									else
										echo "\"".date('j M Y',strtotime($row['date_paid']))."\"";?>/>
									<input type="hidden" name="due_paid_id[]" value=<?php echo $row['id']; ?>>
									</td>
								</tr>
								<?php } ?>

							</table>
							<p>
								<?php echo "Total due = Rs. {$total}"; ?>
							</p>
							<input type="hidden" name = "save_dues" value="1" />
							<button type="submit" class="btn btn-default">Save changes</button>
						</form>
					</div>
					<h3 class='to_hide_from_employee'
					<?php
						if(strcmp($_SESSION['username'],'mahima') != 0) {
							echo " style='display:none' ";
						}
					?> > Send SMS </h3>
					<div class='to_hide_from_employee'
					<?php
						if(strcmp($_SESSION['username'],'mahima') != 0) {
							echo " style='display:none' ";
						}
					?> >
						<form id="sendsms" role="form" method="post" action="">
							<div class="form-group">
								<label for="message">Message:</label>
								<br />
								<?php
									$message = "Address: C-14 Community Centre Naraina Vihar\n"
									."Phone: 9717585207, 25774759\n"
									."Timings: Mon to Sat 10.30am to 1.30pm, 6.00 to 8.30pm, \n"
									."Dr.Mahima";
									echo "<textarea name=\"message\" id = \"message\" rows=4 cols=80>".$message."</textarea>";
								?>
							</div>
							<input type="hidden" name="phone" value=<?php echo "\"".$patient['phone']."\""?>>
							<input type="hidden" name="phone2" value=<?php echo "\"".$patient['phone2']."\""?>>
							<input type="hidden" name="sendsms" value="1"/>
							<br />
							<button type="submit" class="btn btn-default">Send SMS</button>
						</form>
					</div>

					<h3 class='to_hide_from_employee'
					<?php
						if(strcmp($_SESSION['username'],'mahima') != 0) {
							echo " style='display:none' ";
						}
					?> > Visits </h3>
					<div class='to_hide_from_employee'
					<?php
						if(strcmp($_SESSION['username'],'mahima') != 0) {
							echo " style='display:none' ";
						}
					?> >
						<h4> Add visit note</h4>
						<form id="addvisitnote" role="form" action="" method="post">
							<div class="form-group">
								<label for="note">Note:</label>
								<br/>
								<textarea name="note" id="note" rows=3 cols=70></textarea>
							</div>
							<div class="form-group">
								<label for="visit_date">Date:</label>
								<!-- <input type="text" class="form-control" id="due_date" name="due_date"> -->
								<input type="text" name="visit_date" style="width:80px;margin-left: 20px" id="visit_date" value=<?php echo "\"".date('j M Y')."\"";?>/>
							</div>
							<div class="form-group">
								<label for="weight">Weight:</label>
								<input type="number" step="any" name="weight" style="width:80px;margin-left: 7px" id="weight" value='0'/>
							</div>
							<div class="form-group">
								<label for="height">Height:</label>
								<input type="number" step="any" name="height" style="width:80px;margin-left: 8px" id="height" value='0'/>
							</div>
							<br>
							<button type="submit" class="btn btn-default">Submit</button>
						</form>

						<h4> Previous visits </h4>
						<img src="" id="scanned_img" width=180 height=250>
						<button class="scan-pr">Scan Prescription</button>
						<br>

					  <p style="font-size: 30px;color:Tomato;">
							<strong>
								<a href=<?php echo "\"plotly.php?id={$_GET['id']}&type=height\"" ;?>>Click to see growth chart (height)</a>
							</strong>
						</p>
						<p style="font-size: 30px;color:Tomato;">
							<strong>
								<a href=<?php echo "\"plotly.php?id={$_GET['id']}&type=BMI\"" ;?>>Click to see growth chart (BMI)</a>
							</strong>
						</p>
						<div id="visitIDForPrescriptionScan" style="display: none;"></div>
						<form id="previousvisits" role="form" action="" method="post">
							<div class="pagination-page"></div>
							<table border="1" width="540px">

									<tr>
										<!-- <th>S.No.</th> -->
                    <th>ID</th>
										<th>Date</th>
										<th>Height       (cm)</th>
										<th>Weight      (kg)</th>
										<th>BMI</th>
										<th>Note</th>
                    <th>Invoice</th>
                    <th>Prescription</th>
										<th>Delete</th>
									</tr>
									<tbody id="visits_section">
									<?php
                  $patient_id = $_GET['id'];
									$q = "SELECT id, date, note, height, weight, invoice_id, image_url FROM notes WHERE p_id = {$_GET['id']} ORDER BY date DESC";
									$result = mysqli_query($link, $q);
									$i=1;
									$total = 0;
									while($row = mysqli_fetch_assoc($result)) {
										$total += $row['amount'];
										?>
										<tr>
											<!-- <td><?php echo "{$i}"; $i += 1; ?>  </td> -->
                      <td><?php echo $row['id']; ?></td>
											<td style="text-align:center;vertical-align: middle;"><?php echo date('j M Y',strtotime($row['date']))?> </td>
											<td> <input style="text-align:center;vertical-align: middle;width:40px" name="change_height[]" value = <?php  echo "'{$row['height']}'";?> >  </td>
											<td> <input style="text-align:center;vertical-align: middle;width:40px" name="change_weight[]" value = <?php  echo "'{$row['weight']}'";?> >  </td>
											<td>
											<?php
												$height = $row['height']/100.0;
												$weight = $row['weight'];
												$height_squared = $height*$height;
												$bmi = $weight/$height_squared;
												echo round($bmi,1);
											?>
											</td>
											<td><textarea name="change_note[]" cols="24" rows="2"><?php echo "{$row['note']}";?></textarea> </td>


											<td><?php
                      if($row['invoice_id']) {
                        echo "<a href=pdf-invoice.php?id=".$row['invoice_id'].">"."Show invoice ".$row['invoice_id']."</a>";
                      } else {
                        echo "<a href=create-invoice.php?id={$patient_id}&visit_id=".$row['id'].">"."Create invoice</a>";
                      }
                      ?></td>
											<td><?php

					  $visit_id = $row['id'];
					  $query = "SELECT * FROM prescriptions WHERE visit_id = '{$visit_id};'";
					  $prescriptionResult = mysqli_query($link, $query);
					  $i = 1;
					  while($prescription = mysqli_fetch_assoc($prescriptionResult)) {
					  	echo "<a href='{$prescription['url']}'>See presc {$i}</a><br>";
					  	$i++;
					  }

                      	?>


												<img class = 'spinner' src="images/ellipsis.svg" style="display: inline; display: none; width: 28px; height: 28px">
												<button class=<?php echo "\"scan-save {$row['id']}\""?> style="display: none">Save</button>

                      	<?php

                      ?></td>

											<input type="hidden" name="note_id[]" value=<?php echo "\"".$row['id']."\"" ?> />
											<td style="text-align:center; vertical-align: middle;"><?php
											if($row['id']) {
												echo "<input type=\"checkbox\" value=\"".$row['id']."\" name=\"delete_visit[]\">";
											} else {
												echo " ";
											}
											?>
											</td>
										</tr>
										<?php } ?>
										</tbody>

								</table>
								<button type="submit" class="btn btn-default">Save changes</button>
							</form>
						</div>
					</div>
					<div class='to_hide_from_employee'
					<?php
						if(strcmp($_SESSION['username'],'mahima') != 0) {
							echo " style='display:none' ";
						}
					?> >
					<h4>Schedule</h4>
					<form id="myform" action="" method="post" style="width:800px;background:none;border:none;margin:0px 0px 0px 0px;padding:0px 0px 0px 0px">
						<input type="hidden" name="p_id" value=<?php echo $patient['id'] ?> />
						<input type="submit" name="submit" value="Save Changes" />
						<br />
						<input type="button" name="CHECKRED" value="Check vaccines which cannot be given now" onClick="checkRed()" />
						<input type="button" name="uncheck" value="Uncheck All" onClick="uncheckAll()" />
						<table style="margin:0px 0px 0px 0px;">
							<tbody>
								<tr>
									<th>Given</th>
									<th>Vaccine</th>
									<th>Sched Date</th>
									<th>Given Date</th>
									<th>Lower Limit</th>
									<th>Upper Limit</th>
									<th>Product Name</th>
									<th>Remove</th>
								</tr>

								<?php
								$result = mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id = {$_GET['id']} ORDER BY date, v_id");
	//To show lower and upper limit, we add them to birth date
								$count = 0;
								while($row = mysqli_fetch_assoc($result))
								{
									$vac = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM vaccines WHERE id = {$row['v_id']}"));
									$temp_nofdays = "+".$vac['lower_limit']." days";
									$lower_limit = date('d-F-Y', strtotime($temp_nofdays, strtotime($patient['dob'])));
									if($vac['upper_limit'] > 36500)
										$upper_limit = "None";
									else
									{
										$temp_nofdays = "+".$vac['upper_limit']." days";
										$upper_limit = date('d-F-Y', strtotime($temp_nofdays, strtotime($patient['dob'])));
									}
									echo "<tr ";
									if ($row['given']=='Y')
									{
			echo "id=\"focus_green\"";	//green focus if vaccine has been given
			$color_id = "focus_green";
		}
		else if (strtotime("now") < strtotime($row['date']))
		{
			echo "id=\"focus_yellow\"";	//yellow focus if sched date is yet to come
			$color_id = "focus_yellow";
		}
		else if (($vac['upper_limit'] > 36500)||(strtotime("now") < strtotime("+".$vac['upper_limit']." days", strtotime($patient['dob']))))	//strtotime causes error if too large value is given
		{
			echo "id=\"focus_orange\"";	//orange focus if sched date has gone but vac can still be given
			$color_id = "focus_orange";
		}
		else
		{
			echo "id=\"focus_red\"";	//red focus if vaccine cant be given now
			$color_id = "focus_red";
		}
		echo " >";
		echo "<td>";
		?>
		<select name="given[]" style="">
			<option value='Y' <?php if($row['given']=='Y') echo "selected"; ?> >Y</option>
			<option value='N' <?php if($row['given']=='N') echo "selected"; ?> >N</option>
		</select>
		<?php
		echo "</td>";
		echo "<td>";
		echo $vac['name'];
		echo "</td>";

		?>

		<td>
			<input type="hidden" name="vac_date_hidden[]" value=<?php echo "\"".date('j M Y',strtotime($row['date']))."\"";?>/>
			<input type="text" name="vac_date[]" style="width:80px" <?php echo "id=\"vac_date".$count."\""; ?> value=<?php echo "\"".date('j M Y',strtotime($row['date']))."\"";?>/>
		</td>

		<td>
			<input type="hidden" name="vac_given_date_hidden[]" value=<?php
			if($row['date_given']=='0000-00-00'||$row['date_given']=='')
				echo "\"nil\"";
			else
				echo "\"".date('j M Y',strtotime($row['date_given']))."\"";?>/>
			<input type="text" name="vac_given_date[]" style="width:80px" <?php echo "id=\"vac_given_date".$count."\""; ?> value=<?php
			if($row['date_given']=='0000-00-00'||$row['date_given']=='')
				echo "\"nil\"";
			else
				echo "\"".date('j M Y',strtotime($row['date_given']))."\"";?>/>
		</td>
		<?php
		echo "<td>";
		echo $lower_limit;
		echo "</td>";
		echo "<td>";
		echo $upper_limit;
		echo "</td>";
		?>
		<td>
			<select name="make[]">
				<?php
				$result_make = mysqli_query($link, "SELECT vm.id as id, vm.name as name FROM vac_make vm, vac_to_make vtm WHERE vm.id = vtm.vm_id AND vtm.v_id = {$vac['id']} ORDER BY vm.id ASC");
				echo $query;
				while($vac_make = mysqli_fetch_assoc($result_make))
				{
					echo "<option value=".$vac_make['id'];
					if($row['make']==$vac_make['id'])
						echo " selected ";
					echo ">".$vac_make['name']."</option>\n";
				}
				?>
			</select>
			<input type="hidden" name="make_hidden[]" value=<?php echo "\"{$row['make']}\"";?> />
		</td>
		<?php
		echo "<td>";
		echo "<input type=\"checkbox\" class=\"{$color_id}\" value=\"".$row['id']."\" name=\"delete_vac[]\">";
		echo "<input type=\"hidden\" value=\"".$row['id']."\" name=\"vac_sched_id[]\">";
		echo "<input type=\"hidden\" value=\"".$row['v_id']."\" name=\"v_id[]\">";
		echo "</td>";
		echo "</tr>";
		$count++;
	}
	?>
</tbody>
</table>
<input type="submit" name="submit" value="Save Changes" />
<br />
<br />
<br />
<br />
<br />
</form>
</div>
<?php
}
else
{
	Redirect("search-patient.php");
	exit;
}
include('footer.php'); ?>
