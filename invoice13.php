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
        $this->SetTextColor(255, 0, 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(95);
        $this->Cell(10, 5, 'JOURNAL - TRANSACTION CODES', 0, 0, 'L');
        $this->Cell(140);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(10, 5, 'US$', 0, 1);


        $this->Ln(5);        // Check if the page number is within the range of EMP CODEs

        $this->SetFont('Arial', '', 10);
        $this->Cell(10);
        $this->Cell(20, 5, "DATE", 0, 0, 'L');
        $this->Cell(15);
        $this->Cell(10, 5, ":25/04/2023", 0, 0, 'L');
        $this->Cell(65);
        $this->Cell(10, 5, "BIOMEST SALARIES", 0, 1, 'L');



        $this->Cell(10);
        $this->Cell(20, 5, "TIME", 0, 0, 'L');
        $this->Cell(15);
        $this->Cell(10, 5, ":11:00", 0, 1, 'L');
        $this->Ln(5);


        $this->Cell(10);
        $this->Cell(20, 5, "START PERIOD", 0, 0, 'L');
        $this->Cell(15);
        $this->Cell(10, 5, ":2023/04", 0, 1, 'L');

        $this->Cell(10);
        $this->Cell(20, 5, "END PERIOD", 0, 0, 'L');
        $this->Cell(15);
        $this->Cell(10, 5, ":2023/04", 0, 1, 'L');





        $this->Ln(5);
        $this->Rect(5, 5, 287, 200);
    }


    function CreateBody()
    {
        $colWidths = [20, 26, 50, 30, 30, 30, 30, 20, 30];
        $this->SetFillColor(192, 192, 192);
        $this->SetFont('Arial', 'B', 7);

        $this->Cell(10);
        $this->Cell($colWidths[0], 5, 'LEDGER', 'T', 0, 'L');
        $this->Cell($colWidths[1], 5, 'CODE', 'T', 0, 'L');
        $this->Cell($colWidths[2], 5, 'DESCRIPTION', 'T', 0, 'L');
        $this->Cell($colWidths[3], 5, 'DEBIT', 'T', 0, 'L');
        $this->Cell($colWidths[4], 5, 'CREDIT', 'T', 0, 'L');
        $this->Cell($colWidths[5], 5, 'DEBIT', 'T', 0, 'L');
        $this->Cell($colWidths[6], 5, 'CREDIT', 'T', 0, 'L');
        $this->Cell($colWidths[7], 5, 'DEBIT ', 'T', 0, 'L');
        $this->Cell($colWidths[8], 5, 'CREDIT ', 'T', 1, 'L');

        $this->Cell(10);
        $this->Cell($colWidths[0], 5, '', '', 0, 'L');
        $this->Cell($colWidths[1], 5, '', '', 0, 'L');
        $this->Cell($colWidths[2], 5, '', '', 0, 'L');
        $this->Cell($colWidths[3], 5, 'EMPLOYEE', '', 0, 'L');
        $this->Cell($colWidths[4], 5, 'EMPLOYEE', '', 0, 'L');
        $this->Cell($colWidths[5], 5, 'BENEFITS', '', 0, 'L');
        $this->Cell($colWidths[6], 5, 'BENEFITS', '', 0, 'L');
        $this->Cell($colWidths[7], 5, 'EMPLOYER', '', 0, 'L');
        $this->Cell($colWidths[8], 5, 'EMPLOYER', '', 1, 'L');

        $this->Cell(10);
        $this->Cell($colWidths[0], 5, '', 'B', 0, 'L');
        $this->Cell($colWidths[1], 5, '', 'B', 0, 'L');
        $this->Cell($colWidths[2], 5, '', 'B', 0, 'L');
        $this->Cell($colWidths[3], 5, 'PAYMENTS', 'B', 0, 'L');
        $this->Cell($colWidths[4], 5, 'DEDUCTIONS', 'B', 0, 'L');
        $this->Cell($colWidths[5], 5, '', 'B', 0, 'L');
        $this->Cell($colWidths[6], 5, '', 'B', 0, 'L');
        $this->Cell($colWidths[7], 5, 'CONTR', 'B', 0, 'L');
        $this->Cell($colWidths[8], 5, 'CONTR', 'B', 1, 'L');


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
            $this->Cell($colWidths[0], 5, '', '', 0, 'L');
            $this->Cell($colWidths[1], 5, '0' . $user['invoice_id'], '', 0, 'L');
            $this->Cell($colWidths[2], 5, $user['client_name'], '', 0, 'L');
            $this->Cell($colWidths[3], 5, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[4], 5, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[5], 5, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[6], 5, number_format(0, 2), '', 0, 'L');
            $this->Cell($colWidths[7], 5, number_format(0, 2), '', 0, 'L');
            $this->Cell($colWidths[8], 5, number_format(0, 2), '', 1, 'L');
        }

        $this->Ln(2);
        $this->Cell(10);
        $this->Cell($colWidths[0], 5, '', '', 0, 'L');
        $this->Cell($colWidths[1], 5, '', '', 0, 'L');
        $this->Cell($colWidths[2], 5, "", '', 0, 'L');
        $this->Cell($colWidths[3], 5, number_format($grandTotal, 2), 'TB', 0, 'L');
        $this->Cell($colWidths[4], 5, number_format($grandTotal, 2), 'TB', 0, 'L');
        $this->Cell($colWidths[5], 5, number_format($grandTotal, 2), 'TB', 0, 'L');
        $this->Cell($colWidths[6], 5, number_format(0, 2), 'TB', 0, 'L');
        $this->Cell($colWidths[7], 5, number_format(0, 2), 'TB', 0, 'L');
        $this->Cell($colWidths[8], 5, number_format(0, 2), 'TB', 1, 'L');


        $this->Ln(20);
        $this->Cell(125);
        $this->Cell(20, 5, "TOTAL EMPLOYEES: " . (count($users)) . " of " . (count($users)), 0, 1, 'L');

        $this->Cell(131);
        $this->Cell(20, 5, "END OF REPORT", 0, 1, 'L');
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
