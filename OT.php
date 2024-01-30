<?php

require_once('../../config/connection.php');
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <title>SCG | Fair Manpower</title>

    <link rel="icon" type="image/ico" href="../../favicon.ico">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


    <link rel="stylesheet" type="text/css" href="../../vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="../../src/plugins/jquery-steps/jquery.steps.css">
    <link rel="stylesheet" type="text/css" href="../../vendors/styles/style.css">

 
    <script src="../../asset/plugins/sweetalert2-11.10.1/jquery-3.7.1.min.js"></script>
    <script src="../../asset/plugins/sweetalert2-11.10.1/sweetalert2.all.min.js"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .flex {
            display: flex;
        }

        .card-box {
            width: 100%;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .pd-ltr-20 {
            display: flex;
            justify-content: space-between;
            align-items: start;
        }

        .upper-box {
        flex-grow: 1; 
        flex-basis: calc(25% - 10px); 
        margin: 5px;
        padding: 10px;
        box-sizing: border-box;
        }

        .lower-box {
            flex-grow: 2; 
            flex-basis: calc(70% - 10px); 
            margin: 5px;
            padding: 10px;
            box-sizing: border-box;
        }

        @media (min-width: 768px) {
        .upper-box,
        .lower-box {
            flex-basis: calc(50% - 20px);
            margin: 10px;
        }
        }

        .upper-box,
        .lower-box {
        max-width: 880px;
        }
        .custom-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 100%;
            max-width: 18rem;
        }

        .custom-card .card-header,
        .custom-card .card-body {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .custom-card .card-title {
            margin: 0;
            font-size: 1.25rem;
        }

        .custom-card .card-title span {
            font-size: 2.5rem;
        }

        .custom-card .col-md-4 {
            flex: 0 0 auto;
            width: 33.33333%;
        }

        .custom-card .mb-3 {
            margin-bottom: 1rem;
        }
    </style>

</head>

<body>

    <?php include('../analytics/include/navbar.php') ?>
    <?php include('../analytics/include/sidebar.php') ?>


    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="card-box upper-box pd-20 height-100-p mb-30">
                <h4 class="font-20 weight-500 mb-20 text-capitalize" style="margin-bottom: 0px">
                    วิเคราะห์ข้อมูลการทำ OT
                </h4>
                <p class="font-8 max-width-1000;">ข้อมูล ณ วันที่ <?php echo strtoupper(date('d-M-Y')); ?> </p>
                <?php include('../analytics/filter.php') ?>
            </div>
            <div class="card-box lower-box pd-20 height-100-p mb-30">
                <?php include('C:\xampp\htdocs\dashboard analytics\admin\analytics\chart_cal_percent.php'); ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card custom-card text-white bg-primary">
                                <div class="card-header">Plan % Normal Time</div>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php
                                        echo "<span style='font-size: 40px;'>" . number_format($percentage_plan, 2) . "%</span><br>";
                                        ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card custom-card text-white bg-secondary">
                                <div class="card-header">Actual % Normal Time</div>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php
                                        echo "<span style='font-size: 40px;'>" . number_format($percentage_actual, 2) . "%</span><br>";
                                        ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card custom-card text-white <?php echo $colorClass; ?> ">
                                <div class="card-header">Percentage Difference</div>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php
                                        echo "<span style='font-size: 40px;'>" . number_format($diff_percentage_plan_actual, 2) . "%</span><br>";
                                        ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('../analytics/chart_planning_trend.php') ?>
                <?php include('../analytics/chart_ot_type_drilldown.php') ?>
                <?php include('../analytics/chart_top5_emp.php') ?>
                <?php include('../analytics/chart_ot_per_person.php') ?>
            </div>
        </div>
        <?php include('../analytics/include/footer.php') ?>
    </div>
    </div>


    <!-- js -->
    <?php include('../analytics/include/scripts.php') ?>
</body>

</html>