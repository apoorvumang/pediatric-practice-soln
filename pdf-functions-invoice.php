<?php

class PDF extends FPDF
{

	function Header()
	{
		// Arial bold 15


	}

	function InvoiceDetails($info, $doctor) {

		if($doctor == 'Dr. Mahima') {
			$doctor_name = 'Dr. Mahima Anurag';
			$doctor_degree = "MBBS, MD(Pediatrics)";
			$doctor_work = "Consultant Child Specialist and Neonatologist";
			$doctor_regn = "DMC-3334";
		} else {
			$doctor_name = 'Dr. Anurag Saxena';
			$doctor_degree = "MBBS, MD(Medicine)";
			$doctor_work = "Consultant Physician";
			$doctor_regn = "DMC-3283";
		}
		$this->SetFont('Arial','B',16);
		$this->Cell(100,15, $doctor_name);
		$this->SetFont('Arial','B',12);
		$this->Cell(70,7,"Specialists' Clinic",'',1,'R');

		$this->SetFont('Arial','',12);
		$this->Cell(100,15, $doctor_degree);
		$this->Cell(70,5,'C-14 Community Centre','',1,'R');

		$this->Cell(100,15, $doctor_work.' ');
		$this->Cell(70,5,'Naraina Vihar','',1,'R');

		$this->Cell(100,15,'Regn. No. '.$doctor_regn);
		$this->Cell(70,5,'New Delhi - 110028','',1,'R');

		$this->Cell(100,15,'');
		$this->Cell(70,5,'Tel. 9717585207','',1,'R');


		$this->Ln(7);
		$headers = array("Invoice No.:", "Invoice date:", "Patient ID:", "Patient name:");
		for($i = 0; $i < 2; ++$i)
    {
				$this->Cell(30,5,$headers[$i*2]);
				$this->Cell(40,5,$info[$i*2]);
				$this->Cell(30,5,$headers[$i*2+1]);
				$this->Cell(40,5,$info[$i*2+1]);
        $this->Ln();
    }
	}

	function AmountDetails($amountInfo, $mode) {
		$this->Ln(5);
		$descriptions = explode("*",$amountInfo[0]);
		$amounts = explode("*",$amountInfo[1]);
		$discount = $amountInfo[2];
		$this->SetFont('Arial','B',12);
		$this->SetFillColor(200,200,200);
		$this->Cell(15,7,'S.No.','1','','C');
		$this->Cell(120,7,'Description','1','','C');
		$this->Cell(30,7,'Amount','1','','C');
		$this->Ln();
		$this->SetFont('Arial','',10);
		$total = 0;
		$fill = 0;
		$length = sizeof($descriptions);
		if($discount) {
			$length += 2;
		}
		$this->Image('mahima-sign.png',145,85 +$length*6.5,40);
		$serialNo = 1;
		for($i = 0; $i < sizeof($descriptions); $i++) {

			if($descriptions[$i]!="CONSULTATION" && $descriptions[$i] != "Medical Certificate" && $descriptions[$i] != "CONSULTATION AND INOCULATION" && $descriptions[$i] != "File Charges") {
				if(strpos($descriptions[$i], "xx") === False)
					$descriptions[$i].=" Vaccination";
				$descriptions[$i]= rtrim($descriptions[$i], 'xx');
				$descriptions[$i] = "- ".$descriptions[$i]; //bullet point for vaccinations
				$this->Cell(15,7,'','LR','','C',$fill);
			} else {
				// serial no. should only show for consultation, medcert etc, not for any vaccinations
				$this->Cell(15,7,$serialNo,'LR','','C',$fill);
				$serialNo++;
			}
			$this->Cell(120,7,"  ".$descriptions[$i],'LR','','L',$fill);
			//if consultation and inoculation then amount column should show blank (not 0)
			if($descriptions[$i] == "CONSULTATION AND INOCULATION") {
				$amounts[$i] = "";
			}
			$this->Cell(30,7,$amounts[$i]."  ",'LR','','R',$fill);
			$this->Ln();
			$total += intval($amounts[$i]);
			$fill = !$fill;
		}

		if($discount) {
			$this->Cell(15,7,'','LRT','','L', $fill);
			$this->Cell(120,7,"Total Before discount:",'LRT','','R', $fill);
			$this->Cell(30,7,$total."  ",'LRT','','R', $fill);
			$this->Ln();
			$this->Cell(15,7,'','LR','','L', $fill);
			$this->Cell(120,7,"Discount:",'LR','','R', $fill);
			$this->Cell(30,7,$discount."  ",'LR','','R', $fill);
			$this->Ln();
		}

		$grandTotal = $total - $discount;
		$this->Cell(15,7,'','LRBT','','L', $fill);
		$this->Cell(120,7,"Grand Total:",'LRBT','','R', $fill);
		$this->Cell(30,7,$grandTotal."  ",'LRBT','','R', $fill);

		$this->Ln(12);
		$stringGrandTotal = (string)$grandTotal;
		$this->SetFont('Arial','B',12);
		$this->Cell(70,5,"To Pay: Rs. ".$stringGrandTotal."  only",'','','L');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(70,5,"Mode of payment: ".$mode,'','','L');
		$this->Ln();
	}



	// Page footer
	function Footer()
	{
		$this->Ln(10);
		$this->SetFont('Arial','I',12);
		$this->Cell(165,5,"Authorised signatory",'','','R');
	}
}


function createInvoicePDF($id, $link) {
	$pdf = new PDF();
	$pdf->SetMargins(20,20,10);

	$pdf->AddPage();
	$invoiceInfo = mysqli_fetch_assoc(mysqli_query($link, "SELECT i.discount as discount, i.invoice_id as id, i.p_id as p_id, i.date as date, i.mode as mode, i.descriptions as descriptions, i.amounts as amounts, p.name as name, i.doctor as doctor FROM patients p, invoice i WHERE i.id = {$id} AND p.id = i.p_id"));
	$info = array($invoiceInfo["id"], date('d M Y', strtotime($invoiceInfo["date"])), $invoiceInfo["p_id"], $invoiceInfo["name"]);
	$doctor = $invoiceInfo['doctor'];
	$amountInfo = array($invoiceInfo["descriptions"], $invoiceInfo["amounts"], $invoiceInfo["discount"]);
	$mode = $invoiceInfo["mode"];
	$pdf->InvoiceDetails($info, $doctor);
	$pdf->AmountDetails($amountInfo, $mode);
	return $pdf;

}
	//
	// // $pdf->Output("s");
	// $filecontents = $pdf->Output('asdasd', 'S');
	// echo $filecontents;
	//
	// // return $pdf;


?>
