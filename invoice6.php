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
        $this->SetHeader();
        $this->CreateBody();
        $this->AddPage();
    }

    function Header()
    {
        $this->SetFont('Arial', '', 12);
        $this->Cell(80);
        $this->Cell(10, 1, 'CONFIRMED PAYROLL CALCULATION CONTROLS', 0, 0, 'L');
        $this->Cell(140);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 1, 'Page ' . $this->PageNo() . ' of {nb}', 0, 1);


        $this->Ln(10);        // Check if the page number is within the range of EMP CODEs


        $this->SetFont('Arial', '', 8);
        $this->Cell(10);
        $this->Cell(20, 1, "ESTATES", 0, 1, 'L');
    }
    function SetHeader()
    {


        $this->Ln(5);
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(10);
        $this->Cell(18, 1, "Selection: All Records", 0, 0, 'L');

        $this->Ln(5);
        $this->SetFont('Arial', '', 6);
        $this->Cell(10);
        $this->Cell(12, 1, "Printed On", 0, 0, 'L');
        $this->Cell(25, 1, date('d/m/Y') . ' @ ' . date('H:i:s') . '- Confirmed Run', 0, 0, 'L');
        $this->Cell(20);
        $this->Cell(30, 1, "Pay Date:" . date('d/m/Y'), 0, 1, 'L');


        $this->Ln(8);
        $numberOfUsers = $this->validate->getUserRows();
        $this->Cell(10);
        $this->Cell(35, 1, "Total Net Pay For The Run", 0, 0, 'L');
        $this->Cell(10, 1, '195.220', 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(20, 1, "Exchange Rate:", 0, 0, 'L');
        $this->Cell(5, 1, number_format(1, 2), 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(30, 1, '31,641,722.30', 0, 0, 'L');
        $this->Cell(20);
        $this->Cell(40, 1, 'Number Of Emloyees Processed :', 0, 0, 'L');
        $this->Cell(15, 1, $numberOfUsers, 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(35, 1, 'Employees on tax directives:', 0, 0, 'L');
        $this->Cell(10, 1, $numberOfUsers, 0, 1, 'L');

        $this->Ln(5);
        $this->Cell(10);
        $this->Cell(35, 1, "Gross Costs", 0, 0, 'L');
        $this->Cell(10, 1, '195.220', 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(20, 1, "", 0, 0, 'L');
        $this->Cell(5, 1, '', 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(30, 1, '38,415,203.44', 0, 0, 'L');
        $this->Cell(20);
        $this->Cell(40, 1, 'Govt. Statistics : - Female Count:', 0, 0, 'L');
        $this->Cell(15, 1, $numberOfUsers, 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(35, 1, 'Male Count:', 0, 0, 'L');
        $this->Cell(10, 1, $numberOfUsers, 0, 1, 'L');

        $this->Ln(5);
        $this->Cell(10);
        $this->Cell(30, 1, "FISCAL EMPLOYEE AND COMPANY ACCUMULATIVES REPORT", 0, 0, 'L');




        $this->Ln(5);
        $this->Rect(5, 5, 287, 200);
    }

    function CreateBody()
    {
        $colWidths = [40, 40, 40, 40, 40, 30, 30, 10];
        $this->SetFillColor(192, 192, 192);
        $this->SetFont('Arial', '', 6);

        $this->Cell(10);
        $this->Cell($colWidths[0], 5, 'TAX TOTAL - Descriptions', 'B', 0, 'L');
        $this->Cell($colWidths[1], 5, 'Employee Bal B/D', 'B', 0, 'L');
        $this->Cell($colWidths[2], 5, 'Complany Bal B/D', 'B', 0, 'L');
        $this->Cell($colWidths[3], 5, 'Employee Values Posted', 'B', 0, 'L');
        $this->Cell($colWidths[4], 5, 'Company Values Posted ', 'B', 0, 'L');
        $this->Cell($colWidths[5], 5, 'Employee Values C/Forward', 'B', 0, 'L');
        $this->Cell($colWidths[6], 5, 'Company C/ Forward', 'B', 0, 'L');
        $this->Cell($colWidths[7], 5, '', 'B', 1, 'L');

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
            $this->Cell($colWidths[0], 3, $user['client_name'], '', 0, 'L');
            $this->Cell($colWidths[1], 3, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[2], 3, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[3], 3, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[4], 3, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[5], 3, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[6], 3, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[6], 3, 'Currency', '', 1, 'L');
            $this->Cell(10);
            $this->Cell($colWidths[0], 3, '', '', 0, 'L');
            $this->Cell($colWidths[1], 3, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[2], 3, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[3], 3, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[4], 3, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[5], 3, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[6], 3, number_format($invoiceTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[6], 3, 'Currency', '', 1, 'L');
        }
        $this->Ln(2);

        $this->Cell(10);
        $this->Cell($colWidths[0], 4, 'LOCAL TOTALS', 'T', 0, 'L');
        $this->Cell($colWidths[1], 4, number_format($grandTotal, 2), 'T', 0, 'L');
        $this->Cell($colWidths[2], 4, number_format($grandTotal, 2), 'T', 0, 'L');
        $this->Cell($colWidths[3], 4, number_format($grandTotal, 2), 'T', 0, 'L');
        $this->Cell($colWidths[4], 4, number_format($grandTotal, 2), 'T', 0, 'L');
        $this->Cell($colWidths[5], 4, number_format($grandTotal, 2), 'T', 0, 'L');
        $this->Cell($colWidths[6], 4, number_format($grandTotal, 2), 'T', 0, 'L');
        $this->Cell($colWidths[7], 4, '', 'T', 1, 'L');
        $this->Cell(10);
        $this->Cell($colWidths[0], 4, 'FOREX TOTALS', 'B', 0, 'L');
        $this->Cell($colWidths[1], 4, number_format($grandTotal, 2), 'B', 0, 'L');
        $this->Cell($colWidths[2], 4, number_format($grandTotal, 2), 'B', 0, 'L');
        $this->Cell($colWidths[3], 4, number_format($grandTotal, 2), 'B', 0, 'L');
        $this->Cell($colWidths[4], 4, number_format($grandTotal, 2), 'B', 0, 'L');
        $this->Cell($colWidths[5], 4, number_format($grandTotal, 2), 'B', 0, 'L');
        $this->Cell($colWidths[6], 4, number_format($grandTotal, 2), 'B', 0, 'L');
        $this->Cell($colWidths[7], 4, '', 'B', 1, 'L');

        $this->Ln(3);
        $this->Cell(10);
        $this->Cell(20, 4, 'NSSA & WCIF TOTALS', '', 1, 'L');

        $this->Ln(5);

        $this->Cell(10);
        $this->Cell(20, 5, '', '', 0, 'L');
        $this->Cell(40, 5, 'Standards', '', 0, 'L');
        $this->Cell(40, 5, 'Manpower', '', 0, 'L');
        $this->Cell(40, 5, 'Pensionable', '', 0, 'L');
        $this->Cell(50, 5, 'Insurable Earnings', '', 0, 'L');
        $this->Cell(20, 5, 'WCIF', '', 0, 'L');
        $this->Cell(20, 5, 'WCIF', '', 0, 'L');
        $this->Cell(20, 5, 'NSSA', '', 0, 'L');
        $this->Cell($colWidths[6], 5, 'WCIF', '', 1, 'L');

        $colWidths = [30, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20];
        $this->Cell(10);
        $this->Cell($colWidths[0], 5, 'Company', 'B', 0, 'L');
        $this->Cell($colWidths[1], 5, 'Gross', 'B', 0, 'L');
        $this->Cell($colWidths[2], 5, 'Levy', 'B', 0, 'L');
        $this->Cell($colWidths[3], 5, 'Gross', 'B', 0, 'L');
        $this->Cell($colWidths[4], 5, 'Levy', 'B', 0, 'L');
        $this->Cell($colWidths[5], 5, 'Earnings', 'B', 0, 'L');
        $this->Cell($colWidths[6], 5, 'Under 65', 'B', 0, 'L');
        $this->Cell($colWidths[7], 5, 'Over 65', 'B', 0, 'L');
        $this->Cell($colWidths[8], 5, 'Total', 'B', 0, 'L');
        $this->Cell($colWidths[9], 5, 'Rate', 'B', 0, 'L');
        $this->Cell($colWidths[10], 5, 'Contribution', 'B', 0, 'L');
        $this->Cell($colWidths[11], 5, 'Count', 'B', 0, 'L');
        $this->Cell($colWidths[12], 5, 'Count', 'B', 1, 'L');

        $numberOfUsers = $this->validate->getUserRows();
        $this->Ln(1);
        $this->Cell(10);
        $this->Cell(30, 4, 'EDEN ESTATE', '', 1, 'L'); {
            $this->Cell(10);
            $this->Cell($colWidths[0], 3, 'ZWL', '', 0, 'L');
            $this->Cell($colWidths[1], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[2], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[3], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[4], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[5], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[6], 3, '', '', 0, 'L');
            $this->Cell($colWidths[7], 3, number_format(0, 2), '', 0, 'L');
            $this->Cell($colWidths[8], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[9], 3, number_format(1.35, 4) . "%", '', 0, 'L');
            $this->Cell($colWidths[10], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[11], 3, $numberOfUsers, '', 0, 'L');
            $this->Cell($colWidths[12], 3, $numberOfUsers, '', 1, 'L');

            $this->Cell(10);
            $this->Cell($colWidths[0], 3, 'ZWL', '', 0, 'L');
            $this->Cell($colWidths[1], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[2], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[3], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[4], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[5], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[6], 3, '', '', 0, 'L');
            $this->Cell($colWidths[7], 3, number_format(0, 2), '', 0, 'L');
            $this->Cell($colWidths[8], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[9], 3, number_format(1.35, 4) . "%", '', 0, 'L');
            $this->Cell($colWidths[10], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[11], 3, $numberOfUsers, '', 0, 'L');
            $this->Cell($colWidths[12], 3, $numberOfUsers, '', 1, 'L');
        }

        $this->Ln(1);
        $this->Cell(10);
        $this->Cell(30, 4, 'MONCHERI ESTATE', '', 1, 'L'); {
            $this->Cell(10);
            $this->Cell($colWidths[0], 3, 'ZWL', '', 0, 'L');
            $this->Cell($colWidths[1], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[2], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[3], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[4], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[5], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[6], 3, '', '', 0, 'L');
            $this->Cell($colWidths[7], 3, number_format(0, 2), '', 0, 'L');
            $this->Cell($colWidths[8], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[9], 3, number_format(1.35, 4) . "%", '', 0, 'L');
            $this->Cell($colWidths[10], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[11], 3, $numberOfUsers, '', 0, 'L');
            $this->Cell($colWidths[12], 3, $numberOfUsers, '', 1, 'L');

            $this->Cell(10);
            $this->Cell($colWidths[0], 3, 'ZWL', '', 0, 'L');
            $this->Cell($colWidths[1], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[2], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[3], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[4], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[5], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[6], 3, '', '', 0, 'L');
            $this->Cell($colWidths[7], 3, number_format(0, 2), '', 0, 'L');
            $this->Cell($colWidths[8], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[9], 3, number_format(1.35, 4) . "%", '', 0, 'L');
            $this->Cell($colWidths[10], 3, number_format($grandTotal, 2), '', 0, 'L');
            $this->Cell($colWidths[11], 3, $numberOfUsers, '', 0, 'L');
            $this->Cell($colWidths[12], 3, $numberOfUsers, '', 1, 'L');
        }
        $this->Ln(3);
        $this->Cell(10);
        $this->Cell($colWidths[0], 5, '', '', 0, 'L');
        $this->Cell($colWidths[1], 5, number_format($grandTotal * 2, 2), 'TB', 0, 'L');
        $this->Cell($colWidths[2], 5, number_format($grandTotal * 2, 2), 'TB', 0, 'L');
        $this->Cell($colWidths[3], 5, number_format($grandTotal * 2, 2), 'TB', 0, 'L');
        $this->Cell($colWidths[4], 5, number_format($grandTotal * 2, 2), 'TB', 0, 'L');
        $this->Cell($colWidths[5], 5, number_format($grandTotal * 2, 2), 'TB', 0, 'L');
        $this->Cell($colWidths[6], 5, '', '', 0, 'L');
        $this->Cell($colWidths[7], 5, number_format(0, 2), 'TB', 0, 'L');
        $this->Cell($colWidths[8], 5, number_format($grandTotal * 2, 2), 'TB', 0, 'L');
        $this->Cell($colWidths[9], 5, number_format(1.35, 4) . "%", 'TB', 0, 'L');
        $this->Cell($colWidths[10], 5, number_format($grandTotal * 2, 2), 'TB', 0, 'L');
        $this->Cell($colWidths[11], 5, $numberOfUsers * 2, 'TB', 0, 'L');
        $this->Cell($colWidths[12], 5, $numberOfUsers * 2, 'TB', 1, 'L');


        $this->AddPage();
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(60);
        $this->Cell(60, 10, 'EMPLOYEE STATUS SUMMARY CONTROL', '', 1, 'L');

        $colWidths = [30, 30, 30, 30];
        $this->SetFont('Arial', '', 8);
        $this->Cell(10);
        $this->Cell($colWidths[0], 10, 'Status', 'B', 0, 'L');
        $this->Cell($colWidths[1], 10, 'Count', 'B', 0, 'L');
        $this->Cell($colWidths[2], 10, 'Leave Previous', 'B', 0, 'L');
        $this->Cell($colWidths[3], 10, 'Leave Accrued', 'B', 1, 'L'); {
            $this->Cell(10);
            $this->Cell($colWidths[0], 7, 'PERMANENT', '', 0, 'L');
            $this->Cell($colWidths[1], 7, number_format($numberOfUsers * 2, 0), 'B', 0, 'L');
            $this->Cell($colWidths[2], 7, number_format($grandTotal * 2, 2), 'B', 0, 'L');
            $this->Cell($colWidths[3], 7, number_format($grandTotal * 2, 2), 'B', 1, 'L');

            $this->Cell(10);
            $this->Cell($colWidths[0], 7, '', '', 0, 'L');
            $this->Cell($colWidths[1], 7, number_format($numberOfUsers * 2, 0), 'B', 0, 'L');
            $this->Cell($colWidths[2], 7, number_format($grandTotal * 2, 2), 'B', 0, 'L');
            $this->Cell($colWidths[3], 7, number_format($grandTotal * 2, 2), 'B', 0, 'L');
            $this->Cell(30);
            $this->Cell(50, 7, 'Employess on tax directives:  0', '', 1, 'L');
        }


        $this->Ln(10);
        $this->Cell(80);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(60, 10, 'EMPLOYEE STATUS SUMMARY CONTROL', '', 1, 'L');

        $colWidths = [40, 40, 40, 40, 40];
        $this->SetFont('Arial', '', 8);
        $this->Cell(10);
        $this->Cell($colWidths[0], 10, 'Loan', 'B', 0, 'L');
        $this->Cell($colWidths[1], 10, 'Count', 'B', 0, 'L');
        $this->Cell($colWidths[2], 10, 'Install', 'B', 0, 'L');
        $this->Cell($colWidths[3], 10, 'Balance', 'B', 0, 'L');
        $this->Cell($colWidths[3], 10, '', 'B', 1, 'L'); {
            $this->Cell(10);
            $this->Cell($colWidths[0], 7, 'PERMANENT', '', 0, 'L');
            $this->Cell($colWidths[1], 7, number_format($numberOfUsers * 2, 0), '', 0, 'L');
            $this->Cell($colWidths[2], 7, number_format($grandTotal * 2, 2), '', 0, 'L');
            $this->Cell($colWidths[3], 7, number_format($grandTotal * 2, 2), 'B', 0, 'L');
            $this->Cell(10);
            $this->Cell($colWidths[4], 7, number_format($grandTotal * 2, 2), '', 1, 'L');

            $this->Cell(10);
            $this->Cell($colWidths[0], 7, '', '', 0, 'L');
            $this->Cell($colWidths[1], 7, '', '', 0, 'L');
            $this->Cell($colWidths[2], 7, 'Total Outstanding', '', 0, 'L');
            $this->Cell($colWidths[3], 7, number_format($grandTotal * 2, 2), 'B', 0, 'L');
            $this->Cell(10);
            $this->Cell($colWidths[4], 7, number_format($grandTotal * 2, 2), '', 1, 'L');
        }
    }







    function Footer()
    {
        $this->SetY(-13);
        $this->SetFont('Arial', 'I', 6);
        $this->Cell(0, 4, 'Confirmed Pay Run On 29/09/2023 ', 'T', 0, 'L');
        $this->Cell(-270, 4, 'Brought forward figures were dated 31/08/2023', 'T', 0, 'C');
        $this->Cell(0, 4, 'Database ', '', 1, 'R');
        $this->Cell(0, 4, '', '', 0, 'L');
        $this->Cell(-270, 4, 'Selection: All Records', '', 0, 'C');
        $this->Cell(0, 4, '', '', 1, 'R');
    }
}

$validate = new Validate();
$rows = $validate->getUserData();
$pdf = new PDF($validate, $rows);
$pdf->AddPage();
$pdf->loopHeaderData();
$pdf->AliasNbPages();
$pdf->Output();
