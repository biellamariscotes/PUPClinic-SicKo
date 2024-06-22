<?php
require_once('includes/session-nurse.php');
require_once('includes/connect.php');
require_once('../vendors/tcpdf/tcpdf.php');

$date = date('F j, Y');
$full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
$diagnosis = isset($_POST['diagnosis']) ? $_POST['diagnosis'] : '';

// Create new PDF document
$pdf = new TCPDF();

// Set document information
$pdf->SetCreator('SicKo');
$pdf->SetAuthor('Polytechnic University of the Philippines');
$pdf->SetTitle('Medical Certificate');
$pdf->SetSubject('Medical Certificate for ' . $full_name);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Content
$html = '
    <div style="text-align: center;">
        <div style="font-weight: bold;">Republic of the Philippines</div>
        <div style="font-weight: bold; margin-bottom: 10px;">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</div>
        <div style="font-weight: bold;">MEDICAL CLINIC</div>
    </div>
    <br>
    <div style="text-align: right;">
        Date: ' . $date . '
    </div>
    <br>
    <div style="text-align: left;">
        To Whom It May Concern:
    </div>
    <br>
    <div style="margin-left: 40px; margin-bottom: 10px;">
        I am writing to endorse ' . $full_name . ' a student at Polytechnic University
    </div>
    <div style="margin-left: 40px; margin-bottom: 10px;">
        of the Philippines - Santa Rosa Branch for urgent medical evaluation and treatment at 
    </div>
    <div style="margin-left: 40px; margin-bottom: 10px;">
        ________________________________. ' . $full_name . ' suffers from ' . $diagnosis . ' , which
    </div>
    <div style="margin-left: 40px; margin-bottom: 10px;">
        significantly impacts their daily life and academic performance. Their condition necessitates
    </div>
    <div style="margin-left: 40px; margin-bottom: 10px;">
        specialized care that we believe your hospital can provide. We kindly request you to prioritize
    </div>
    <div style="margin-left: 40px; margin-bottom: 10px;">
        ' . $full_name . ' case and provide the necessary medical attention.
    </div>
    <br>
    <div style="text-align: right;">
        _______________________<br>
            School Nurse
    </div>
';

// Write HTML content into PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('endorsement_' . $full_name . '.pdf', 'D');
?>
