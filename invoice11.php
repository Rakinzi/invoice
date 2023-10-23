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
        $this->Image('./Zim.jpeg', 65, 10, 70, 35);
        $this->Ln(40);
        // Check if the page number is within the range of EMP CODEs

        $this->SetFont('Arial', '', 7);
        $this->Ln(5);
        $this->Cell(8);
        $this->Cell(30, 1, "SDL No.:", 0, 0, 'L');
        $this->Cell(90);
        $this->Cell(30, 1, "Three Months End:   30/09/2023", 0, 1, 'L');

        $this->Ln(10);
        $this->Cell(8);
        $this->Cell(30, 1, "WAGE BILL:   49,319,463.22", 0, 0, 'L');
        $this->Cell(90);
        $this->Cell(30, 1, "Levy Paid:                 246,597.32", 0, 1, 'L');
        $this->Ln(10);
        $this->Rect(5, 5, 190, 70);
    }

    function CreateBody()
    {
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(70);
        $this->Cell(30, 5, "MINISTRY OF INDUSTRY AND COMMERCE", 0, 1, 'L');

        $this->Rect(15, 85, 170, 50);
        $this->Ln(10);
        $this->Cell(15);
        $this->Cell(30, 5, "EDEN ESTATE", 0, 1, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Ln(10);
        $this->Cell(15);
        $this->Cell(30, 5, "HARARE", 0, 1, 'L');

        $this->Ln(25);
        $this->Cell(8);
        $this->Cell(30, 5, "SDL No.:", 0, 0, 'L');
        $this->Cell(45);
        $this->Cell(20, 5, "I.C No.:", 0, 0, 'L');
        $this->Cell(35);
        $this->Cell(30, 5, "DATE OF ISSUE", 0, 1, 'L');

        $this->Ln(10);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(60);
        $this->Cell(30, 5, "STANDARDS DEVELOPMENT LEVY DECLARATION", 0, 1, 'L');

        $this->Ln(5);
        $this->SetFont('Arial', '', 7);
        $this->Cell(8);
        $this->Cell(30, 7, "I (Note 2) ..................................", 0, 1, 'L');
        $this->Cell(8);
        $this->Cell(30, 7, "DECLARE THAT THE WAGE BILL OF (Note2) .........................................", 0, 1, 'L');
        $this->Cell(8);
        $this->Cell(30, 7, "FOR ALL EMPLOYEES INCLUDING WORKING DIRECTORS", 0, 1, 'L');

        $this->Ln(5);
        $this->Cell(8);
        $this->Cell(30, 7, "FOR THE THREE MONTHS ENDED", 0, 0, 'L');
        $this->Cell(50);
        $this->Cell(20, 7, "WAS:", 0, 0, 'L');
        $this->Cell(60, 6, "49,319,463.22", 1, 1, 'L');

        $this->Ln(2);
        $this->Cell(8);
        $this->Cell(10, 7, "ON", 0, 0, 'L');
        $this->Cell(35, 6, "49,319,463.22", 1, 0, 'L');
        $this->Cell(10);
        $this->Cell(20, 7, "EMPLOYEES (Note 6)", 0, 1, 'L');

        $this->Ln(2);
        $this->Cell(8);
        $this->Cell(30, 7, "AND I ENCLOSE A CHEQUE / USD / RAND / FOR", 0, 0, 'L');
        $this->Cell(70);
        $this->Cell(60, 6, "49,319,463.22", 1, 1, 'L');

        $this->Ln(10);
        $this->Cell(8);
        $this->Cell(50, 7, "SIGNED ......................................................................", 0, 0, 'L');
        $this->Cell(40);
        $this->Cell(30, 7, "DATE.....................................................", 0, 0, 'L');
    }

    function Footer()
    {
        $this->SetY(-25);
        $this->Cell(8);
        $this->Cell(30, 4, "This form must be returned to the STANDARDS DEVELOPMENT FUND,", 0, 1, 'L');
        $this->Cell(8);
        $this->Cell(30, 4, "P.Bag 7708, CAUSEWAY, HARARE. N.B. THIS LEVY IS TO BE PAID PROMPTLY - See Note 5", 0, 1, 'L');

        $this->Cell(8);
        $this->Cell(30, 5, "PHYSICAL ADDRESSES:", 0, 1, 'L');

        $this->SetFont('Arial', '', 5);
        $this->Cell(8);
        $this->Cell(30, 3, "14th Floor Mukwati Building", 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(30, 3, "3rd Floor Mhlamhlandlela Building", 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(30, 3, "Trade Measures Building\\", 0, 1, 'L');

        $this->Cell(8);
        $this->Cell(30, 3, "Livingstone / Fourth Street", 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(30, 3, "Basch st / 10th Ave", 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(30, 3, "3rd Street / 4th Street.", 0, 1, 'L');

        $this->Cell(8);
        $this->Cell(30, 3, "Email: inspectors@sdf.gov.zw", 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(30, 3, "P.O. 696, Bulawayo", 0, 0, 'L');
        $this->Cell(30);
        $this->Cell(30, 3, "P.O. Box 630, Mutare", 0, 1, 'L');
    }
}

$validate = new Validate();
$rows = $validate->getUserData();
$pdf = new PDF($validate, $rows);
$pdf->AddPage();
$pdf->loopHeaderData();
$pdf->AliasNbPages();
$pdf->Output('', 'ZIMRA');
