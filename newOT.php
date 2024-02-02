<?php

require_once('../../config/connection.php');

?>



<?php include('../analytics/include/header.php') ?>

<body>

    <?php include('../analytics/include/navbar.php') ?>
    <?php include('../analytics/include/sidebar.php') ?>


    <div class="main-container">
        <div class="pd-ltr-20">
            <!-- <div class="card-box upper-box pd-20 height-100-p mb-30" >
                <h4 class="font-20 weight-500 mb-20 text-capitalize" style="margin-bottom: 0px">
                    วิเคราะห์ข้อมูลการทำ OT
                </h4>
                <p class="font-8 max-width-1000;">ข้อมูล ณ วันที่ <?php echo strtoupper(date('d-M-Y')); ?> </p>
            </div> -->
            <div class="card-box lower-box pd-20 height-100-p mb-30">
            <?php include('../analytics/new.php') ?>

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
                <?php include('../analytics/chart_ot_per_person.php') ?>
                <?php include('../analytics/chart_top5_emp.php') ?>
            </div>
        </div>
        <?php include('../analytics/include/footer.php') ?>
    </div>
    </div>


    <!-- js -->
    <?php include('../analytics/include/scripts.php') ?>
</body>

</html>