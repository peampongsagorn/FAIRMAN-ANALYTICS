<?php

require_once('../../config/connection.php');

?>



<?php include('../analytics/include/header.php') ?>

<body>

    <?php include('../analytics/include/navbar.php') ?>
    <?php include('../analytics/include/sidebar.php') ?>



    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="card-box lower-box pd-20 height-100-p mb-30" style="background-color: #212233">

                <!-- หัวข้อรายงาน OT -->
                <div class="row mb-4">
                    <div class="col-2">
                        <h2 class="text-white bg-dark p-2" style=" text-align: center; border: 2px solid #3E4080;
                        border-radius: 15px; box-shadow: 2px 4px 5px #3E4080">รายงาน OT</h2>
                    </div>
                    <div class="col-4 justify-content-end">
                        <?php include('../analytics/new.php') ?>
                    </div>
                </div>

                <!-- บัตรด้านบน -->
                <div  style="max-width: 1000px; width: auto; margin: auto; justify-content: center; display: flex; align-items: center;">
                    <div class="container-fluid" style="background-color: white; padding: 15px; margin-bottom: 20px; border: 2px solid #3E4080; border-radius: 15px; box-shadow: 5px 5px 5px #3E4080;">
                        <div class="row" style="justify-content: center;">
                            <div class="col-md-3 mb-5 d-flex">
                                <?php include('../analytics/chart_cal_percent.php') ?>
                                <div class="card custom-card text-white"
                                    style="background-color: #41446B; flex-grow: 1; border-radius: 15px; box-shadow: 2px 4px 5px #3E4080">
                                    <div class="card-header text-center"
                                        style="background-color: #313456; border-top-right-radius: 15px; border-top-left-radius: 15px;">
                                        Plan % Normal Time
                                    </div>
                                    <div class="card-body d-flex align-items-center justify-content-center"
                                        style="height: 70px;">
                                        <h5 class="card-title">
                                            <?php
                                            echo "<span style='font-size: 40px; color: white;'>" . number_format($percentage_plan, 2) . "%</span><br>";
                                            ?>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-5 d-flex">
                                <div class="card custom-card text-white"
                                    style="background-color: #41446B; flex-grow: 1; border-radius: 15px; box-shadow: 2px 4px 5px #3E4080">
                                    <div class="card-header text-center"
                                        style="background-color: #313456; border-top-right-radius: 15px; border-top-left-radius: 15px;">
                                        Actual % Normal Time
                                    </div>
                                    <div class="card-body d-flex align-items-center justify-content-center"
                                        style="height: 70px;">
                                        <h5 class="card-title">
                                            <?php
                                            echo "<span style='font-size: 40px; color: white;'>" . number_format($percentage_actual, 2) . "%</span><br>";
                                            ?>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-5 d-flex">
                                <div class="card custom-card text-white"
                                    style="background-color: #41446B ; flex-grow: 1; border-radius: 15px; box-shadow: 2px 4px 5px #3E4080">
                                    <div class="card-header text-center"
                                        style="background-color: #313456; border-top-right-radius: 15px; border-top-left-radius: 15px;">
                                        Percentage
                                        Difference
                                    </div>
                                    <div class="card-body d-flex align-items-center justify-content-center"
                                        style="height: 70px;">
                                        <h5 class="card-title">
                                            <?php
                                            echo "<span style='font-size: 40px; color: white;'>" . number_format($diff_percentage_plan_actual, 2) . "%</span><br>";
                                            ?>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="container-fluid" style="background-color: white; padding: 15px; margin-bottom: 20px">
                    <div class="row no-gutters">
                        <div class="col-md-3 mb-3 d-flex justify-content-center">
                            <?php include('../analytics/chart_cal_percent.php') ?>
                            <div class="card custom-card text-white"
                                style="background-color: #41446B; flex-grow: 1; border-radius: 15px; box-shadow: 2px 4px 5px #3E4080">
                                <div class="card-header text-center"
                                    style="background-color: #313456; border-top-right-radius: 15px; border-top-left-radius: 15px;">
                                    Plan % Normal Time
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center"
                                    style="height: 70px;">
                                    <h5 class="card-title">
                                        <?php
                                        echo "<span style='font-size: 40px; color: white;'>" . number_format($percentage_plan, 2) . "%</span><br>";
                                        ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 d-flex justify-content-center">
                            <div class="card custom-card text-white"
                                style="background-color: #41446B; flex-grow: 1; border-radius: 15px; box-shadow: 2px 4px 5px #3E4080">
                                <div class="card-header text-center"
                                    style="background-color: #313456; border-top-right-radius: 15px; border-top-left-radius: 15px;">
                                    Actual % Normal Time
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center"
                                    style="height: 70px;">
                                    <h5 class="card-title">
                                        <?php
                                        echo "<span style='font-size: 40px; color: white;'>" . number_format($percentage_actual, 2) . "%</span><br>";
                                        ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 d-flex justify-content-center">
                            <div class="card custom-card text-white"
                                style="background-color: #41446B; flex-grow: 1; border-radius: 15px; box-shadow: 2px 4px 5px #3E4080">
                                <div class="card-header text-center"
                                    style="background-color: #313456; border-top-right-radius: 15px; border-top-left-radius: 15px;">
                                    Percentage Difference
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center"
                                    style="height: 70px;">
                                    <h5 class="card-title">
                                        <?php
                                        echo "<span style='font-size: 40px; color: white;'>" . number_format($diff_percentage_plan_actual, 2) . "%</span><br>";
                                        ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="container-fluid" style="background-color: white; padding: 20px; margin-bottom: 20px"> -->
                <!-- <div class="row justify-content-center"> -->
                <!-- <div style="display: flex; justify-content: center; align-items: center; background-color: white; padding: 20px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                        <div class="col-auto mb-3 d-flex">
                            <div class="card custom-card text-white"
                                style="background-color: #41446B; border-radius: 15px; box-shadow: 2px 4px 5px #3E4080; width: 207.75 mm; ">
                                <div class="card-header text-center"
                                    style="background-color: #313456; border-top-right-radius: 15px; border-top-left-radius: 15px;">
                                    Plan % Normal Time
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center"
                                    style="height: 70px;">
                                    <h5 class="card-title">
                                        <?php
                                        echo "<span style='font-size: 40px; color: white;'>" . number_format($percentage_plan, 2) . "%</span><br>";
                                        ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto mb-3 d-flex">
                            <div class="card custom-card text-white"
                                style="background-color: #41446B; border-radius: 15px; box-shadow: 2px 4px 5px #3E4080">
                                <div class="card-header text-center"
                                    style="background-color: #313456; border-top-right-radius: 15px; border-top-left-radius: 15px;">
                                    Actual % Normal Time
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center"
                                    style="height: 70px;">
                                    <h5 class="card-title">
                                        <?php
                                        echo "<span style='font-size: 40px; color: white;'>" . number_format($percentage_actual, 2) . "%</span><br>";
                                        ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto mb-3 d-flex">
                            <div class="card custom-card text-white"
                                style="background-color: #41446B; border-radius: 15px; box-shadow: 2px 4px 5px #3E4080">
                                <div class="card-header text-center"
                                    style="background-color: #313456; border-top-right-radius: 15px; border-top-left-radius: 15px;">
                                    Percentage Difference
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center"
                                    style="height: 70px;">
                                    <h5 class="card-title">
                                        <?php
                                        echo "<span style='font-size: 40px; color: white;'>" . number_format($diff_percentage_plan_actual, 2) . "%</span><br>";
                                        ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->







                <!-- กราฟแนวโน้มการวางแผน OT -->
                <div class="row">
                    <!-- สัดส่วนของ OT จริง -->
                    <div class="col-md-6">
                        <?php include('../analytics/chart_ot_type_drilldown.php'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php include('../analytics/chart_planning_trend.php'); ?>
                    </div>
                </div>

                <!-- สัดส่วนของ OT จริงและค่าเฉลี่ย OT ต่อบุคคล -->
                <div class="row">
                    <!-- ค่าเฉลี่ย OT ต่อบุคคลจริง -->
                    <div class="col-md-6">
                        <?php include('../analytics/chart_ot_per_person.php'); ?>
                    </div>
                    <div class="col-6  ">
                        <?php include('../analytics/chart_top5_emp.php'); ?>
                    </div>
                </div>

                <!-- ตาราง 5 อันดับบุคคลที่มีชั่วโมง OT มากที่สุด -->
                <!-- <div class="row">
                    <div class="col-12">
                        <?php include('../analytics/chart_top5_emp.php'); ?>
                    </div>
                </div> -->
            </div>
        </div>
        <?php include('../analytics/include/footer.php'); ?>
    </div>




    <!-- js -->
    <?php include('../analytics/include/scripts.php') ?>
</body>

</html>