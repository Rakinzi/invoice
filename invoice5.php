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


        $this->Ln(10);

        $this->Cell(10);
        $this->Cell(20, 1, "EDEN ESTATE:", 0, 0, 'L');
        $this->Cell(15);
        $this->Cell(20, 1, '', 0, 0, 'L');
        $this->Cell(65);
        $this->Cell(20, 1, "BANK TRANSFER", 0, 0, 'L');
        $this->Cell(5);
        $this->Cell(30, 1, '', 0, 0, 'L');
        $this->Cell(20);
        $this->Cell(20, 1, "", 0, 0, 'L');
        $this->Cell(5);
        $this->Cell(20, 1, '', 0, 0, 'L');



        $this->Ln(5);
        $this->Rect(5, 5, 287, 200);
    }

    function CreateBody()
    {
        $colWidths = [60, 50, 20, 60, 20, 40, 20];
        $this->SetFillColor(192, 192, 192);
        $this->SetFont('Arial', 'B', 10);

        $this->Cell(10);
        $this->Cell($colWidths[0], 5, 'Name', 'B', 0, 'L');
        $this->Cell($colWidths[1], 5, 'Bank', 'B', 0, 'L');
        $this->Cell($colWidths[2], 5, 'Sort Code', 'B', 0, 'L');
        $this->Cell($colWidths[3], 5, 'Bank A/C', 'B', 0, 'L');
        $this->Cell($colWidths[4], 5, 'Emp Code', 'B', 0, 'L');
        $this->Cell($colWidths[5], 5, 'Net Pay', 'B', 0, 'L');
        $this->Cell($colWidths[6], 5, 'Currency', 'B', 1, 'L');

        $this->SetFont('Arial', '', 10);

        $users = $this->validate->getUserData();
        $invoiceTotal = 0;
        $grandTotal = 0;
        // Determine the maximum count for both invoice and quotation items

        // Loop through the maximum count
        foreach ($users as $user) {
            $items = $this->validate->invoice_items($user['invoice_id']);

            // Print invoice item on the right
            foreach ($items as $item) {
                $invoiceTotal += $item['item_price'];
            }

            $grandTotal += $invoiceTotal;

            $this->Cell(10);
            $this->Cell($colWidths[0], 10, $user['client_name'], '', 0, 'L');
            $this->Cell($colWidths[1], 10, $user['company_name'], '', 0, 'L');
            $this->Cell($colWidths[2], 10, 'A' . $user['invoice_id'], '', 0, 'L');
            $this->Cell($colWidths[3], 10, 'Bank A/C', '', 0, 'L');
            $this->Cell($colWidths[4], 10, 'Emp Code', '', 0, 'L');
            $this->Cell($colWidths[5], 10, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[6], 10, 'Currency', '', 1, 'L');
        }

        $this->Ln(10);
        $this->Cell(10);
        $this->Cell($colWidths[0], 10, '', '', 0, 'L');
        $this->Cell($colWidths[1], 10, 'Grand Total Employee Count', '', 0, 'L');
        $this->Cell($colWidths[2], 10, count($users), '', 0, 'L');
        $this->Cell($colWidths[3], 10, '', '', 0, 'L');
        $this->Cell(40, 10, 'Grand Total Net Pay', '', 0, 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 10, number_format($grandTotal, 2), '', 0, 'L');
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
