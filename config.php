<?php

class Validate
{
    public $dbConnect = false;
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $db = 'dicommfl_portal_trial';

    public function __construct()
    {
        if (!$this->dbConnect) {
            $conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

            if ($conn->error) {
                die('Error in mysql connection :' . $conn->connect_error);
            } else {
                $this->dbConnect = $conn;
            }
        }
    }

    public function invoice_items($invoice_id)
    {
        $sql = "SELECT * FROM tbl_booking_invoice_items where invoice_id = $invoice_id";
        $result = mysqli_query($this->dbConnect, $sql);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $row;
    }
    public function getUserData()
    {
        $sql = "SELECT * FROM booking_clients AS bc INNER JOIN tbl_booking_invoices AS bi ON bc.client_id = bi.client_id WHERE bc.client_id = bi.client_id;";
        $result = $this->dbConnect->query($sql);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $rows;
    }

    public function getUserRows()
    {
        $sql = "SELECT * FROM booking_clients AS bc INNER JOIN tbl_booking_invoices AS bi ON bc.client_id = bi.client_id WHERE bc.client_id = bi.client_id;";
        $result = $this->dbConnect->query($sql);
        return $result->num_rows;
    }
}
