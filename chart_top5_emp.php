<?php
//session_start();
require_once('../../config/connection.php');

$currentYear = date('Y');
$filterData = $_SESSION['filter'] ?? null;
// $sqlConditions_actual = "date_start BETWEEN '{$filterData['startMonthDate']}' AND '{$filterData['endMonthDateCurrent']}'";
$currentYear = date('Y'); // ปีปัจจุบัน
$startYear = $currentYear . '-01-01'; // วันที่ 1 มกราคมของปีปัจจุบัน
$currentDate = date('Y-m-d'); // วันที่ปัจจุบัน

$sqlConditions_actual = "date_start BETWEEN '{$startYear}' AND '{$currentDate}'";


if ($filterData) {

    if (!empty($filterData['startMonthDate']) && !empty($filterData['endMonthDateCurrent'])) {
        $sqlConditions_actual = "date_start BETWEEN '{$filterData['startMonthDate']}' AND '{$filterData['endMonthDateCurrent']}'";
 
    }

    if (!empty($filterData['sectionId'])) {
        $sqlConditions_actual .= " AND cc.section_id = '{$filterData['sectionId']}'";
    }
    elseif (!empty($filterData['departmentId'])) {
        $sqlConditions_actual .= " AND s.department_id = '{$filterData['departmentId']}'";
    }
    elseif (!empty($filterData['divisionId'])) {
        $sqlConditions_actual .= " AND d.division_id = '{$filterData['divisionId']}'";
    }
    elseif (!empty($filterData['locationId'])) {
        $sqlConditions_actual .= " AND dv.location_id = '{$filterData['locationId']}'";
    }
    elseif (!empty($filterData['companyId'])) {
        $sqlConditions_actual .= " AND l.company_id = '{$filterData['companyId']}'";
    }
    elseif (!empty($filterData['organizationId'])) {
        $sqlConditions_actual .= " AND c.organization_id = '{$filterData['organizationId']}'";
    }
    elseif (!empty($filterData['sub_businessId'])) {
        $sqlConditions_actual .= " AND o.sub_business_id = '{$filterData['sub_businessId']}'";
    }
    elseif (!empty($filterData['businessId'])) {
        $sqlConditions_actual .= " AND sb.business_id = '{$filterData['businessId']}'";
    }
}

$sql = "SELECT 
		CONCAT(e.firstname_thai ,' ',e.lastname_thai) AS EMPLOYEE_NAME,
        SUM(otr.attendance_hours) AS SUM_HOURS,
        ott.type_fix_nonfix AS TYPE_OT,
        d.name_thai AS DEPARTMENT,
        s.name_thai AS SECTION
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
                {$sqlConditions_actual}
        GROUP BY 
            CONCAT(e.firstname_thai ,' ',e.lastname_thai),
            ott.type_fix_nonfix,
            d.name_thai,
            s.name_thai
                ";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

$ActualOtData = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $name = $row['EMPLOYEE_NAME'];
    $department = $row['DEPARTMENT'];
    $section = $row['SECTION'];
    $type = $row['TYPE_OT'];
    $hours = $row['SUM_HOURS'];

    // ตรวจสอบและสร้าง array ถ้ายังไม่มี
    if (!isset($ActualOtData[$name])) {
        $ActualOtData[$name] = [];
    }
    if (!isset($ActualOtData[$name][$department])) {
        $ActualOtData[$name][$department] = [];
    }
    if (!isset($ActualOtData[$name][$department][$section])) {
        $ActualOtData[$name][$department][$section] = ['FIX' => 0, 'NONFIX' => 0];
    }

    // สะสมข้อมูลจำนวนชั่วโมงตามประเภท OT
    if ($type === 'FIX') {
        $ActualOtData[$name][$department][$section]['FIX'] += $hours;
    } else if ($type === 'NONFIX') {
        $ActualOtData[$name][$department][$section]['NONFIX'] += $hours;
    }
}

$sortedData = [];

// ลูปผ่านข้อมูลเพื่อคำนวณ totalHours
foreach ($ActualOtData as $name => $departments) {
    foreach ($departments as $department => $sections) {
        foreach ($sections as $section => $hours) {
            $totalHours = $hours['FIX'] + $hours['NONFIX']; // คำนวณชั่วโมงรวม
            $sortedData[] = [
                'name' => $name,
                'department' => $department,
                'section' => $section,
                'FIX' => $hours['FIX'],
                'NONFIX' => $hours['NONFIX'],
                'totalHours' => $totalHours
            ];
        }
    }
}

// เรียงลำดับข้อมูลตาม totalHours จากมากไปน้อย
usort($sortedData, function ($a, $b) {
    return $b['totalHours'] <=> $a['totalHours'];
});
$top10 = array_slice($sortedData, 0, 10);
?>
<html>

<head>
<style>
    .table {
        width: 90%;
        margin: auto;
    }

    thead th {
        font-size: 14px;
    }

    tbody {
        font-size: 12px;
    }

    th, td {
        padding: 3px;
    }
</style>

</head>

<body>
    <table class="data-table2 table striped hover nowrap">
        <thead>
            <tr>
                <th scope="col">NAME</th>
                <th scope="col">DEPARTMENT</th>
                <th scope="col">SECTION</th>
                <th scope="col">TOTAL HOURS</th>
                <th scope="col">OT NON_FIX</th>
                <th scope="col">OT FIX</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($top10 as $item) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($item['name']) . '</td>';
                echo '<td>' . htmlspecialchars($item['department']) . '</td>';
                echo '<td>' . htmlspecialchars($item['section']) . '</td>';
                echo '<td>' . htmlspecialchars($item['totalHours']) . '</td>';
                echo '<td>' . htmlspecialchars($item['FIX']) . ' (' . number_format(($item['FIX'] / $item['totalHours']) * 100, 2) . '%) </td>';
                echo '<td>' . htmlspecialchars($item['NONFIX']) . ' (' . number_format(($item['NONFIX'] / $item['totalHours']) * 100, 2) . '%) </td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</body>
<script>
    $(document).ready(function() {
        // Initialize DataTable with custom options
        var dataTable = $('.data-table2').DataTable({
            "lengthMenu": [4, 5, 6, 7, 8], // เลือกจำนวนแถวที่แสดง
            "pageLength": 5, // จำนวนแถวที่แสดงต่อหน้าเริ่มต้น
            "dom": '<"d-flex justify-content-between"lf>rt<"d-flex justify-content-between"ip><"clear">', // ตำแหน่งของ elements
            "language": {
                
                "zeroRecords": "ไม่พบข้อมูล",
                "info": "แสดงหน้าที่ PAGE จาก PAGES",
                "infoEmpty": "ไม่มีข้อมูลที่แสดง",
                "infoFiltered": "(กรองจากทั้งหมด MAX รายการ)",
                "search": "ค้นหา:",
                "paginate": {
                    "first": "หน้าแรก",
                    "last": "หน้าสุดท้าย",
                    "next": "ถัดไป",
                    "previous": "ก่อนหน้า"
                }
            }
        });

        // Add Bootstrap styling to length dropdown and search input
        $('select[name="dataTables_length"]').addClass('form-control form-control-sm');
        $('input[type="search"]').addClass('form-control form-control-sm');

        // Trigger DataTables redraw on select change
        $('select[name="dataTables_length"]').change(function() {
            dataTable.draw();
        });

        // Trigger DataTables search on input change
        $('input[type="search"]').on('input', function() {
            dataTable.search(this.value).draw();
        });
    });
</script>
</html>