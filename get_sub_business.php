<?php
session_start();
// require_once('C:\xampp\htdocs\dashboard analytics\config\connection.php');
require_once('../../config/connection.php');

header('Content-Type: application/json');

// ตรวจสอบว่ามีการส่ง division_id มาหรือไม่
if(isset($_GET['businessId']) && !empty($_GET['businessId'])) {
    $businessId = $_GET['businessId'];
    // กรองข้อมูล department ตาม division_id ที่รับมา
    $sql = "SELECT sub_business_id,name_eng FROM sub_business WHERE business_id = ?";
    $params = array($businessId);
} else {
    // ถ้าไม่มี division_id มา ก็เลือกข้อมูล department ทั้งหมด
    $sql = "SELECT sub_business_id,name_eng FROM sub_business";
    $params = array();
}

$stmt = sqlsrv_query($conn, $sql, $params);

$sub_businesses = array();
if($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $sub_businesses[] = $row;
    }
    echo json_encode($sub_businesses);
} else {
    die(print_r(sqlsrv_errors(), true));
}
?>

