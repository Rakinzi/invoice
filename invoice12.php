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
        $this->CreateBody();
    }
    function Header()
    {
        $this->SetFont('Arial', '', 11);
        $this->Cell(50);
        $this->Cell(30, 1, "ZIMBABWE MANPOWER DEVELOPMENT FUND", 0, 0, 'L');
        $this->Image('./Zimdef.png', 70, 12, 70, 55);
        $this->Ln(55);
        // Check if the page number is within the range of EMP CODEs


    }

    function CreateBody()
    {
        $this->SetFont('Arial', '', 8);
        $this->Cell(8);
        $this->Cell(30, 7, "Amount Paid:          177,494.40", 0, 1, 'L');

        $this->Cell(8);
        $this->Cell(30, 7, "For The month of   September 2023", 0, 1, 'L');

        $this->Ln(2);
        $this->Cell(8);
        $this->Cell(10, 2, "Date Paid:", 0, 0, 'L');
        $this->Cell(10);
        $this->Cell(30, 2, "         /          /", "B", 1, 'L');

        $this->Ln(5);
        $this->Cell(35);
        $this->Cell(120, 2, "", "B", 1, 'L');

        $this->Ln(5);
        $this->Cell(8);
        $this->Cell(30, 7, "Name of Employer:           EDEN ESTATE", 0, 1, 'L');

        $this->Cell(8);
        $this->Cell(30, 7, "Address:", 0, 1, 'L');
        $this->Ln(5);


        $this->Cell(41);
        $this->Cell(30, 7, "HARARE", 0, 1, 'L');

        $this->Ln(5);
        $this->Cell(8);
        $this->Cell(30, 7, "Tel No.:", 0, 0, 'L');
        $this->Cell(70);
        $this->Cell(30, 7, "Fax No.:", 0, 1, 'L');

        $this->Cell(8);
        $this->Cell(30, 7, "EC No.:", 0, 0, 'L');
        $this->Cell(70);
        $this->Cell(30, 7, "I.C No.:", 0, 1, 'L');

        $this->Ln(5);
        $this->Cell(35);
        $this->Cell(120, 2, "", "B", 1, 'L');

        $this->Ln(10);
        $this->Cell(8);
        $this->Cell(60, 7, "I (Note 2) ...................................................................................................", 0, 1, 'L');

        $this->Cell(8);
        $this->Cell(15, 7, "REPRESENTING", 0, 0, 'L');
        $this->Cell(10);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 7, "EDEN ESTATE", 0, 1, 'L');

        $this->SetFont('Arial', '', 8);
        $this->Cell(8);
        $this->Cell(15, 7, "DECLARE THAT THE WAGE BILL FOR ALL EMPLOYEES INCLUDING DIRECTORS (Note 3)", 0, 1, 'L');

        $this->Cell(8);
        $this->Cell(20, 7, "FOR THE MONTH OF", 0, 0, 'L');
        $this->Cell(15);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 7, "September 2023", 0, 0, 'L');
        $this->Cell(15);
        $this->SetFont('Arial', '', 8);
        $this->Cell(5, 7, "WAS", 0, 0, 'L');
        $this->Cell(10);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 7, "17,749,439.91", 0, 1, 'L');

        $this->SetFont('Arial', '', 8);
        $this->Cell(8);
        $this->Cell(60, 7, "AND I ENCLOSED A CHEQUE/P.O./M.O. FOR (Note 4)", 0, 0, 'L');
        $this->Cell(20);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 7, "177,494.40", 0, 1, 'L');

        $this->SetFont('Arial', '', 8);
        $this->Cell(8);
        $this->Cell(60, 7, "PAYABLE TO MANPOWER DEVELOPMENT FUND", 0, 1, 'L');

        $this->Cell(8);
        $this->Cell(16, 7, "BEING", 0, 0, 'L');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 7, "1.000%", 0, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(20, 7, "OF THAT WAGE BILL", 0, 1, 'L');

        $this->Ln(15);
        $this->Cell(8);
        $this->Cell(16, 7, "NATURE OF BUSINESS (Note 5) .....................................................................................................................", 0, 1, 'L');

        $this->Cell(8);
        $this->Cell(16, 7, "SIGNED ....................................................................................................................", 0, 1, 'L');

        $this->Cell(8);
        $this->Cell(16, 7, "DATED ......./......./............", 0, 1, 'L');
    }
}

$validate = new Validate();
$rows = $validate->getUserData();
$pdf = new PDF($validate, $rows);
$pdf->AddPage();
$pdf->loopHeaderData();
$pdf->AliasNbPages();
$pdf->Output('', 'ZIMRA');
