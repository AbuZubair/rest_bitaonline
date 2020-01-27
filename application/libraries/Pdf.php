<?php 
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/config/tcpdf_config.php');

class Pdf extends TCPDF {
    //Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES.'logo_example.jpg';
		$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 20);
		// Title
		$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}

	function print_pdf($html, $title) { 

        $file = 'uploaded/'.$title.'.pdf';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        
        $pdf->SetAuthor('Hydromart');
        $pdf->SetTitle($title);

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

    // remove default header/footer
        // $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

    // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT,PDF_MARGIN_BOTTOM);

    // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
    // auto page break //
        $pdf->SetAutoPageBreak(TRUE, 30);

        //set page orientation
        
    // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('helvetica', '', 11);
        $pdf->ln();

        //kotak form
        $pdf->AddPage();
        // $pdf->setY(10);
        // $pdf->setXY(10,10);
        // $pdf->SetMargins(10, 10, 10, 10); 
        /* $pdf->Cell(150,42,'',1);*/

        $html_ = <<<EOF
        <!-- EXAMPLE OF CSS STYLE -->
        <style>  
            a { text-decoration: none; color: #0903E8; font-family: verdana; }  
            a:hover { color: #FA3C3C; }  
        </style>  
        
EOF;

        $html_ .= $html;

        // $result = $html;

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
            
        //ob_end_clean();
        // $pdf->Output($_SERVER['DOCUMENT_ROOT'] .'rest_ci/uploaded/'.$title.'.pdf', 'F'); 
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].PATH_PDF.'/'.'uploaded'.'/'.$title.'.pdf', 'F'); 
        
        return $file;

    }
    
}


?>