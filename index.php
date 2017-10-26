<?php


//session_name('tzLogin');
//require 'connect.php';
include('header.php');
// Those two files can be included only if INCLUDE_CHECK is defined
//session_start();
//session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks


if($_SESSION['id'] && !isset($_COOKIE['tzRemember']) && !$_SESSION['rememberMe'])
{
	// If you are logged in, but you don't have the tzRemember cookie (browser restart)
	// and you have not checked the rememberMe checkbox:

	$_SESSION = array();
	session_destroy();
	
	// Destroy the session
}



if(isset($_GET['logout']))
{
	$_SESSION = array();
	session_destroy();
	
	Redirect("index.php");
	exit;
}

if($_POST['submit'])
{
	// Checking whether the Login form has been submitted
	
	$err = array();
	// Will hold our errors
	
	
	if(!$_POST['username'] || !$_POST['password'])
		$err[] = 'All the fields must be filled in!';
	
	if(!count($err))
	{
		// Escaping all input data
		$_POST['username'] = mysqli_real_escape_string($link_root, $_POST['username']);
		$_POST['password'] = mysqli_real_escape_string($link_root, $_POST['password']);
		$_POST['rememberMe'] = (int)$_POST['rememberMe'];
		
		$row = mysqli_fetch_assoc(mysqli_query($link_root, "SELECT * FROM doctors WHERE username='{$_POST['username']}' AND password='".md5($_POST['password'])."'"));

		if($row['username'])
		{
			// If everything is OK login
			
			$_SESSION['username'] = $row['username'];
			$_SESSION['name'] = $row['name'];
			$_SESSION['type'] = $row['type'];
			$_SESSION['rememberMe'] = $_POST['rememberMe'];
			
			// Store some data in the session
			
			setcookie('tzRemember',$_POST['rememberMe']);
			Redirect("index.php");
			exit;
		}
		else $err[]='Wrong username and/or password!';
	}
	
	if($err)
	{
		echo implode('<br />',$err);
	}
}

if($_SESSION['name']){ 

	echo "<h3>Welcome {$_SESSION['name']}!</h3><br />";
	?>
<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="js/jquery.fileupload.js"></script>
<script type="text/javascript" src="js/cloudinary-jquery-file-upload.js"></script>


<script type="text/javascript">
$(document).ready(function() {
  $("#scan-pr").click(function(e) {
    e.preventDefault()
    console.log("scanning!")
    $(".spinner").show()
    $.ajax({
      type: "GET",
      url: "http://localhost:8899/scan/test",
      success: function(data) {
        console.log("success got scan")
        // console.log(data)
        $("#scanned_img").attr("src", 'data:image/jpg;base64,'+data);
        $(".spinner").hide()
        $("#save-pr").show()
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
      $.ajax({
        type: 'POST',
        url: 'add-picture-prescription.php',
        data: {
          visit_id: 3138,
          url: data.result.url,
        },
        dataType: 'text',
        success: function(result) {
          console.log(result);
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
    $("#save-pr").click(function(e) {
      $(".spinner").show()
      console.log("save clicked")
      e.preventDefault()
      var data = $("#scanned_img").attr("src")
      $('.cloudinary_fileupload').fileupload('option', 'formData').file = data;
      $('.cloudinary_fileupload').fileupload('add', { files: [ data ]});
    })
  })
</script>



<form>
<input type="hidden" name="file" class="upload_field">
</form>

<a id="scan-pr" class="btn btn-outline-primary mr-2" href="#" role="button">Scan Prescription</a>
<img class = 'spinner' src="images/ellipsis.svg" style="display: inline; display: none; width: 28px; height: 28px">
<a id="save-pr" class="btn btn-outline-primary pull-right" href="#" style="display: none" role="button">Save</a>
<img src="" id="scanned_img" class="w-100">

<p><strong>Use the above links to navigate.</strong></p>
<?php
	include('footer.php');
	exit;
}

?>
			<form class="clearfix" action="" method="post">
					<h3>Doctor Login</h3>
					<p>
					<label class="grey" for="username">Username:</label><br />
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					</p>
					<p>
					<label class="grey" for="password">Password:</label><br />
					<input class="field" type="password" name="password" id="password" size="23" />
					</p>
					<p>
					
	            			<label><input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1" /> &nbsp;Remember me</label>
			            	</p>
			            	<p>
					<input type="submit" name="submit" value="Login" class="bt_login" />
					</p>
				
			</form>
<?php include('footer.php');?>
