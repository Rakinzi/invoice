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
        parent::__construct('L');
        $this->validate = $validate;
        $this->rows = $rows;
    }

    function loopHeaderData()
    {
        $items = $this->rows;
        foreach ($items as $item) {
            $this->CreateHeader($item);
            $this->CreateBody($item);
            $this->AddPage();
        }
    }
    function CreateHeader($rows)
    {
        $this->SetFont('Arial', '', 12);
        $this->Cell(100);
        $this->Cell(10, 1, 'EMPLOYEE ANNUAL LEAVE STATEMENT', 0, 0, 'L');
        $this->Cell(150);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 1, $this->PageNo(), 0, 1);

        $this->Ln(5);
        $newRow = $rows;
        // Check if the page number is within the range of EMP CODEs

        $this->SetFont('Arial', '', 10);
        $this->Cell(10);
        $this->Cell(20, 1, "EDEN ESTATE", 0, 1, 'L');

        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(18, 1, "Printed On", 0, 0, 'L');
        $this->Cell(20, 1, date('d/m/Y') . ' at ' . date('H:i:s'), 0, 0, 'L');
        $this->Cell(20);
        $this->Cell(30, 1, "As At:" . date('d/m/Y') . ' ' . date('H:i:s'), 0, 1, 'L');


        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(18, 1, "Emp Code:", 0, 0, 'L');
        $this->Cell(10, 1, 'EE001', 0, 0, 'L');
        $this->Cell(20);
        $this->Cell(20, 1, "Name:", 0, 0, 'L');
        $this->Cell(30, 1, strtoupper($rows['client_name']), 0, 1, 'L');


        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(18, 1, "Leave Due", 0, 0, 'L');
        $this->Cell(10, 1, '195.220', 0, 0, 'L');
        $this->Cell(20);
        $this->Cell(20, 1, "Max:", 0, 0, 'L');
        $this->Cell(30, 1, number_format(990, 2), 0, 0, 'L');
        $this->Cell(20);
        $this->Cell(40, 1, "Special Days Taken:", 0, 0, 'L');
        $this->Cell(30, 1, number_format(0, 2), 0, 0, 'L');
        $this->Cell(40, 1, "Sick Days Taken:", 0, 0, 'L');
        $this->Cell(30, 1, number_format(0, 2), 0, 1, 'L');

        $this->Ln(5);
        $this->Rect(5, 5, 287, 200);
    }

    function CreateBody($rows)
    {
        $colWidths = [40, 30, 30, 40, 30, 30, 30, 20];
        $this->SetFillColor(192, 192, 192);
        $this->SetFont('Arial', 'UB', 10);

        $this->Cell(10);
        $this->Cell($colWidths[0], 10, 'Leave Type', '', 0, 'L');
        $this->Cell($colWidths[1], 10, 'From Date', '', 0, 'L');
        $this->Cell($colWidths[2], 10, 'To Date', '', 0, 'L');
        $this->Cell($colWidths[3], 10, 'Comment', '', 0, 'L');
        $this->Cell($colWidths[4], 10, 'Pay Date', '', 0, 'L');
        $this->Cell($colWidths[5], 10, 'Days Taken', '', 0, 'L');
        $this->Cell(10);
        $this->Cell($colWidths[6], 10, 'Days Accrued', '', 0, 'L');
        $this->Cell(10);
        $this->Cell($colWidths[7], 10, 'Balance', '', 1, 'L');



        // $items = $this->validate->invoice_items($rows['invoice_id']);
        // $items2 = $this->validate->invoice_items($rows['invoice_id']);

        // $invoiceItemCount = count($items);
        // $quotationItemCount = count($items2);

        // // Determine the maximum count for both invoice and quotation items
        // $maxItemCount = max($invoiceItemCount, $quotationItemCount);

        $this->SetFont('Arial', '', 10);
        $this->Cell(10);
        $this->Cell($colWidths[0], 10, 'Balance b/d', '', 0, 'L');
        $this->Cell($colWidths[1], 10, '31-08-2023', '', 0, 'L');
        $this->Cell($colWidths[2], 10, '03-10-2023', '', 0, 'L');
        $this->Cell($colWidths[3], 10, '', '', 0, 'L');
        $this->Cell($colWidths[4], 10, '29-09-2023', '', 0, 'L');
        $this->Cell($colWidths[5], 10, '', '', 0, 'L');
        $this->Cell(10);
        $this->Cell($colWidths[6], 10, number_format(192.720, 3), '', 0, 'L');
        $this->Cell(10);
        $this->Cell($colWidths[7], 10, number_format(192.720, 3), '', 1, 'L');

        $this->SetFont('Arial', '', 10);
        $this->Cell(10);
        $this->Cell($colWidths[0], 10, 'LEAVE ACCRUED', '', 0, 'L');
        $this->Cell($colWidths[1], 10, '29-09-2023', '', 0, 'L');
        $this->Cell($colWidths[2], 10, '29-09-2023', '', 0, 'L');
        $this->Cell($colWidths[3], 10, '', '', 0, 'L');
        $this->Cell($colWidths[4], 10, '29-09-2023', '', 0, 'L');
        $this->Cell($colWidths[5], 10, '', '', 0, 'L');
        $this->Cell(10);
        $this->Cell($colWidths[6], 10, number_format(2.500, 3), '', 0, 'L');
        $this->Cell(10);
        $this->Cell($colWidths[7], 10, number_format(195.220, 3), '', 1, 'L');

        $this->SetFont('Arial', '', 10);
        $this->Cell(10);
        $this->Cell($colWidths[0], 10, '', '', 0, 'L');
        $this->Cell($colWidths[1], 10, '', '', 0, 'L');
        $this->Cell($colWidths[2], 10, '', '', 0, 'L');
        $this->Cell($colWidths[3], 10, '', '', 0, 'L');
        $this->Cell($colWidths[4], 10, '', '', 0, 'L');
        $this->Cell($colWidths[5], 10, number_format(0, 2), 'T', 0, 'L');
        $this->Cell(10);
        $this->Cell($colWidths[6], 10, number_format(2.500, 3), 'T', 0, 'L');
        $this->Cell(10);
        $this->Cell($colWidths[7], 10, '', 'B', 1, 'L');

        $this->SetFont('Arial', '', 10);
        $this->Cell(10);
        $this->Cell($colWidths[0], 10, '', '', 0, 'L');
        $this->Cell($colWidths[1], 10, '', '', 0, 'L');
        $this->Cell($colWidths[2], 10, '', '', 0, 'L');
        $this->Cell($colWidths[3], 10, '', '', 0, 'L');
        $this->Cell($colWidths[4], 10, '', '', 0, 'L');
        $this->Cell($colWidths[5], 10, '', '', 0, 'L');
        $this->Cell(10);
        $this->Cell($colWidths[6], 10, 'Balance c/d', '', 0, 'L');
        $this->Cell(10);
        $this->Cell($colWidths[7], 10, number_format(195.220, 3), 'B', 0, 'L');
    }



    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Produced By ', 0, 0, 'L');
        $this->Cell(0, 10, 'Database ', 0, 0, 'R');
    }
}

$validate = new Validate();
$rows = $validate->getUserData();
$pdf = new PDF($validate, $rows);
$pdf->AddPage();
$pdf->loopHeaderData();
$pdf->AliasNbPages();
$pdf->Output();
