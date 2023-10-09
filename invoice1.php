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
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(60);
        $this->Cell(30, 10, 'MONCHERI ESTATE', 0, 1, 'L');
        $newRow = $rows;
        // Check if the page number is within the range of EMP CODEs
        $this->Cell(60);
        $this->Cell(30, 1, 'EMP CODE: ' . $newRow['client_name'], 0, 1, 'L');
        $this->Ln(5);

        $this->SetFont('Arial', '', 8);
        $this->Cell(60);
        $this->Cell(30, 1, "DEPT: " . $newRow['company_name'], 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(20, 1, "LEAVE DUE:  1.533", 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(10, 1, "TAKEN: 0.00 ", 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(10, 1, "GRD: 2 ", 0, 1, 'L');

        $this->Ln(5);
        $this->Cell(60);
        $this->Cell(30, 1, "TITLE: SECURITY GUARD", 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(20, 1, "DATE JOINED:  " . date('d/m/Y', strtotime($newRow['created_at'])), 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(10, 1, "DOB: " . date('d/m/Y'), 0, 1, 'L');

        $this->Ln(5);
        $this->Cell(60);
        $this->Cell(30, 1, "ID: 43-226396M47", 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(20, 1, "PAY RATE: 21,115.3847", 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(10, 1, "NSSA: ", 0, 1, 'L');

        $this->Ln(5);
        $this->Cell(60);
        $this->Cell(30, 1, "PAY DATE: 29/09/2023", 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(20, 1, "ZWL RATE: 21,115.38", 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(10, 1, "USD RATE: 0.00 ", 0, 1, 'L');

        $this->Ln(5);
        $this->Rect(5, 5, 287, 200);
    }

    function CreateBody($rows)
    {
        $colWidths = [60, 20, 40, 20, 60, 20, 40, 20];
        $this->SetFillColor(192, 192, 192);
        $this->SetFont('Arial', '', 7);

        $this->Cell($colWidths[0], 10, 'EARNING', 'TLR', 0, 'C', 1);
        $this->Cell($colWidths[1], 10, 'AMOUNT', 'TR', 0, 'C', 1);
        $this->Cell($colWidths[2], 10, 'DETAIL', 'TR', 0, 'C', 1);
        $this->Cell($colWidths[3], 10, 'CURRENCY', 'TR', 0, 'C', 1);
        $this->Cell($colWidths[4], 10, 'DEDUCTIONS', 'TLR', 0, 'C', 1);
        $this->Cell($colWidths[5], 10, 'AMOUNT', 'TR', 0, 'C', 1);
        $this->Cell($colWidths[6], 10, 'DETAIL', 'TR', 0, 'C', 1);
        $this->Cell($colWidths[7], 10, 'CURRENCY', 'TR', 1, 'C', 1);

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
                $this->Cell($colWidths[0], 10, '0' . $invoiceItem['invoice_items_id'] . ' ' . $invoiceItem['item_name'], 'L', 0, 'C');
                $this->Cell($colWidths[1], 10, $invoiceItem['item_price'], '', 0, 'C');
                $this->Cell($colWidths[2], 10, $invoiceItem['item_quantity'], '', 0, 'C');
                $this->Cell($colWidths[3], 10, $rows['currency'], 'R', 0, 'C');
                $invoiceTotal += $invoiceItem['item_price'];
            } else {
                // Print empty cells for invoice items
                $this->Cell($colWidths[0], 10, '', 'L', 0, 'C');
                $this->Cell($colWidths[1], 10, '', '', 0, 'C');
                $this->Cell($colWidths[2], 10, '', '', 0, 'C');
                $this->Cell($colWidths[3], 10, '', 'R', 0, 'C');
            }

            // Print quotation item on the left
            if ($i < $quotationItemCount) {
                $quotationItem = $items2[$i];
                $this->Cell($colWidths[4], 10, '0' . $quotationItem['invoice_items_id'] . ' ' . $quotationItem['item_name'], '', 0, 'C');
                $this->Cell($colWidths[5], 10, $quotationItem['item_price'], '', 0, 'C');
                $this->Cell($colWidths[6], 10, $quotationItem['item_quantity'], '', 0, 'C');
                $this->Cell($colWidths[7], 10, $rows['currency'], 'R', 0, 'C');
                $this->Ln(6);
                $quotationTotal += $quotationItem['item_price'];
            } else {
                // Print empty cells for quotation items
                $this->Cell($colWidths[4], 10, '', '', 0, 'C');
                $this->Cell($colWidths[5], 10, '', '', 0, 'C');
                $this->Cell($colWidths[6], 10, '', '', 0, 'C');
                $this->Cell($colWidths[7], 10, '', 'R', 0, 'C');
                $this->Ln(3);
            }
        }

        $this->Cell($colWidths[0], 10, '', 'LB', 0, 'C');
        $this->Cell($colWidths[1], 10, '', 'B', 0, 'C');
        $this->Cell($colWidths[2], 10, '', 'B', 0, 'C');
        $this->Cell($colWidths[3], 10, '', 'RB', 0, 'C');
        $this->Cell($colWidths[4], 10, '', 'B', 0, 'C');
        $this->Cell($colWidths[5], 10, '', 'B', 0, 'C');
        $this->Cell($colWidths[6], 10, '', 'B', 0, 'C');
        $this->Cell($colWidths[7], 10, '', 'RB', 1, 'C');

        $this->SetFont('Arial', 'B', 7);
        $this->Cell($colWidths[0], 10, 'Earnings', 'L', 0, 'C');
        $this->SetFont('Arial', '', 7);
        $this->Cell($colWidths[1], 10, number_format($quotationTotal, 2), '', 0, 'C', 1);
        $this->Cell($colWidths[2], 10, number_format($quotationTotal, 2), '', 0, 'C', 1);
        $this->Cell($colWidths[3], 10, number_format(($quotationTotal - $quotationTotal), 2), '', 0, 'C', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell($colWidths[4], 10, 'Deductions', '', 0, 'C');
        $this->SetFont('Arial', '', 7);
        $this->Cell($colWidths[5], 10, number_format($quotationTotal, 2), '', 0, 'C', 1);
        $this->Cell($colWidths[6], 10, number_format($quotationTotal, 2), '', 0, 'C', 1);
        $this->Cell($colWidths[7], 10, number_format(($quotationTotal - $quotationTotal), 2), 'R', 1, 'C', 1);

        $this->Cell(40, 10, 'BANK:', 'L', 0, 'C'); // Left and right borders
        $this->Cell(240, 10, '', 'R', 1, 'C');

        $this->Cell(60, 10, 'Exchange rate:  1.00', 'LB', 0, 'C');
        $this->Cell(70, 10, 'Net Pay:  528,008.41', 'B', 0, 'C');
        $this->Cell(70, 10, 'USD Net Paid:  0.00', 'B', 0, 'C');
        $this->Cell(80, 10, 'ZWL Net Paid:  528,008.41', 'RB', 1, 'C');

        $this->Cell(40, 10, 'A-Salary, wages, overtime', 'LT', 0, 'C');
        $this->Cell($colWidths[1], 10, '', 'T', 0, 'C');
        $this->Cell($colWidths[2], 10, '554,278.85', 'T', 0, 'C');
        $this->Cell($colWidths[3], 10, 'R-Professional Subscriptions', 'T', 0, 'C');
        $this->Cell($colWidths[4], 10, '4,750.96', 'T', 0, 'C');
        $this->Cell($colWidths[5], 10, 'N.S.S.A', 'T', 0, 'C');
        $this->Cell($colWidths[5], 10, '', 'T', 0, 'C');
        $this->Cell(60, 10, '14,252.88', 'RT', 1, 'C');

        $this->Cell(40, 10, 'X-AIDS LEVY', 'LB', 0, 'C');
        $this->Cell($colWidths[1], 10, '', 'B', 0, 'C');
        $this->Cell($colWidths[2], 10, '211.64', 'B', 0, 'C');
        $this->Cell($colWidths[3], 10, 'Y-PAYE DEDUCTED', 'B', 0, 'C');
        $this->Cell($colWidths[4], 10, '7,054.96', 'B', 0, 'C');
        $this->Cell(100, 10, '', 'RB', 0);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$validate = new Validate();
$rows = $validate->getUserData();
$pdf = new PDF($validate, $rows);
$pdf->AddPage();
$pdf->loopHeaderData();
$pdf->AliasNbPages();
$pdf->Output();
