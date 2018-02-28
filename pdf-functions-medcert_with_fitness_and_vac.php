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
		$this->Ln(65);
		$this->SetFont('Arial','B',20);
		// $medcert_info = $this->medcert_info;
		$title = "Medical Fitness Certificate";
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
		$formatted_patient_name = $medcert_info['formatted_patient_name'];
    $first_name = $medcert_info['first_name'];
    $patient_sex = $medcert_info['patient_sex'];
    $formatted_dob = $medcert_info['formatted_dob'];
    $formatted_age = $medcert_info['formatted_age'];
    $pronoun = $medcert_info['$pronoun'];
		$sex = $medcert_info['patient_sex'];
		$parent_name = $medcert_info['parent_name'];
    $pronoun = "She";
    if($sex == 'M') {
      $pronoun = "He";
    }
		$text = "This is to certify that ".$formatted_patient_name." ";
    $text .= "DOB ".$formatted_dob." ";
		if($sex == 'M') {
			$text .= "son of ";
		} else {
			$text .= "daughter of ";
		}
		$text .= $parent_name;
		$text .= " is a healthy child of {$formatted_age}.\n";

		$lowerCasePronoun = "she";
		if($pronoun == "He") {
			$lowerCasePronoun = "he";
		}
		$text3 = "Please find attached the list of immunizations {$lowerCasePronoun} has received till date.\n";

    $text2 = "\n{$pronoun} does not suffer from any chronic or communicable disease.\n";
    $text2 .= "{$first_name} is a physically active and mentally alert child fit to participate in all school activites.\n";
		$this->SetFont('Arial','',12);

		$nb= $this->WordWrap($text,170);
		$this->Write(5,$text);
    $this->Ln(10);

    $nb= $this->WordWrap($text2,170);
		$this->Write(5,$text2);
		$this->Ln(10);

		$nb= $this->WordWrap($text3,170);
		$this->Write(5,$text3);
	}


	// Page footer
	function Footer()
	{
		$this->Ln(31);
		$doctor_name = 'Dr. Mahima Anurag';
		$this->SetFont('Arial','B',12);
		$this->Cell(70,7,$doctor_name,'','','L');
		$this->Ln();
		// $this->Image('mahima-sign.png',25,110,20);
	}
}


function createMedCertWithFitnessAndVacPDF($medcert_info, $link) {
	$pdf = new PDF();
	$pdf->SetMargins(28,20,10);
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
