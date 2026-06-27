<?php

class PDF extends FPDF
{

	private $patient, $vac_sched, $pending_vacs;

	function setVars($patient, $vac_sched, $pending_vacs = array())
	{
		$this->patient = $patient;
		$this->vac_sched = $vac_sched;
		$this->pending_vacs = $pending_vacs;
	}
	// Colored table

	private function printVacDates($dateArray, $idArray, $fill, $w)
	{
		$countNotSetDates = 0;
		foreach ($idArray as $key => $id)
		{
			if(!isset($dateArray[$id]))
			{
				$countNotSetDates++;
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
		for ($i=0; $i < 9-count($idArray)+$countNotSetDates; $i++)
		{
			$this->Cell($w,8,'','LRB',0,'C',$fill);
		}
	}

	function FancyTable($header)
	{
		// Colors, line width and bold font
		$vac_sched = $this->vac_sched;
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		// Header
		$w = array(52, 22,22,22,22,22,22,22,22,22);
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],8,$header[$i],1,0,'C',true);
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(225,225,225);
		$this->SetTextColor(0);
		$this->SetFont('', '', 11);
		// Data
		$fill = false;
		$dateArray = array();
		while($record = mysqli_fetch_assoc($vac_sched))
		{
			$dateArray[$record['v_id']] = $record['date_given'];
		}
		$vacList = array('BCG', 'Hepatitis-B', 'DTwP/DTaP and OPV', 'Hib', 'IPV', 'Pneumococcal', 'Rotavirus', 'Measles', 'Influenza', 'Hepatitis-A', 'Chickenpox', 'MMR', 'Typhoid', 'Cholera', 'Meningitis', 'HPV','Tdap/Td/TT');
		//Order of vaccine ids should be chronological according to dose
		//ie if you have 4 doses, then put the id of first does first and last dose last
		for ($i=0; $i < count($vacList); $i++)
		{
			$this->SetFont('', 'B', 12);
			$this->Cell($w[0],8,$vacList[$i],'LRB',0,'C',$fill);
			$this->SetFont('', '', 10);
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
					$tempArr = array(22,23,24,25,26,27,65,66,68,69);
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
					$tempArr = array(46,47,48,49,50,51,64,73,74);
					break;
				case 13:	//Cholera
					$tempArr = array(59,60);
					break;
				case 14:	//Meningitis
					$tempArr = array(34,35,36,70);
					break;
				case 15:	//HPV
					$tempArr = array(3,4,5);
					break;
				case 16:	//Tdap/Td/TT
					$tempArr = array(45,52,55,56);
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

	function DueScheduleTable()
	{
		$pending = $this->pending_vacs;
		if (empty($pending)) return;

		$this->SetFont('Arial', 'B', 14);
		$this->Cell(0, 10, 'Upcoming / Pending Vaccinations', 0, 1, 'L');
		$this->SetFont('', 'B', 11);

		$w = array(15, 80, 45, 40);
		$headers = array('S.No.', 'Vaccine', 'Scheduled Date', 'Status');
		for ($i = 0; $i < count($headers); $i++) {
			$this->Cell($w[$i], 8, $headers[$i], 1, 0, 'C', false);
		}
		$this->Ln();

		$this->SetFont('', '', 10);
		$today = date('Y-m-d');
		$sno = 1;
		foreach ($pending as $row) {
			$sched_date = $row['date'];
			$date_display = ($sched_date != '0000-00-00') ? date('d M Y', strtotime($sched_date)) : 'TBD';

			$status = 'Scheduled';
			if ($sched_date != '0000-00-00' && $sched_date < $today) {
				$status = 'Overdue';
			} elseif ($sched_date != '0000-00-00' && strtotime($sched_date) <= strtotime('+30 days')) {
				$status = 'Due Soon';
			}

			$this->Cell($w[0], 7, $sno++, 1, 0, 'C');
			$this->Cell($w[1], 7, $row['vaccine_name'], 1, 0, 'L');
			$this->Cell($w[2], 7, $date_display, 1, 0, 'C');

			if ($status == 'Overdue') {
				$this->SetTextColor(180, 0, 0);
				$this->SetFont('', 'B', 10);
			} elseif ($status == 'Due Soon') {
				$this->SetTextColor(180, 130, 0);
				$this->SetFont('', 'B', 10);
			}
			$this->Cell($w[3], 7, $status, 1, 0, 'C');
			$this->SetTextColor(0);
			$this->SetFont('', '', 10);
			$this->Ln();
		}

		$this->Ln(5);

		$this->SetFont('Arial', 'B', 14);
		$this->Cell(0, 10, 'Completed Vaccinations', 0, 1, 'L');
		$this->SetFont('Arial', '', 14);
	}

	function Header()
	{
		// Arial bold 15
		$this->SetFont('Arial','B',15);
		$patient = $this->patient;
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
		// Position at 3.0 cm from bottom
		$this->SetY(-30);

		$doctor_name = 'Dr. Mahima Anurag';
		$doctor_degree = "MBBS, MD(Pediatrics)";
		$doctor_work = "Consultant Child Specialist and Neonatologist";
		$doctor_regn = "DMC-3334";
		$this->SetFont('Arial','B',16);
		$this->Cell(70,7,$doctor_name,'','','L');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(70,5,$doctor_degree,'','','L');
		$this->Ln();
		$this->Cell(70,5,$doctor_work,'','','L');
		$this->Ln();
		$this->Cell(70,5,$doctor_regn,'','','L');
		$signY = $this->GetY() - 20;
		$this->Image('mahima-sign.png',100,$signY,30);
	}
}

function createPrintSchedulePDF($id, $link, $include_pending = false) {
	$vac_sched = mysqli_query($link, "SELECT * FROM vac_schedule WHERE p_id='$id' AND given='Y'");
	$patient = mysqli_fetch_assoc(mysqli_query($link, "SELECT name,dob FROM patients WHERE id='$id'"));

	$pending_vacs = array();
	if ($include_pending) {
		$pending_q = mysqli_query($link,
			"SELECT vs.date, v.name as vaccine_name
			 FROM vac_schedule vs
			 JOIN vaccines v ON vs.v_id = v.id
			 WHERE vs.p_id='$id' AND vs.given != 'Y'
			 ORDER BY vs.date ASC");
		while ($row = mysqli_fetch_assoc($pending_q)) {
			$pending_vacs[] = $row;
		}
	}

	$pdf = new PDF();
	$pdf->SetMargins(20,10);
	$pdf->setVars($patient, $vac_sched, $pending_vacs);

	if ($include_pending && !empty($pending_vacs)) {
		$pdf->SetFont('Arial','',14);
		$pdf->AddPage('P');
		$pdf->DueScheduleTable();
	}

	$header = array('Vaccine', 'Dose 1', 'Dose 2', 'Dose 3', 'Dose 4', 'Dose 5', 'Dose 6', 'Dose 7', 'Dose 8', 'Dose 9');
	$pdf->SetFont('Arial','',14);
	$pdf->AddPage('L');
	$pdf->FancyTable($header);
	return $pdf;
}

?>
