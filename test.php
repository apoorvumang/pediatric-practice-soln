<!DOCTYPE html>
<html>
<head>
	<title>jQuery UI Example Page</title>
		<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>
    <script>
	$(function() {
		$( "#datepicker1" ).datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "1989:2022",
			altField: "#actualDate1",
			altFormat: "yy-mm-dd"
		});
	});
	$(function() {
		$( "#datepicker2" ).datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "1989:2022",
			altField: "#actualDate2",
			altFormat: "yy-mm-dd"
		});
	});
	</script>
</head>
<body style="font-size:62.5%;">
  
<input type="text" id="datepicker1" value="07/05/2012"/>
<input type="text" id="actualDate1"/>
<input type="text" id="datepicker2" value="07/05/2012"/>
<input type="text" id="actualDate2"/>

</body>
</html>