<?php
session_start();
require_once('C:\xampp\htdocs\dashboard analytics\config\connection.php');

$currentYear = date('Y');
$filterData = $_SESSION['filter'] ?? null;

// $sqlConditions = "WHERE YEAR(otr.date_start) = '{$currentYear}'";
$sqlConditions_actual = "date_start BETWEEN '{$filterData['startMonthDate']}' AND '{$filterData['endMonthDateCurrent']}'";
$isDepartmentSpecific = !empty($filterData['departmentId']);
$sqlSelect = "d.name_eng AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
$sqlGroupBy = "d.name_eng";

if ($filterData) {

    // if (!empty($filterData['businessId'])) {
    //     $sqlSelect = "sb.name_eng AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
    //     $sqlConditions_actual .= " AND sb.business_id = '{$filterData['businessId']}'";
    //     $sqlGroupBy = "sb.name_eng";
    // }

    // elseif (!empty($filterData['sub_businessId'])) {
    //     $sqlSelect = "o.organization_id AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
    //     $sqlConditions_actual .= " AND o.sub_business_id = '{$filterData['sub_businessId']}'";
    //     $sqlGroupBy = " o.organization_id";
    // }

    // elseif (!empty($filterData['organizationId'])) {
    //     $sqlSelect = "c.name_eng AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
    //     $sqlConditions_actual .= " AND dv.organization_id = '{$filterData['organizationId']}'";
    //     $sqlGroupBy = "c.name_eng";
    // }

    // elseif (!empty($filterData['companyId'])) {
    //     $sqlSelect = "l.name_eng AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
    //     $sqlConditions_actual .= " AND l.company_id = '{$filterData['companyId']}'";
    //     $sqlGroupBy = "l.name_eng";
    // }

    // elseif (!empty($filterData['locationId'])) {
    //     $sqlSelect = "dv.name_eng AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
    //     $sqlConditions_actual .= " AND dv.location_id = '{$filterData['locationId']}'";
    //     $sqlGroupBy = "dv.name_eng";
    // }

    // elseif (!empty($filterData['divisionId'])) {
    //     $sqlSelect = "d.name_eng AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
    //     $sqlConditions_actual .= " AND d.division_id = '{$filterData['divisionId']}'";
    //     $sqlGroupBy = "d.name_eng";
    // }

    // elseif (!empty($filterData['departmentId'])) {
    //     $sqlSelect = "s.name_eng AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
    //     $sqlConditions_actual .= " AND s.department_id = '{$filterData['departmentId']}'";
    //     $sqlGroupBy = "s.name_eng";
    // }

    // elseif (!empty($filterData['sectionId'])) {
    //     $sqlSelect = "cc.cost_center_code AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
    //     $sqlConditions_actual .= " AND cc.section_id = '{$filterData['sectionId']}'";
    //     $sqlGroupBy = "cc.cost_center_code";
    // }

    if(!empty($filterData['sectionId'])) {
            $sqlSelect = "cc.cost_center_code AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
            $sqlConditions_actual .= " AND cc.section_id = '{$filterData['sectionId']}'";
            $sqlGroupBy = "cc.cost_center_code";
        }
    elseif (!empty($filterData['departmentId'])) {
            $sqlSelect = "s.name_eng AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
            $sqlConditions_actual .= " AND s.department_id = '{$filterData['departmentId']}'";
            $sqlGroupBy = "s.name_eng";
        }
    elseif (!empty($filterData['divisionId'])) {
            $sqlSelect = "d.name_eng AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
            $sqlConditions_actual .= " AND d.division_id = '{$filterData['divisionId']}'";
            $sqlGroupBy = "d.name_eng";
        }
    elseif (!empty($filterData['locationId'])) {
            $sqlSelect = "dv.name_eng AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
            $sqlConditions_actual .= " AND dv.location_id = '{$filterData['locationId']}'";
            $sqlGroupBy = "dv.name_eng";
        }
    elseif (!empty($filterData['companyId'])) {
            $sqlSelect = "l.name_eng AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
            $sqlConditions_actual .= " AND l.company_id = '{$filterData['companyId']}'";
            $sqlGroupBy = "l.name_eng";
        }
    elseif (!empty($filterData['organizationId'])) {
            $sqlSelect = "c.name_eng AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
            $sqlConditions_actual .= " AND dv.organization_id = '{$filterData['organizationId']}'";
            $sqlGroupBy = "c.name_eng";
        }
                        
    elseif (!empty($filterData['sub_businessId'])) {
            $sqlSelect = "o.organization_id AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
            $sqlConditions_actual .= " AND o.sub_business_id = '{$filterData['sub_businessId']}'";
            $sqlGroupBy = " o.organization_id";
        }
    elseif (!empty($filterData['businessId'])) {
            $sqlSelect = "sb.name_eng AS NAME, SUM(otr.attendance_hours) / NULLIF(COUNT(DISTINCT(otr.card_id)),0) AS AVERAGE_OT";
            $sqlConditions_actual .= " AND sb.business_id = '{$filterData['businessId']}'";
            $sqlGroupBy = "sb.name_eng";
        }
}
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
        INNER JOIN
            division as dv on d.division_id = dv.division_id
        INNER JOIN
            location as l on dv.location_id = l.location_id
        INNER JOIN 
            company as c on l.company_id = c.company_id
        INNER JOIN
            organization as o on c.organization_id = o.organization_id
        INNER JOIN
            sub_business as sb on o.sub_business_id = sb.sub_business_id
        INNER JOIN
            business b on sb.business_id = b.business_id
        WHERE
            {$sqlConditions_actual}
        GROUP BY
            $sqlGroupBy";

$stmt = sqlsrv_query($conn, $sql);
$chartData = [];

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $chartData[] = [
        'name' => $row['NAME'],
        'average_ot' => $row['AVERAGE_OT']
    ];
}

$chartDataJson = json_encode($chartData);
?>

<!DOCTYPE html>
<html>

<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', { 'packages': ['bar'] });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Department/Section');
            data.addColumn('number', 'Average OT Per Person');

            var chartData = JSON.parse('<?php echo $chartDataJson; ?>');
            chartData.forEach(function (row) {
                data.addRow([row.name, parseFloat(row.average_ot)]);
            });

            var options = {
                chart: { title: 'Average OT Per Person Actual' },
                bars: 'horizontal',
                hAxis: { format: 'decimal' },
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