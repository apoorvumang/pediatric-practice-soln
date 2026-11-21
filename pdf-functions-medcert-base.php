<?php

abstract class MedicalCertificatePDF extends FPDF
{
	protected $medcert_info;
	protected $certificateTitle;

	function setVars($medcert_info)
	{
		$this->medcert_info = $medcert_info;
	}

	function Header()
	{
		if (($this->medcert_info['include_letterhead'] ?? '1') === '1') {
			$this->drawLetterhead();
		}

		// Keep the certificate in the same position for preprinted stationery.
		$this->SetY($this->tMargin + 65);
		$this->SetFont('Arial', '', 12);
		$this->Write(5, $this->medcert_info['date']);
		$this->Ln(10);
		$this->SetFont('Arial', 'B', 20);
		$titleWidth = $this->GetStringWidth($this->certificateTitle) + 6;
		$this->SetX((200 - $titleWidth) / 2);
		$this->Cell($titleWidth, 9, $this->certificateTitle);
		$this->Ln(20);
	}

	private function drawLetterhead()
	{
		$this->SetFont('Arial', 'B', 16);
		$this->Cell(100, 7, 'Dr. Mahima Anurag');
		$this->SetFont('Arial', 'B', 12);
		$this->Cell(0, 7, "Specialists' Clinic", 0, 1, 'R');

		$this->SetFont('Arial', '', 11);
		$this->Cell(100, 5, 'MBBS, MD(Pediatrics)');
		$this->Cell(0, 5, 'C-14 Community Centre', 0, 1, 'R');
		$this->Cell(100, 5, 'Consultant Child Specialist and Neonatologist');
		$this->Cell(0, 5, 'Naraina Vihar', 0, 1, 'R');
		$this->Cell(100, 5, 'Regn. No. DMC-3334');
		$this->Cell(0, 5, 'New Delhi - 110028', 0, 1, 'R');
		$this->Cell(0, 5, 'Tel. 9717585207', 0, 1, 'R');

		$lineY = $this->GetY() + 3;
		$this->Line($this->lMargin, $lineY, $this->w - $this->rMargin, $lineY);
	}
}
