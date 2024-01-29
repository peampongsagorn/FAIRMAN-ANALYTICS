<?php
session_start();
require_once('C:\xampp\htdocs\dashboard analytics\config\connection.php');

$currentYear = date('Y');
$filterData = $_SESSION['filter'] ?? null;
$sqlConditions_plan = "year = '{$currentYear}'"; // เงื่อนไขเริ่มต้นคือข้อมูลของปีปัจจุบัน
$sqlConditions_actual = "date_start BETWEEN '{$filterData['startMonthDate']}' AND '{$filterData['endMonthDateCurrent']}'";
$isDepartmentSpecific = !empty($filterData['departmentId']);


if ($isDepartmentSpecific) {
    $sqlGroupBy = "GROUP BY s.name_thai";
    $sqlSelect = "s.name_thai AS NAME, SUM(otr.Attendance_Hour) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
} else {
    // ถ้าไม่มี departmentId, เลือกข้อมูลตาม department
    $sqlGroupBy = "GROUP BY d.name_thai";
    $sqlSelect = "d.name_thai AS NAME, SUM(otr.Attendance_Hour) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
}

// if ($filterData) {
    
//     if (!empty($filterData['departmentId'])) {
//         $sqlConditions_actual .= " AND s.department_id = '{$filterData['departmentId']}'";
//     }

//     if (!empty($filterData['sectionId'])) {
//         $sqlConditions_actual .= " AND cc.section_id = '{$filterData['sectionId']}'";
//     }
// }

$sql = "SELECT 
            $sqlSelect
        FROM 
            ot_record as otr
        INNER JOIN
            employee as emp ON otr.card_id = emp.card_id
        INNER JOIN 
            cost_center as cc ON emp.cost_center_organization_id = cc.cost_center_id
        INNER JOIN 
            section as s ON cc.section_id = s.section_id
        INNER JOIN
            department as d ON s.department_id = d.department_id
        WHERE
            $sqlConditions_actual
        GROUP BY
            $sqlGroupBy";

$stmt = sqlsrv_query($conn, $sql);
$chartData = [];

if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // เก็บข้อมูลใน array เพื่อใช้ในการสร้างแผนภูมิ
        $chartData[] = [
            'name' => $row['NAME'],
            'average_ot' => $row['AVERAGE_OT']
        ];
    }
}

foreach ($chartData as $data) {
    echo "Department/Section: {$data['name']}, Average OT: {$data['average_ot']}<br>";
}
// echo $filterData['startMonthDate'];
// echo $filterData['endMonthDateCurrent'];
// echo $filterData['sectionId'];
// echo $filterData['departmentId'];

$per_personJson = json_encode($chartData);

?>
<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Department/Section');
            data.addColumn('number', 'Average OT Per Person');

            var queryResults = JSON.parse('<?php echo $per_personJson; ?>');

            queryResults.forEach(function(row) {
                data.addRow([row.name, parseFloat(row.average_ot)]);
            });

            var options = {
                chart: {title: 'Average OT Per Person Actual'},
                bars: 'horizontal', // Required for Material Bar Charts.
                hAxis: {format: 'decimal'},
                height: 400,
                colors: ['#1b9e77', '#d95f02', '#7570b3']
            };

            var chart = new google.charts.Bar(document.getElementById('barchart_material'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    </script>
</head>
<body>
    <div id="barchart_material" style="width: 800px; height: 500px;"></div>
</body>
</html>

