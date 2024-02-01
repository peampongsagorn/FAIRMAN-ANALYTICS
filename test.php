<?php
session_start();
require_once('../../config/connection.php');

$currentYear = date('Y');
$filterData = $_SESSION['filter'] ?? null;
$currentYear = date('Y'); // ปีปัจจุบัน
$startYear = $currentYear . '-01-01'; // วันที่ 1 มกราคมของปีปัจจุบัน
$currentDate = date('Y-m-d'); // วันที่ปัจจุบัน

$sqlConditions_actual = "date_start BETWEEN '{$startYear}' AND '{$currentDate}'";

if ($filterData) {

    if (!empty($filterData['startMonthDate']) && !empty($filterData['endMonthDateCurrent'])) {
        $sqlConditions_actual = "date_start BETWEEN '{$filterData['startMonthDate']}' AND '{$filterData['endMonthDateCurrent']}'";
        echo $sqlConditions_actual;
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
		otr.card_id AS CARD_ID,
        otr.date_start AS DATE_OT,
        otr.attendance_hours AS SUM_HOURS,
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
            department as d ON s.department_id = d.department_id
        INNER JOIN 
            division as dv ON d.division_id = dv.division_id
        INNER JOIN
            location as l ON dv.location_id = l.location_id
        INNER JOIN
            company as c ON l.company_id = c.company_id
        INNER JOIN
            organization as o ON c.organization_id = o.organization_id
        INNER JOIN
            sub_business as sb ON o.sub_business_id = sb.sub_business_id
        INNER JOIN
            business as b on sb.business_id = b.business_id
        WHERE 
                {$sqlConditions_actual}
        GROUP BY 
            otr.card_id,
            otr.date_start,
            otr.attendance_hours,
            ott.type_fix_nonfix,
            d.name_thai,
            s.name_thai
                ";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$ActualOtData = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $card = $row['CARD_ID'];
    $date = $row['DATE_OT']->format('Y-m-d');
    $department = $row['DEPARTMENT'];
    $section = $row['SECTION'];
    $type = $row['TYPE_OT'];
    $hours = $row['SUM_HOURS'];
}
    //ตรวจสอบและสร้าง array ถ้ายังไม่มี
    if (!isset($ActualOtData[$card])) {
        $ActualOtData[$card] = [];
    }
    if (!isset($ActualOtData[$card][$date])) {
        $ActualOtData[$card][$date] = [];
    }
    if (!isset($ActualOtData[$card][$date][$department])) {
        $ActualOtData[$card][$date][$department] = [];
    }
    if (!isset($ActualOtData[$card][$date][$department][$section])) {
        $ActualOtData[$card][$date][$department][$section] = ['FIX' => 0, 'NONFIX' => 0];
    }
    if ($type === 'FIX') {
        $ActualOtData[$card][$date][$department][$section]['FIX'] = $hours;
    } else if ($type === 'NONFIX') {
        $ActualOtData[$card][$date][$department][$section]['NONFIX'] = $hours;
    }


// ตรวจสอบว่ามีข้อมูลใน $ActualOtData หรือไม่
if (!empty($ActualOtData)) {
    echo '<table border="1">';
    echo '<tr>';
    echo '<th>Card ID</th>';
    echo '<th>Date</th>';
    echo '<th>Department</th>';
    echo '<th>Section</th>';
    echo '<th>FIX Hours</th>';
    echo '<th>NONFIX Hours</th>';
    echo '</tr>';

    foreach ($ActualOtData as $card => $dates) {
        foreach($dates as $date => $departments) {
            foreach ($departments as $department => $sections) {
                foreach ($sections as $section => $hours) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($card) . '</td>';
                    echo '<td>' . htmlspecialchars($date) . '</td>';
                    echo '<td>' . htmlspecialchars($department) . '</td>';
                    echo '<td>' . htmlspecialchars($section) . '</td>';
                    echo '<td>' . htmlspecialchars($hours['FIX']) . '</td>';
                    echo '<td>' . htmlspecialchars($hours['NONFIX']) . '</td>';
                    echo '</tr>';
                }
            }
        }
    }   

    echo '</table>';

} else {
    echo 'No data available.';
}
?>
