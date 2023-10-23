<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';
require 'config.php';

use Fpdf\Fpdf;

class PDF extends FPDF
{
    private $currentRowData;

    private $validate;
    private $rows;

    public function __construct($validate, $rows)
    {
        parent::__construct('P');
        $this->validate = $validate;
        $this->rows = $rows;
    }

    function loopHeaderData()
    {
        $this->CreateHeader();
        $this->CreateBody();
    }
    function CreateHeader()
    {
        $this->Image('./Zimra.jpeg', 165, 10, 20, 25);
        $this->SetFillColor(0, 0, 0);
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(65);
        $this->Cell(30, 10, 'ZMIBABWE REVENUE AUTHORITY', 0, 1, 'L');
        $this->Ln(5);
        // Check if the page number is within the range of EMP CODEs
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(72);
        $this->Cell(30, 1, 'Return For The Remittance Of P.A.Y.E', 0, 0, 'L');
        $this->Cell(65);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 1, 'Form P2', 0, 1, 'L');
        $this->Ln(5);

        $this->SetFont('Arial', 'IU', 7);
        $this->Cell(155);
        $this->Cell(30, 1, "Attachments", 0, 1, 'L');

        $this->SetFont('Arial', '', 7);
        $this->Ln(5);
        $this->Cell(8);
        $this->Cell(30, 1, "Region:   01", 0, 0, 'L');
        $this->Cell(70);
        $this->Cell(30, 1, "Station:   HARARE", 0, 1, 'L');

        $this->Ln(5);
        $this->Rect(5, 5, 200, 287);
    }

    function CreateBody()
    {
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(8);

        $this->Cell(30, 5, "PART A", 0, 1, 'L');
        $colWidths = [80, 90];
        $this->SetFillColor(192, 192, 192);
        $this->SetFont('Arial', '', 7);


        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '1. Employer\'s name', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, 'EDEN ESTATE', 'TRB', 1, 'L', 0);

        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '2. Trade name', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, 'EDEN ESTATE', 'TRB', 1, 'L', 0);


        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '3. Business Partner Number', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '117822018', 'TRB', 1, 'L', 0);


        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '4. PAYE Number', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '0', 'TRB', 1, 'L', 0);


        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '5. TIN', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '', 'TRB', 1, 'L', 0);


        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '6. Physical Address', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '', 'TRB', 1, 'L', 0);

        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '', 'TRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, ', HARARE', 'TRB', 1, 'L', 0);

        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '7. Postal Address', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '129 BORROWDALE RD', 'TRB', 1, 'L', 0);

        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '', 'TRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, 'GUNHILL, HARARE', 'TRB', 1, 'L', 0);

        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '8. Tax Period', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '09-23', 'TRB', 1, 'L', 0);

        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '9. Due Date', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '10/10/2023', 'TRB', 1, 'L', 0);

        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '10. Email Address', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '', 'TRB', 1, 'L', 0);

        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '11. Cell Number', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '', 'TRB', 1, 'L', 0);

        $this->Ln(5);


        $this->SetFont('Arial', 'B', 7);
        $this->Cell(8);
        $this->Cell(30, 5, "PART B", 0, 1, 'L');
        $colWidths = [80, 45];
        $this->SetFillColor(192, 192, 192);

        $this->Cell(88);
        $this->Cell(30, 5, "Total", 0, 1, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '1. Total Remuneration', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '17,126,658.59', 'TRB', 1, 'L', 0);

        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '2. Number of Employees (Inc contract employees)', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '23', 'TRB', 1, 'L', 0);


        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '3. Gross PAYE', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '1,160,671.16', 'TRB', 1, 'L', 0);


        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '4. AIDS Levy @3%', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '34,820.13', 'TRB', 1, 'L', 0);


        $this->Cell(8);
        $this->Cell($colWidths[0], 5, '5. Total Tax Paid', 'TLRB', 0, 'L', 0);
        $this->Cell($colWidths[1], 5, '1,195,491.29', 'TRB', 1, 'L', 0);

        $this->Ln(5);
        $this->Cell(8);
        $this->Cell(45, 5, 'I declare that the information I have given on this form is correct and complete.', 0, 1, 'L');

        $this->Ln(10);
        $this->Cell(8);
        $this->Cell(45, 1, '', 'T', 0, 'L');
        $this->Cell(20);
        $this->Cell(45, 1, '', 'T', 0, 'L');
        $this->Cell(20);
        $this->Cell(45, 1, '', 'T', 1, 'L');

        $this->Cell(8);
        $this->Cell(45, 5, 'Name', '', 0, 'L');
        $this->Cell(20);
        $this->Cell(45, 5, 'Designation', '', 0, 'L');
        $this->Cell(20);
        $this->Cell(45, 5, 'Signature', '', 1, 'L');

        $this->Ln(15);
        $this->Cell(15);
        $this->Cell(25, 5, 'Date of Submission :', '', 0, 'L');
        $this->Cell(10);
        $this->Cell(5, 5, '', 1, 0, 'L');
        $this->Cell(5, 5, '', 1, 0, 'L');
        $this->Cell(5);
        $this->Cell(5, 5, '', 1, 0, 'L');
        $this->Cell(5, 5, '', 1, 0, 'L');
        $this->Cell(5);
        $this->Cell(5, 5, '', 1, 0, 'L');
        $this->Cell(5, 5, '', 1, 0, 'L');
        $this->Cell(5, 5, '', 1, 0, 'L');
        $this->Cell(5, 5, '', 1, 1, 'L');

        $this->SetFont('Arial', 'B', 7);
        $this->Ln(15);
        $this->Cell(8);
        $this->Cell(45, 5, 'Please note that :', 0, 1, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(8);
        $this->Cell(45, 5, '1. Interest is charged at 10% per annum for late remittance of PAYE.', 0, 1, 'L');
        $this->Cell(8);
        $this->Cell(45, 5, '2. Late payments may attract penalties.', 0, 1, 'L');


        $this->Image('./Stamp.jpg', 145, 190, 55, 40);
    }
}

$validate = new Validate();
$rows = $validate->getUserData();
$pdf = new PDF($validate, $rows);
$pdf->AddPage();
$pdf->loopHeaderData();
$pdf->AliasNbPages();
$pdf->Output('', 'ZIMRA');
