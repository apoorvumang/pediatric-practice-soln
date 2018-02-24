<?php

class PDF extends FPDF
{

	private $medcert_info;

	function WordWrap(&$text, $maxwidth)
	{
	    $text = trim($text);
	    if ($text==='')
	        return 0;
	    $space = $this->GetStringWidth(' ');
	    $lines = explode("\n", $text);
	    $text = '';
	    $count = 0;

	    foreach ($lines as $line)
	    {
	        $words = preg_split('/ +/', $line);
	        $width = 0;

	        foreach ($words as $word)
	        {
	            $wordwidth = $this->GetStringWidth($word);
	            if ($wordwidth > $maxwidth)
	            {
	                // Word is too long, we cut it
	                for($i=0; $i<strlen($word); $i++)
	                {
	                    $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
	                    if($width + $wordwidth <= $maxwidth)
	                    {
	                        $width += $wordwidth;
	                        $text .= substr($word, $i, 1);
	                    }
	                    else
	                    {
	                        $width = $wordwidth;
	                        $text = rtrim($text)."\n".substr($word, $i, 1);
	                        $count++;
	                    }
	                }
	            }
	            elseif($width + $wordwidth <= $maxwidth)
	            {
	                $width += $wordwidth + $space;
	                $text .= $word.' ';
	            }
	            else
	            {
	                $width = $wordwidth + $space;
	                $text = rtrim($text)."\n".$word.' ';
	                $count++;
	            }
	        }
	        $text = rtrim($text)."\n";
	        $count++;
	    }
	    $text = rtrim($text);
	    return $count;
	}

	function setVars($medcert_info)
	{
		$this->medcert_info = $medcert_info;
	}

	function Header()
	{
		// Arial bold 15
		$this->Ln(45);
		$this->SetFont('Arial','B',20);
		// $medcert_info = $this->medcert_info;
		$title = "Medical Certificate";
		$w = $this->GetStringWidth($title)+6;
		// Move to the right
		$this->SetX((200-$w)/2);
		// Thickness of frame (1 mm)
		$this->SetLineWidth(1);
		// Title
		$this->Cell($w,9,$title);
		// Line break
		$this->Ln(20);

	}

	function body() {
		$medcert_info = $this->medcert_info;
		$name = $medcert_info['patient_name'];
		$sex = $medcert_info['patient_sex'];
		$parent_name = $medcert_info['parent_name'];
		$treatmentFrom = $medcert_info['treatmentFrom'];
		$diagnosis = $medcert_info['diagnosis'];
		$restFrom = $medcert_info['restFrom'];
		$restTo = $medcert_info['restTo'];
		$no_of_days = $medcert_info['no_of_days'];
		$text = "This is to certify that ".$name." ";
		if($sex == 'M') {
			$text .= "son of ";
		} else {
			$text .= "daughter of ";
		}
		$text .= $parent_name;
		$text .= " is under my treatment from {$treatmentFrom} for {$diagnosis}.\n";
		if($sex == 'M') {
			$text .= "He ";
		} else {
			$text .= "She ";
		}
		$text .= "is advised rest for the duration of {$no_of_days} days from {$restFrom} to {$restTo}.";
		$this->SetFont('Arial','',12);
		// $text=str_repeat('this is a word wrap test ',20);
		$nb= $this->WordWrap($text,170);
		// $this->Write(5,"This paragraph has $nb lines:\n\n");
		$this->Write(5,$text);
		// $this->Cell	(70,7,$text,'','','L');
	}


	// Page footer
	function Footer()
	{
		$this->Ln(31);
		$doctor_name = 'Dr. Mahima Anurag';
		$this->SetFont('Arial','B',12);
		$this->Cell(70,7,$doctor_name,'','','L');
		$this->Ln();
		$this->Image('mahima-sign.png',25,105,20);
	}
}


function createMedCertPDF($medcert_info, $link) {
	$pdf = new PDF();
	$pdf->SetMargins(20,20,10);
	$pdf->setVars($medcert_info);
	$pdf->AddPage();
	$pdf->body();
	return $pdf;
}
	//
	// // $pdf->Output("s");
	// $filecontents = $pdf->Output('asdasd', 'S');
	// echo $filecontents;
	//
	// // return $pdf;


?>
