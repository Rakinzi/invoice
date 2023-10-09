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
        $this->SetFont('Arial', '', 14);
        $this->Cell(80);
        $this->Cell(10, 1, "EMPLOYER'S CERTIFICATE IN SUPPORT CLAIM", 0, 1, 'L');
        $this->Cell(150);


        $this->Ln(10);        // Check if the page number is within the range of EMP CODEs
        $this->SetFont('Arial', '', 10);

        $this->Cell(10);
        $this->Cell(18, 1, "SURNAME:", 0, 0, 'L');
        $this->Cell(5);
        $this->Cell(30, 1, strtoupper($rows['client_name']), 0, 0, 'L');
        $this->Cell(55);
        $this->Cell(18, 1, "FIRSTNAME:", 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(30, 1, strtoupper($rows['client_name']), 0, 1, 'L');



        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(30, 1, "SOCIAL SECURITY NO:", 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(30, 1, '', 0, 0, 'L');
        $this->Cell(38);
        $this->Cell(25, 1, "NATIONAL ID NO:", 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(30, 1, '34-034065Y34', 0, 1, 'L');


        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(20, 1, "EMPLOYEE NO:", 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(20, 1, 'ME164', 0, 0, 'L');
        $this->Cell(58);
        $this->Cell(25, 1, "OCCUPATION:", 0, 0, 'L');
        $this->Cell(5);
        $this->Cell(20, 1, 'SECURITY GUARD', 0, 1, 'L');

        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(20, 1, "COMPANY NAME:", 0, 0, 'L');
        $this->Cell(15);
        $this->Cell(20, 1, 'MONCHERI ESTATE', 0, 0, 'L');
        $this->Cell(65);
        $this->Cell(20, 1, "FROM DATE:", 0, 0, 'L');
        $this->Cell(5);
        $this->Cell(30, 1, '02/02/2022', 0, 0, 'L');
        $this->Cell(20);
        $this->Cell(20, 1, "TO DATE:", 0, 0, 'L');
        $this->Cell(5);
        $this->Cell(20, 1, '25/07/2023', 0, 0, 'L');



        $this->Ln(5);
        $this->Rect(5, 5, 287, 200);
    }

    function CreateBody($rows)
    {
        $colWidths = [40, 25, 60, 45];
        $this->SetFillColor(192, 192, 192);
        $this->SetFont('Arial', 'UB', 10);

        $this->Cell(10);
        $this->Cell($colWidths[0], 10, 'MONTH', '', 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(10);
        $this->Cell($colWidths[1], 10, '', '', 0, 'L');
        $this->SetFont('Arial', 'UB', 10);
        $this->Cell($colWidths[2], 10, 'CONTRIBUTIONS PAID', '', 0, 'L');
        $this->Cell(31);
        $this->Cell($colWidths[3], 10, 'INSURABLE EARNINGS', '', 1, 'L');
        $this->SetFont('Arial', '', 10);


        $items = $this->validate->invoice_items($rows['invoice_id']);
        $items2 = $this->validate->invoice_items($rows['invoice_id']);

        $invoiceItemCount = count($items);
        $quotationItemCount = count($items2);

        // Determine the maximum count for both invoice and quotation items
        $maxItemCount = max($invoiceItemCount, $quotationItemCount);

        $invoiceTotal = 0;
        $quotationTotal = 0;
        // Loop through the maximum count
        for ($i = 0; $i < $maxItemCount; $i++) {
            // Print invoice item on the right
            if ($i < $invoiceItemCount) {
                $invoiceItem = $items[$i];
                $this->Cell(10);
                $this->Cell($colWidths[0], 10, date('Y M'), '', 0, 'L');
                $this->Cell(5);
                $this->Cell(15, 10, '', '', 0, 'R');
                $invoiceTotal += $invoiceItem['item_price'];
            } else {
                // Print empty cells for invoice items
                $this->Cell($colWidths[0], 10, '', 'L', 0, 'L');
                $this->Cell($colWidths[1], 10, '', '', 0, 'L');
            }

            // Print quotation item on the left
            if ($i < $quotationItemCount) {
                $quotationItem = $items2[$i];
                $this->Cell(25);
                $this->Cell(30, 10, $quotationItem['item_price'], '', 0, 'R');
                $this->Cell(75);
                $this->Cell(15, 10, $quotationItem['item_price'], '', 0, 'R');
                $this->Ln(6);
                $quotationTotal += $quotationItem['item_price'];
            } else {
                // Print empty cells for quotation items
                $this->Cell($colWidths[2], 10, '', '', 0, 'L');
                $this->Cell($colWidths[3], 10, '', '', 0, 'L');
                $this->Ln(3);
            }
        }
        $this->Ln(3);
        $this->Cell(10);
        $this->Cell($colWidths[0], 10, '', '', 0, 'L');
        $this->Cell(5);
        $this->Cell(15, 5, '', '', 0, 'R');
        $this->Cell(25);
        $this->Cell(30, 10, number_format($quotationTotal, 2), 'TB', 0, 'R');
        $this->Cell(60);
        $this->Cell(30, 10, number_format($quotationTotal, 2), 'TB', 0, 'R');
        $this->Ln(6);
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
