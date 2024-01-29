<?php
//session_start();
// require_once('C:\xampp\htdocs\dashboard analytics\config\connection.php');
require_once('../../config/connection.php');

$currentYear = date('Y');
$filterData = $_SESSION['filter'] ?? null;
$sqlConditions_actual = "date_start BETWEEN '{$filterData['startMonthDate']}' AND '{$filterData['endMonthDateCurrent']}'";

if ($filterData) {
    

    if (!empty($filterData['businessId'])) {
        $sqlConditions_actual .= " AND sb.business_id = '{$filterData['businessId']}'";
    }

    if (!empty($filterData['sub_businessId'])) {
        $sqlConditions_actual .= " AND o.sub_business_id = '{$filterData['sub_businessId']}'";
    }

    if (!empty($filterData['organizationId'])) {
        $sqlConditions_actual .= " AND c.organization_id = '{$filterData['organizationId']}'";
    }

    if (!empty($filterData['companyId'])) {
        $sqlConditions_actual .= " AND l.company_id = '{$filterData['companyId']}'";
    }

    if (!empty($filterData['locationId'])) {
        $sqlConditions_actual .= " AND dv.location_id = '{$filterData['locationId']}'";
    }

    if (!empty($filterData['divisionId'])) {
        $sqlConditions_actual .= " AND d.division_id = '{$filterData['divisionId']}'";
    }

    if (!empty($filterData['departmentId'])) {
        $sqlConditions_actual .= " AND s.department_id = '{$filterData['departmentId']}'";
    }

    if (!empty($filterData['sectionId'])) {
        $sqlConditions_actual .= " AND cc.section_id = '{$filterData['sectionId']}'";
    }
}

$sql = "SELECT 
            ott.type_fix_nonfix AS TYPE_OT,
            ott.name AS NAME_OT,
            SUM(otr.attendance_hours) AS SUM_HOURS
        FROM 
            ot_record as otr
        INNER JOIN 
            ot_type as ott ON otr.ot_type_id = ott.ot_type_id
        INNER JOIN 
            employee as e ON otr.card_id = e.card_id
        INNER JOIN 
            cost_center as cc ON e.cost_center_organization_id = cc.cost_center_id
        INNER JOIN 
            section as s ON cc.section_id = s.section_id
        INNER JOIN
            department d ON s.department_id = d.department_id
        INNER JOIN 
            division dv ON d.division_id = dv.division_id
        INNER JOIN
            location l ON dv.location_id = l.location_id
        INNER JOIN
            company c ON l.company_id = c.company_id
        INNER JOIN
            organization o ON c.organization_id = o.organization_id
        INNER JOIN
            sub_business sb ON o.sub_business_id = sb.sub_business_id
        INNER JOIN
            business b on sb.business_id = b.business_id
        WHERE 
            $sqlConditions_actual
        GROUP BY 
            ott.type_fix_nonfix,
            ott.name";

$result = sqlsrv_query($conn, $sql);

$ot_data = [];
if ($result) {
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $type = $row['TYPE_OT'];
        $name = $row['NAME_OT'];
        $totalHours = $row['SUM_HOURS'];

        // สะสมข้อมูลสำหรับแผนภูมิ
        if (!isset($ot_data[$type])) {
            $ot_data[$type] = [
                'total_hours' => 0,
                'details' => []
            ];
        }
        $ot_data[$type]['total_hours'] += $totalHours;
        $ot_data[$type]['details'][$name] = $totalHours;
    }
}

// echo $filterData['startMonthDate'];
// echo "<br>";
// echo $filterData['endMonthDateCurrent'];
// echo "<br>";
// echo $filterData['businessId'];
// echo "<br>";
// echo $filterData['sub_businessId'];
// echo "<br>";
// echo $filterData['organizationId'];
// echo "<br>";
// echo $filterData['companyId'];
// echo "<br>";
// echo $filterData['locationId'];
// echo "<br>";
// echo $filterData['divisionId'];
// echo "<br>";
// echo $filterData['departmentId'];
// echo "<br>";
// echo $filterData['sectionId'];

$ot_data_json = json_encode($ot_data);
?>

<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    var chart; // Global variable for the chart
    var data; // Global variable for the chart data
    var ot_data = JSON.parse('<?php echo $ot_data_json; ?>'); // ข้อมูล OT ในรูปแบบ JSON

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawDonutChart);

    function drawDonutChart() {
        data = new google.visualization.DataTable();
        data.addColumn('string', 'OT Type');
        data.addColumn('number', 'Hours');

        for (var type in ot_data) {
            if (ot_data.hasOwnProperty(type)) {
                data.addRow([type, ot_data[type]['total_hours']]);
            }
        }

        var options = {
            title: 'Total Hours by OT Type',
            pieHole: 0.4,
            sliceVisibilityThreshold: 0
        };

        chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);

        google.visualization.events.addListener(chart, 'select', selectHandler);
    }

    function selectHandler() {
        var selectedItem = chart.getSelection()[0];
        if (selectedItem) {
            var type = data.getValue(selectedItem.row, 0);
            drawDetailChart(type, ot_data[type]['details']);
        }
    }

    function drawDetailChart(type, details) {
        var detailData = new google.visualization.DataTable();
        detailData.addColumn('string', 'OT Name');
        detailData.addColumn('number', 'Hours');

        for (var name in details) {
            if (details.hasOwnProperty(name)) {
                // ตรวจสอบและแปลงค่าเป็นตัวเลข
                var hours = parseFloat(details[name]);
                if (!isNaN(hours)) {
                    detailData.addRow([name, hours]);
                }
            }
        }

        var options = {
            title: 'Detail Hours for ' + type,
            pieHole: 0.4
        };

        var detailChart = new google.visualization.PieChart(document.getElementById('detailchart'));
        detailChart.draw(detailData, options);
    }
    </script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-5">
        <div id="donutchart" style="width: 150%; height: 400px;"></div>
      </div>
      <div class="col-md-5">
        <div id="detailchart" style="width: 150%; height: 400px;"></div>
      </div>
    </div>
  </div>
</body>
</html>
