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
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(100);
        $this->Cell(10, 1, 'ITF/16  TAX  INFORMATION', 0, 0, 'L');
        $this->Cell(140);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 1, 'Page ' . $this->PageNo() . ' of {nb}', 0, 1);


        $this->Ln(10);        // Check if the page number is within the range of EMP CODEs

        $this->SetFont('Arial', 'B', 7);
        $this->Cell(10);
        $this->Cell(15, 1, "COMPANY", 0, 0, 'L');
        $this->Cell(30, 1, 'ESTATE', 0, 1, 'L');

        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(25, 1, "For Tax Period", 0, 0, 'L');
        $this->Cell(50, 1, date('d/m/Y') . '-' . date('d/m/Y'), 0, 1, 'L');






        $this->Ln(5);
        $this->Rect(5, 5, 287, 200);
    }

    function CreateBody()
    {
        $colWidths = [50, 26, 20, 30, 30, 30, 30, 20, 30];
        $this->SetFillColor(192, 192, 192);
        $this->SetFont('Arial', 'B', 7);

        $this->Cell(10);
        $this->Cell($colWidths[0], 5, 'NAME', 'B', 0, 'L');
        $this->Cell($colWidths[1], 5, 'TAX CREDITS', 'B', 0, 'L');
        $this->Cell($colWidths[2], 5, 'P6 No.', 'B', 0, 'L');
        $this->Cell($colWidths[3], 5, 'GROSS INCOME', 'B', 0, 'L');
        $this->Cell($colWidths[4], 5, 'AIDS LEVY', 'B', 0, 'L');
        $this->Cell($colWidths[5], 5, 'PAYE', 'B', 0, 'L');
        $this->Cell($colWidths[6], 5, 'TOTAL TAX PAID', 'B', 0, 'L');
        $this->Cell($colWidths[7], 5, 'EMP CODE', 'B', 0, 'L');
        $this->Cell($colWidths[8], 5, 'TOTAL TAX PAID', 'B', 1, 'L');


        $this->SetFont('Arial', '', 7);

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
            $this->Cell($colWidths[0], 5, $user['client_name'], '', 0, 'L');
            $this->Cell($colWidths[1], 5, number_format(0, 2), '', 0, 'L');
            $this->Cell($colWidths[2], 5, 'A' . $user['invoice_id'], '', 0, 'L');
            $this->Cell($colWidths[3], 5, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[4], 5, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[5], 5, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[6], 5, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[7], 5, 'EE' . $user['invoice_id'], '', 0, 'L');
            $this->Cell($colWidths[8], 5, '75-364722A75', '', 1, 'L');
        }

        $this->Ln(2);
        $this->Cell(10);
        $this->Cell($colWidths[0], 5, '', '', 0, 'L');
        $this->Cell($colWidths[1], 5, number_format(0, 2), 'T', 0, 'L');
        $this->Cell($colWidths[2], 5, "", '', 0, 'L');
        $this->Cell($colWidths[3], 5, number_format($grandTotal, 2), 'T', 0, 'L');
        $this->Cell($colWidths[4], 5, number_format($grandTotal, 2), 'T', 0, 'L');
        $this->Cell($colWidths[5], 5, number_format($grandTotal, 2), 'T', 0, 'L');
        $this->Cell($colWidths[6], 5, number_format($grandTotal, 2), 'T', 0, 'L');
        $this->Cell($colWidths[7], 5, '', '', 0, 'L');
        $this->Cell($colWidths[8], 5, '', '', 1, 'L');

        $this->Ln(10);
        $this->Cell(10);
        $this->Cell(20, 1, "I hereby certify that the foregoing particulars are in every respect fully and truly stated.", 0, 0, 'L');

        $this->Ln(10);
        $this->Cell(160);
        $this->Cell(20, 1, "Signature:....................................................................", 0, 0, 'L');
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
