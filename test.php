<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'config.php';
$sql = "SELECT * FROM booking_clients AS bc INNER JOIN tbl_booking_invoices AS bi ON bc.client_id = bi.client_id WHERE bc.client_id = bi.client_id;";
$result = $conn->query($sql);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($rows as $row) {
    print_r($row);
}
