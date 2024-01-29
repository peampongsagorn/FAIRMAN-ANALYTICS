<?php
session_start(); // เรียกใช้ session_start() ก่อนที่จะใช้ session
require_once('C:\xampp\htdocs\dashboard analytics\config\connection.php');

?>

<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>SCG | Fair Manpower</title>

	<!-- Site favicon -->
	<link rel="icon" type="image/ico" href="../favicon.ico">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../../vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="../../src/plugins/jquery-steps/jquery.steps.css">
	<link rel="stylesheet" type="text/css" href="../../vendors/styles/style.css">

	<script src="../asset/plugins/sweetalert2-11.10.1/jquery-3.7.1.min.js"></script>
	<script src="../asset/plugins/sweetalert2-11.10.1/sweetalert2.all.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<style>
		.flex {
			display: flex;
		}
	</style>

</head>
<body>

    <?php include('C:\xampp\htdocs\dashboard analytics\admin\include\navbar.php') ?>
    <?php include('C:\xampp\htdocs\dashboard analytics\admin\include\sidebar.php') ?>

<div class="main-container">
    <div class="pd-ltr-20">
        <div class="card-box pd-20 height-100-p mb-30">
            <h3>วิเคราะห์ข้อมูลการทำ OT เกิน 36 ชั่วโมงต่อสัปดาห์</h3>
            <p class="font-18 max-width-1000">
                * หมายเหตุ ทางผู้พัฒนาได้ปรับปรุงส่วน <a href="listemployee_Create.php">ข้อมูลพนักงาน</a>ณ วันที่ 5 ม.ค. 2567
            </p>
            <?php include('C:\xampp\htdocs\dashboard analytics\admin\analytics\filter.php') ?>
        </div>
    </div>
</div>
    <div class="container-fluid mt-1">
        <div class="row">


        </div>

    </div>    

    <?php include('C:\xampp\htdocs\dashboard analytics\admin\include\footer.php'); ?>
</div>

    

    <!-- js -->
    <?php include('C:\xampp\htdocs\dashboard analytics\admin\include\scripts.php') ?>

</body>
</html>

</body>
</html>