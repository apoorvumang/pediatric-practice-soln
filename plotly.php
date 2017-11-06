<?php
require 'connect.php';
include('header_db_link.php');

?>


<!DOCTYPE html>
 <head>
   <!-- <script src="https://cdn.plot.ly/plotly-latest.min.js"></script> -->
   <script src="js/plotly-latest.min.js"></script>
 </head>
 <body>

  <?php
  if(!$_GET['id']||!$_GET['type']) {
    echo "You can't access this page directly!";
    exit(0);
  }

  ?>
 <!-- Plotly chart will be drawn inside this div -->
 <div id="plotly-div"></div>



   <script>



  <?php

  $patientID = $_GET['id'];
  $patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM patients WHERE id = {$patientID};"));


  if($patient['sex'] == "M") {
    if($_GET['type']=="height")
      include('male2-20years_plotly.js');
    else
      include('male2-20yearsBMI_plotly.js');
  } else {
    if($_GET['type']=="height")
      include('female2-20years_plotly.js');
    else
      include('female2-20yearsBMI_plotly.js');
  }

  ?>



   trace6 = {

     <?php


     $query = "SELECT * FROM notes n WHERE n.p_id = {$patientID};";
     $result = mysqli_query($link, $query);
     $x = [];
     $y = [];
     $xList = "";
     $yList = "";
     $length = 0;
     while($visit = mysqli_fetch_assoc($result)) {
       if($visit['height']) {
         $yList = $yList."'{$visit['height']}',";
         $y[]= $visit['height'];
         $from = new DateTime($patient['dob']);
         $to   = new DateTime($visit['date']);
         $age = $from->diff($to);
         $displayAge = $age->y + ($age->m)/12.0 + ($age->d)/365.0;
         if($displayAge > 20) {
           $displayAge = 20;
         }
         $x[] = $displayAge;
         $xList = $xList."'{$displayAge}',";
       }
     }
     $yList = rtrim($yList, ",");
     $xList = rtrim($xList, ",");
     $name = $patient['name'];
     ?>
     x: [<?php echo $xList; ?>],
     y: [<?php echo $yList; ?>],
     marker: {
       sizemode: 'area',
       sizeref: 0.15
     },
     mode: 'markers',
     name: '<?php echo $name;?>',
     type: 'scatter',
     uid: 'c933a5',
     xsrc: 'apoorvumang:0:32688c',
     ysrc: 'apoorvumang:0:b5c7e4'
   };
   data = [trace1, trace2, trace3, trace4, trace5, trace6];
   layout = {
     autosize: true,
     dragmode: 'pan',
     hovermode: 'closest',
     showlegend: true,

     <?php
      $sex = $patient['sex'];
      $displaySex = "Males";
      if($sex == "F") {
        $displaySex = "Females";
      }
     ?>
     title: <?php echo "'Stature for age ({$displaySex} 2-20 years)'"; ?>,
     xaxis: {
       autorange: true,
       range: [1.9800317253, 20],
       title: 'Age (in years)',
       type: 'linear'
     },
     yaxis: {
       autorange: true,
       range: [74.7409238889, 194.517846111],
       title: 'Stature (cms)',
       type: 'linear'
     }
   };
   Plotly.plot('plotly-div', {
     data: data,
     layout: layout
   });



   </script>
 </body>
</html>
