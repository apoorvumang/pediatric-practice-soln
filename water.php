<!-- <html>
<body>

<canvas id="myCanvas" width="200" height="200"
style="border:1px solid #c3c3c3;">
Your browser does not support the canvas element.
</canvas>

<script>
var canvas = document.getElementById("myCanvas");
var ctx = canvas.getContext("2d");
ctx.fillStyle = "#000000";
ctx.fillRect(50,10,75,20);
var lvl1 = canvas.getContext("2d");
lvl1.fillStyle = "#000000";
lvl1.fillRect(30,30,115,20);
var lvl2 = canvas.getContext("2d");
lvl2.fillStyle = "#000000";
lvl2.fillRect(30,50,115,20);
var lvl3 = canvas.getContext("2d");
lvl3.fillStyle = "#000000";
lvl3.fillRect(30,70,115,20);
var lvl4 = canvas.getContext("2d");
lvl4.fillStyle = "#000000";
lvl4.fillRect(30,90,115,20);
var lvl5 = canvas.getContext("2d");
lvl5.fillStyle = "#000000";
lvl5.fillRect(30,110,115,20);
</script>

</body>
</html> -->

<!DOCTYPE html>
<?php
$xml = file_get_contents("http://waterpump-online.herokuapp.com/data");
$decoded = json_decode($xml, true);
// var_dump($decoded);
// echo $decoded['data'];
$waterLvlArr = $decoded['data'];
$colorArr = [];
$blue = "\"#3366ff\"";
$black = "\"#000000\"";
foreach ($waterLvlArr as $key => $value) {
  if($value == true)
    $colorArr[] = $blue;
  else {
    $colorArr[] = $black;
  }
}
?>

<html>
<body>

<canvas id="myCanvas" width="200" height="200"
style="border:1px solid #c3c3c3;">
Your browser does not support the canvas element.
</canvas>

<script>
var canvas = document.getElementById("myCanvas");
var ctx = canvas.getContext("2d");
ctx.fillStyle = "#000000";
ctx.fillRect(50,10,75,20);

var lvl1 = canvas.getContext("2d");
lvl1.fillStyle = <?php echo $colorArr[0];?>;
lvl1.fillRect(30,30,115,20);
var lvl2 = canvas.getContext("2d");
lvl2.fillStyle = <?php echo $colorArr[1];?>;
lvl2.fillRect(30,50,115,20);
var lvl3 = canvas.getContext("2d");
lvl3.fillStyle = <?php echo $colorArr[2];?>;
lvl3.fillRect(30,70,115,20);
var lvl4 = canvas.getContext("2d");
lvl4.fillStyle = <?php echo $colorArr[3];?>;
lvl4.fillRect(30,90,115,20);
var lvl5 = canvas.getContext("2d");
lvl5.fillStyle = <?php echo $colorArr[4];?>;
lvl5.fillRect(30,110,115,20);
</script>

</body>
</html>
