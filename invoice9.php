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
        $this->CreateBody();
        $this->AddPage();
    }
    function Header()
    {
        $this->SetFont('Arial', '', 12);
        $this->Cell(100);
        $this->Cell(10, 1, 'EMPLOYEE ANNUAL LEAVE STATEMENT', 0, 0, 'L');
        $this->Cell(140);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 1, 'Page ' . $this->PageNo() . ' of {nb}', 0, 1);


        $this->Ln(10);        // Check if the page number is within the range of EMP CODEs
        $this->SetFont('Arial', '', 10);

        $this->SetFont('Arial', '', 10);
        $this->Cell(10);
        $this->Cell(20, 1, "ESTATES", 0, 1, 'L');


        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(18, 1, "Selection: ", 0, 0, 'L');
        $this->Cell(50, 1, 'Form  EDEN ESTATE To MONCHERI ESTATE And Banks: BANK TRANSFER To BANK TRANSFER', 0, 1, 'L');

        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(18, 1, "Printed On", 0, 0, 'L');
        $this->Cell(50, 1, date('d/m/Y') . ' at ' . date('H:i:s') . '- Confirmed', 0, 0, 'L');
        $this->Cell(20);
        $this->Cell(30, 1, "Pay Date:" . date('d/m/Y'), 0, 1, 'L');


        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(20, 1, "EDEN ESTATE:", 0, 1, 'L');




        $this->Ln(5);
        $this->Rect(5, 5, 287, 200);
    }


    function CreateBody()
    {
        $colWidths = [30, 50, 30, 30, 30, 30, 30, 30];
        $this->SetFillColor(192, 192, 192);
        $this->SetFont('Arial', 'B', 8);

        $this->Cell(10);
        $this->Cell($colWidths[0], 5, 'Emp Code', 'B', 0, 'L');
        $this->Cell($colWidths[1], 5, 'Name', 'B', 0, 'L');
        $this->Cell($colWidths[2], 5, 'Bal B/F', 'B', 0, 'L');
        $this->Cell($colWidths[3], 5, 'Accrued', 'B', 0, 'L');
        $this->Cell($colWidths[4], 5, 'Taken', 'B', 0, 'L');
        $this->Cell($colWidths[5], 5, 'Bal C/F', 'B', 0, 'L');
        $this->Cell($colWidths[6], 5, 'Entitlement', 'B', 0, 'L');
        $this->Cell($colWidths[7], 5, 'Leave Value', 'B', 1, 'L');


        $this->SetFont('Arial', '', 8);

        $users = $this->validate->getUserData();
        $invoiceTotal = 0;
        $grandTotal = 0;
        // Determine the maximum count for both invoice and quotation items
        $this->Ln(2);
        // Loop through the maximum count
        foreach ($users as $user) {
            $items = $this->validate->invoice_items($user['invoice_id']);

            // Print invoice item on the right
            foreach ($items as $item) {
                $invoiceTotal += $item['item_price'];
            }

            $grandTotal += $invoiceTotal;

            $this->Cell(10);
            $this->Cell($colWidths[0], 5, 'EE' . $user['invoice_id'], '', 0, 'L');
            $this->Cell($colWidths[1], 5, $user['client_name'], '', 0, 'L');
            $this->Cell($colWidths[2], 5, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[3], 5, number_format(2.5, 2), '', 0, 'L');
            $this->Cell($colWidths[4], 5, number_format(0, 2), '', 0, 'L');
            $this->Cell($colWidths[5], 5, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[6], 5, number_format(30, 2), '', 0, 'L');
            $this->Cell($colWidths[7], 5, number_format($invoiceTotal, 2), '', 1, 'L');
        }

        $this->Ln(2);
        $this->Cell(10);
        $this->Cell(80, 5, 'DEPARTMENT 1: TOTALS >>>', '', 0, 'L');
        $this->Cell($colWidths[2], 5, number_format($invoiceTotal, 2), 'T', 0, 'L');
        $this->Cell($colWidths[3], 5, number_format(2.5, 2), 'T', 0, 'L');
        $this->Cell($colWidths[4], 5, number_format(0, 2), 'T', 0, 'L');
        $this->Cell($colWidths[5], 5, number_format($invoiceTotal, 2), 'T', 0, 'L');
        $this->Cell($colWidths[6], 5, number_format(30, 2), '', 0, 'L');
        $this->Cell($colWidths[7], 5, number_format($invoiceTotal, 2), 'T', 1, 'L');
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
