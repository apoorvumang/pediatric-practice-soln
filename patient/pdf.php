<?php
require('../fpdf/fpdf.php');
require('../connect.php');

class PDF extends FPDF
{

private $patient, $vac_sched;

function setVars($patient, $vac_sched)
{
	$this->patient = $patient;
	$this->vac_sched = $vac_sched;
}
// Colored table

private function printVacDates($dateArray, $idArray, $fill, $w)
{
	foreach ($idArray as $key => $id)
	{
		if(!isset($dateArray[$id]))
		{
			$this->Cell($w,8,'-','LRB',0,'C',$fill);
			continue;
		}
		if($dateArray[$id]!='0000-00-00')
		{
			$this->Cell($w,8,date('d M Y', strtotime($dateArray[$id])),'LRB',0,'C',$fill);
		}
		else
		{
			$this->Cell($w,8,'Given','LRB',0,'C',$fill);
		}
	}
	for ($i=0; $i < 9-count($idArray); $i++)
	{ 
		$this->Cell($w,8,'','LRB',0,'C',$fill);
	}
}

function FancyTable($header)
{
	// Colors, line width and bold font
	global $vac_sched;
	$this->SetFillColor(255,255,255);
	$this->SetTextColor(0);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.3);
	$this->SetFont('','B');
	// Header
	$w = array(52, 25,25,25,25,25,25,25,25,25);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],8,$header[$i],1,0,'C',true);
	$this->Ln();
	// Color and font restoration
	$this->SetFillColor(225,225,225);
	$this->SetTextColor(0);
	$this->SetFont('', '', 12);
	// Data
	$fill = false;
	$dateArray = array();
	while($record = mysqli_fetch_assoc($vac_sched))
	{
		$dateArray[$record['v_id']] = $record['date_given'];
	}
	$vacList = array('BCG', 'Hepatitis-B', 'DTwP/DTaP and OPV', 'Hib', 'IPV', 'Pneumococcal', 'Rotavirus', 'Measles', 'Influenza', 'Hepatitis-A', 'Chickenpox', 'MMR', 'Typhoid', 'Cholera', 'Meningitis', 'HPV');
	//Order of vaccine ids should be chronological according to dose
	//ie if you have 4 doses, then put the id of first does first and last dose last
	for ($i=0; $i < 16; $i++)
	{
		$this->Cell($w[0],8,$vacList[$i],'LRB',0,'C',$fill);
		switch ($i) {
			case 0:	//BCG
				$tempArr = array(1);
				break;
			case 1: //Hepatitis-B
				$tempArr = array(15,16,17);
				break;
			case 2:	//DTwP/DTaP and OPV
				$tempArr = array(8,9,10,11,12);
				break;
			case 3:	//Hib
				$tempArr = array(19,20,21,18);
				break;
			case 4: //IPV
				$tempArr = array(28,29,30,31);
				break;
			case 5:	//Pneumococcal
				$tempArr = array(39,40,41,42);
				break;
			case 6:	//Rotavirus
				$tempArr = array(43,44,63);
				break;
			case 7:	//Measles
				$tempArr = array(33);
				break;
			case 8: //Influenza
				$tempArr = array(22,23,24,25,26,27,65,66);
				break;
			case 9: //Hepatitis-A
				$tempArr = array(13,14,53);
				break;
			case 10:	//Chickenpox
				$tempArr = array(6,7);
				break;
			case 11:	//MMR
				$tempArr = array(37,38);
				break;
			case 12:	//Typhoid
				$tempArr = array(46,47,48,49,50,51,64);
				break;
			case 13:	//Cholera
				$tempArr = array(59,60);
				break;
			case 14:	//Meningitis
				$tempArr = array(34,35,36);
				break;
			case 15:	//HPV
				$tempArr = array(3,4,5);
				break;
			default:
				# code...
				break;
		}
		$this->printVacDates($dateArray, $tempArr, $fill, $w[1]);
		$this->Ln();
		
		// $fill = !$fill;
	}
	// Closing line
	$this->Cell(array_sum($w),0,'','T');
}

function Header()
{
	// Arial bold 15
	$this->SetFont('Arial','B',15);
	global $patient;
	$title = $patient['name'].'        DOB: '.date('d M Y', strtotime($patient['dob']));
	$w = $this->GetStringWidth($title)+6;
	// Move to the right
	$this->SetX((297-$w)/2);
	// Thickness of frame (1 mm)
	$this->SetLineWidth(1);
	// Title
	$this->Cell($w,9,$title);
	// Line break
	$this->Ln(20);
}

// Page footer
function Footer()
{
	// Position at 1.5 cm from bottom
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',8);
	// Page number
	$this->Cell(0,10,'drmahima.com',0,0,'C');
}
}


if(!isset($_GET['id']))
{
	echo '<h2>Access Denied</h2>';
	exit;
}

$vac_sched = mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id='{$_GET['id']}' AND given='Y'");
$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT name,dob FROM patients WHERE id='{$_GET['id']}'"));
$pdf = new PDF();
$pdf->setVars($patient, $vac_sched);
// Column headings
$header = array('Vaccine', 'Dose 1', 'Dose 2', 'Dose 3', 'Dose 4', 'Dose 5', 'Dose 6', 'Dose 7', 'Dose 8', 'Dose 9');
// Data loading
$pdf->SetFont('Arial','',14);
$pdf->AddPage('L');
$pdf->FancyTable($header);
$pdf->Output();
?>