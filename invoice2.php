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
        $this->Cell(10, 1, 'EMPLOYEES ITF36 CERTIFICATE', 0, 0, 'L');
        $this->Cell(100);
        $this->SetFont('Arial', '', 5);
        $this->Cell(10, 1, 'ITF36', 0, 1);

        $this->Ln(5);
        $newRow = $rows;
        // Check if the page number is within the range of EMP CODEs
        $this->Cell(40);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, 'Earnings and Deductions For the Tax Ended 31st December 2023', 0, 0, 'L');
        $this->Cell(70);
        $this->Cell(10, 5, 'Serial No. 1', 0, 1);

        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(20, 1, "1. Surname: ", 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(20, 1, $rows['client_name'], 0, 0, 'L');
        $this->Cell(70);
        $this->Cell(10, 1, "7. Employer's Name:  ", 0, 0, 'L');
        $this->Cell(20);
        $this->Cell(20, 1, $rows['company_name'], 0, 1, 'L');

        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(20, 1, "2. First Name(s): ", 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(20, 1, $rows['client_name'], 0, 0, 'L');
        $this->Cell(70);
        $this->Cell(10, 1, "8. Employer's Adress:  ", 0, 0, 'L');
        $this->Cell(20);
        $this->Cell(20, 1, $rows['company_name'], 0, 1, 'L');

        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(20, 1, "3. Employees Address: ", 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(20, 1, "", 0, 1, 'L');


        $this->Ln(5);
        $this->Rect(5, 5, 200, 287);
    }

    function CreateBody($rows)
    {
        $colWidths = [60, 25, 60, 45];
        $this->SetFillColor(192, 192, 192);
        $this->SetFont('Arial', 'UB', 7);

        $this->Cell(10);
        $this->Cell($colWidths[0], 10, '11. EARNINGS', '', 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(10);
        $this->Cell($colWidths[1], 10, '', '', 0, 'L');
        $this->SetFont('Arial', 'UB', 7);
        $this->Cell($colWidths[2], 10, '11. DETAIL', '', 0, 'L');
        $this->Cell($colWidths[3], 10, '', '', 1, 'L');
        $this->SetFont('Arial', '', 7);


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
                $this->Cell($colWidths[0], 10, '0' . $invoiceItem['invoice_items_id'] . ' ' . $invoiceItem['item_name'], '', 0, 'L');
                $this->Cell(5);
                $this->Cell(15, 10, $invoiceItem['item_price'], '', 0, 'R');
                $invoiceTotal += $invoiceItem['item_price'];
            } else {
                // Print empty cells for invoice items
                $this->Cell($colWidths[0], 10, '', 'L', 0, 'L');
                $this->Cell($colWidths[1], 10, '', '', 0, 'L');
            }

            // Print quotation item on the left
            if ($i < $quotationItemCount) {
                $quotationItem = $items2[$i];
                $this->Cell(15);
                $this->Cell($colWidths[2], 10, '0' . $quotationItem['invoice_items_id'] . ' ' . $quotationItem['item_name'], '', 0, 'L');
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
        $this->Cell(15, 5, number_format($invoiceTotal, 2), 'TB', 0, 'R');
        $this->Cell(15);
        $this->Cell($colWidths[2], 10, '', '', 0, 'L');
        $this->Cell(15, 5, number_format($quotationTotal, 2), 'TB', 0, 'R');
        $this->Ln(6);

        $this->SetFont('Arial', 'UB', 7);

        $this->Cell(10);
        $this->Cell($colWidths[0], 10, '12. BENEFITS', '', 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(10);
        $this->Cell($colWidths[1], 10, '', '', 0, 'L');
        $this->SetFont('Arial', 'UB', 7);
        $this->Cell($colWidths[2], 10, '11. AMOUNTS CONSIDERED FOR CREDITS', '', 0, 'L');
        $this->Cell($colWidths[3], 10, '', '', 1, 'L');
        $this->SetFont('Arial', '', 7);


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
                $this->Cell($colWidths[0], 10, '0' . $invoiceItem['invoice_items_id'] . ' ' . $invoiceItem['item_name'], '', 0, 'L');
                $this->Cell(5);
                $this->Cell(15, 10, $invoiceItem['item_price'], '', 0, 'R');
                $invoiceTotal += $invoiceItem['item_price'];
            } else {
                // Print empty cells for invoice items
                $this->Cell($colWidths[0], 10, '', 'L', 0, 'L');
                $this->Cell($colWidths[1], 10, '', '', 0, 'L');
            }

            // Print quotation item on the left
            if ($i < $quotationItemCount) {
                $quotationItem = $items2[$i];
                $this->Cell(15);
                $this->Cell($colWidths[2], 10, '0' . $quotationItem['invoice_items_id'] . ' ' . $quotationItem['item_name'], '', 0, 'L');
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
        $this->Cell(15, 5, number_format($invoiceTotal, 2), 'TB', 0, 'R');
        $this->Cell(15);
        $this->Cell($colWidths[2], 10, '', '', 0, 'L');
        $this->Cell(15, 5, number_format($quotationTotal, 2), 'TB', 0, 'R');
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
