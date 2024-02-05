<div class="row">
    <div class="col-md-4 mb-3 d-flex">
        <?php include('../analytics/chart_cal_percent.php') ?>
        <div class="card custom-card text-white" style="background-color: #3C3F58; flex-grow: 1;">
            <div class="card-header text-center" style="background-color: #252745;">Plan % Normal Time</div>
            <div class="card-body d-flex align-items-center justify-content-center" style="height: 150px;">
                <h5 class="card-title">
                    <?php
                    echo "<span style='font-size: 40px; color: white;'>" . number_format($percentage_plan, 2) . "%</span><br>";
                    ?>
                </h5>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3 d-flex">
        <div class="card custom-card text-white" style="background-color: #41446B; flex-grow: 1;">
            <div class="card-header text-center" style="background-color: #313456;">Actual % Normal Time</div>
            <div class="card-body d-flex align-items-center justify-content-center" style="height: 150px;">
                <h5 class="card-title">
                    <?php
                    echo "<span style='font-size: 40px; color: white;'>" . number_format($percentage_actual, 2) . "%</span><br>";
                    ?>
                </h5>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3 d-flex">
        <div class="card custom-card text-white" style="background-color: /* Insert $colorClass color code here */; flex-grow: 1;">
            <div class="card-header text-center" style="background-color: #4E5283;">Percentage Difference</div>
            <div class="card-body d-flex align-items-center justify-content-center" style="height: 150px;">
                <h5 class="card-title">
                    <?php
                    echo "<span style='font-size: 40px; color: white;'>" . number_format($diff_percentage_plan_actual, 2) . "%</span><br>";
                    ?>
                </h5>
            </div>
        </div>
    </div>
</div>
