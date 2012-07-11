<?php include('header.php'); 
if(isset($_POST['submit']))
{  //If the Register form has been submitted
	$err = array();

	if(!isset($_POST['name']) || !isset($_POST['no_of_days']) || !isset($_POST['lower_limit']) || !isset($_POST['upper_limit']) )
	{
		$err[] = 'Please fill all fields!';
	}

	if((!is_numeric($_POST['no_of_days']))||(!is_numeric($_POST['lower_limit']))||(!is_numeric($_POST['upper_limit'])))
	{
		$err[] = "Please enter valid no. of days!";
	}

	if(!count($err))
	{
		$_POST['name'] = mysql_real_escape_string($_POST['name']);
		
		if(isset($_POST['id']))
		{
			mysql_query("UPDATE vaccines SET name='{$_POST['name']}', 
				no_of_days={$_POST['no_of_days']}, 
				dependent={$_POST['dependent']}, 
				sex='{$_POST['sex']}', 
				lower_limit={$_POST['lower_limit']}, 
				upper_limit={$_POST['upper_limit']} WHERE id={$_POST['id']}");
		}
		else
		{
			mysql_query("INSERT INTO vaccines(name, no_of_days, dependent, sex, lower_limit, upper_limit)
					VALUES(
					'".$_POST['name']."',
					".$_POST['no_of_days'].",
					".$_POST['dependent'].",
					'".$_POST['sex']."',
					".$_POST['lower_limit'].",
					".$_POST['upper_limit'].")");
		}

		if(mysql_affected_rows($link)==1)
		{	
			$_SESSION['msg']['reg-success']='Vaccine successfully added!';
		}
		else $err[]='An unknown error has occured.';
	}
	
	if(count($err))
	{
		$_SESSION['msg']['reg-err'] = implode('<br />',$err);
	}	
}
						
if($_SESSION['msg']['reg-err'])
{
	echo '<div class="err">'.$_SESSION['msg']['reg-err'].'</div>';
	unset($_SESSION['msg']['reg-err']);
}

if($_SESSION['msg']['reg-success'])
{
	echo '<div class="success">'.$_SESSION['msg']['reg-success'].'</div>';
	unset($_SESSION['msg']['reg-success']);
}

?>

<?php include('footer.php'); ?>