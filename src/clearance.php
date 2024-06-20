<?php
require_once('includes/session-nurse.php');
require_once('includes/connect.php');
require_once('../vendors/tcpdf/tcpdf.php');

$date = date('F j, Y');
$full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';

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
        This is to certify that ' . $full_name . '
    </div>
    <div style="margin-left: 40px; margin-bottom: 10px;">
        has been examined by the undersigned and found to be physically fit.
    </div>
    <div style="margin-left: 40px; margin-bottom: 10px;">
        This certification is issued upon request for a letter of excuse.
    </div>
    <br>
    <div style="text-align: right;">
        _______________________<br>
        Authorized Signatory
    </div>
';

// Write HTML content into PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('certificate_' . $full_name . '.pdf', 'D');
?>
