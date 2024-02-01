<?php
//session_start(); // เรียกใช้ session_start() ก่อนที่จะใช้ session
// require_once('C:\xampp\htdocs\dashboard analytics\config\connection.php');
require_once('../../config/connection.php');
$submittedData = '';
$currentYear = date('Y'); // ปีปัจจุบัน
$currentMonth = date('m'); // เดือนปัจจุบัน
$defaultEndMonth = $currentYear . '-12'; // ตั้งค่าเดือนเริ่มต้นเป็นธันวาคม

if (isset($_POST['submit'])) {
    $departmentId = $_POST['departmentID'] ?? null;
    $sectionId = $_POST['sectionID'] ?? null;

    // $receivedStartMonth = $_POST['startMonth'] ?? $currentYear. '-01' ; // ถ้าไม่มีค่าส่งมา ก็ใช้เดือนมกราคมของปีปัจจุบัน
    $receivedStartMonth = isset($_POST['startMonth']) && $_POST['startMonth'] ? $_POST['startMonth'] : $currentYear . '-01';
    // $receivedEndMonth_December = $_POST['endMonth'] ?? $defaultEndMonth; // ถ้าไม่มีค่าส่งมา ก็ใช้เดือนธันวาคมของปีปัจจุบัน
    $receivedEndMonth_December = isset($_POST['endMonth']) && $_POST['endMonth'] ? $_POST['endMonth'] : $currentYear . '-12';
    // $receivedEndMonth_Current = $_POST['endMonth'] ?? $currentYear . '-' . $currentMonth; // ถ้าไม่มีค่าส่งมา ก็ใช้เดือนปัจจุบัน
    $receivedEndMonth_Current = isset($_POST['endMonth']) && $_POST['endMonth'] ? $_POST['endMonth'] : $currentYear . '-' . $currentMonth;


    // กรณี 1: สร้างวันที่แบบ YYYY-MM-DD
    $startMonthDate = $receivedStartMonth . '-01'; // ตัวอย่าง: 2024-01-01
    $endMonthDate_December = date('Y-m-t', strtotime($receivedEndMonth_December));
    $endMonthDate_Current = date('Y-m-t', strtotime($receivedEndMonth_Current));

    // กรณี 2: แยกค่าปีและเดือน
    $startYear = date('Y', strtotime($receivedStartMonth));
    $startMonth = date('m', strtotime($receivedStartMonth));
    $endYearDecember = date('Y', strtotime($receivedEndMonth_December));
    $endMonthDecember = date('m', strtotime($receivedEndMonth_December));
    $endYearCurrent = date('Y', strtotime($receivedEndMonth_Current));
    $endMonthCurrent = date('m', strtotime($receivedEndMonth_Current));

    //$submittedData = "Submitted Data: start -> $startMonthDate , End -> $endMonthDate_Current";
    // เก็บข้อมูลไว้ใน session
    $_SESSION['filter'] = [
        'startMonthDate' => $startMonthDate,
        'endMonthDateDecember' => $endMonthDate_December,
        'endMonthDateCurrent' => $endMonthDate_Current,
        'startYear' => $startYear,
        'startMonth' => $startMonth,
        'endYearDecember' => $endYearDecember,
        'endMonthDecember' => $endMonthDecember,
        'endYearCurrent' => $endYearCurrent,
        'endMonthCurrent' => $endMonthCurrent,
        'businessId' => $_POST['businessID'] ?? null,
        'sub_businessId' => $_POST['sub_businessID'] ?? null,
        'organizationId' => $_POST['organizationID'] ?? null,
        'companyId' => $_POST['companyID'] ?? null,
        'locationId' => $_POST['locationID'] ?? null,
        'divisionId' => $_POST['divisionID'] ?? null,
        'departmentId' => $_POST['departmentID'] ?? null,
        'sectionId' => $_POST['sectionID'] ?? null
    ];
}
?>

<!DOCTYPE html>
<html>

<head>

    <style>
        .card-body-month {
            min-height: 65px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .form-control,
        .form-select {
            height: 35px;
            margin-bottom: 0.5rem;
            padding: 5px 10px;
        }

        .card-header-month {
            padding: 5px 10px;
            font-size: 14px;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .form-label {
            flex-basis: 20%;
            text-align: right;
            margin-right: 10px;
        }

        .form-control-container, .form-control-container-month {
            flex-basis: 75%;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .custom-select, .form-control {
            width: 100%;
        }

        .submit-button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
    </style>
    <?php include('../analytics/include/header.php') ?>

</head>

<body>

    <form method="post" action="">
        <section>
            <div class="from-row">
                <p class="from-label">เลือกช่วงเดือนเริ่มต้น:</p>
                <div class="form-control-container-month">
                    <div class="form-group">
                        <input type="month" id="start-month" name="startMonth" class="custom-select form-control" placeholder="From Date">
                    </div>
                </div>
            </div>

            <div class="from-row">
                <p class="from-label">เลือกช่วงเดือนสิ้นสุด:</p>
                <div class="form-control-container-month">
                    <div>
                        <input type="month" id="end-month" name="endMonth" class="custom-select form-control" placeholder="To Date">
                    </div>
                </div>
            </div>

            <div class="from-row">
                <div class="from-label">
                    Business:
                </div>
                <div class="form-control-container">
                    <div class="form-group">
                        <select id="businessID" name="businessID" class="custom-select form-control"
                            aria-label="Default select example" autocomplete="off" data-live-search="true">
                            <option value="" selected>All</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="from-row">
                <div class="from-label">
                    Sub-busisness:
                </div>
                <div class="form-control-container">
                    <div class="form-group">
                        <select id="sub_businessID" name="sub_businessID" class="custom-select form-control"
                            aria-label="Default select example" autocomplete="off" data-live-search="true">
                            <option value="" selected>All</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="from-row">
                <div class="from-label">
                    Organization:
                </div>
                <div class="form-control-container">
                    <div class="form-group">
                        <select id="organizationID" name="organizationID" class="custom-select form-control"
                            aria-label="Default select example" autocomplete="off" data-live-search="true">
                            <option value="" selected>All</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="from-row">
                <div class="from-label">
                    Company:
                </div>
                <div class="form-control-container">
                    <div class="form-group">
                        <select id="companyID" name="companyID" class="custom-select form-control"
                            aria-label="Default select example" autocomplete="off" data-live-search="true">
                            <option value="" selected>All</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="from-row">
                <div class="from-label">
                    Location:
                </div>
                <div class="form-control-container">
                    <div class="form-group">
                        <select id="locationID" name="locationID" class="custom-select form-control"
                            aria-label="Default select example" autocomplete="off" data-live-search="true">
                            <option value="" selected>All</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="from-row">
                <div class="from-label">
                    Division:
                </div>
                <div class="form-control-container">
                    <div class="form-group">
                        <select id="divisionID" name="divisionID" class="custom-select form-control"
                            aria-label="Default select example" autocomplete="off" data-live-search="true">
                            <option value="" selected>All</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="from-row">
                <div class="from-label">
                    Department:
                </div>
                <div class="form-control-container">
                    <div class="form-group">
                        <select id="departmentID" name="departmentID" class="custom-select form-control"
                            aria-label="Default select example" autocomplete="off" data-live-search="true">
                            <option value="" selected>All</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="from-row">
                <div class="from-label">
                    Section:
                </div>
                <div class="form-control-container">
                    <div class="form-group">
                        <select id="sectionID" name="sectionID" class="custom-select form-control"
                            aria-label="Default select example" autocomplete="off" data-live-search="true">
                            <option value="" selected>All</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="submit-button-container">
                <div class="row">
                    <button type="submit" class="btn btn-primary" style="font-size: 20px; padding: 10px 20px;"
                        name="submit">Submit</button>
                </div>
            </div>

        </section>
    </form>


    <?php
    if (!empty($submittedData)) {
        echo "<p>{$submittedData}</p>";
    }
    ?>



    <?php include('../analytics/include/scripts.php') ?>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // updateBusinesses();
        fetch('get_business.php')
            .then(response => response.json())
            .then(businesses => {
                var businessSelect = document.getElementById('businessID');
                businessSelect.innerHTML = '<option value="">เลือกทั้งหมด</option>';
                businesses.forEach(function (business) {
                    businessSelect.innerHTML += '<option value="' + business.business_id + '">' + business.name_eng + '</option>';
                });
                $(businessSelect).selectpicker('refresh');
            })
            .catch(error => {
                console.error('Error:', error);
            });

        // ตั้งค่า Event Listeners สำหรับ dropdown แต่ละตัว
        document.getElementById('businessID').addEventListener('change', function () {
            updateSubBusinesses(this.value);
        });

        document.getElementById('sub_businessID').addEventListener('change', function () {
            updateOrganizations(this.value);
        });

        document.getElementById('organizationID').addEventListener('change', function () {
            updateCompanies(this.value);
        });

        document.getElementById('companyID').addEventListener('change', function () {
            updateLocations(this.value);
        });

        document.getElementById('locationID').addEventListener('change', function () {
            updateDivisions(this.value);
        });

        document.getElementById('divisionID').addEventListener('change', function () {
            updateDepartments(this.value);
        });

        document.getElementById('departmentID').addEventListener('change', function () {
            updateSections(this.value);
        });

        updateSubBusinesses();
    });


    function updateSubBusinesses(businessId = '') {
        // ดึงข้อมูล sub-business ตาม business ที่เลือก
        fetch('get_sub_business.php' + (businessId ? '?businessId=' + businessId : ''))
            .then(response => response.json())
            .then(sub_businesses => {
                var sub_businessSelect = document.getElementById('sub_businessID');
                sub_businessSelect.innerHTML = '<option value="">เลือกทั้งหมด</option>';
                sub_businesses.forEach(function (sub_business) {
                    sub_businessSelect.innerHTML += '<option value="' + sub_business.sub_business_id + '">' + sub_business.name_eng + '</option>';
                });
                $(sub_businessSelect).selectpicker('refresh');
                // หลังจากอัปเดต business ก็โหลด sub-business ใหม่ตาม business แรก  
                updateOrganizations();
            })
            .catch(error => {
                console.error('Error:', error);
            });

    }

    function updateOrganizations(sub_businessId = '') {
        // ดึงข้อมูล organization ตาม sub-business ที่เลือก
        fetch('get_organization.php' + (sub_businessId ? '?sub_businessId=' + sub_businessId : ''))
            .then(response => response.json())
            .then(organizations => {
                var organizationSelect = document.getElementById('organizationID');
                organizationSelect.innerHTML = '<option value="">เลือกทั้งหมด</option>';
                organizations.forEach(function (organization) {
                    organizationSelect.innerHTML += '<option value="' + organization.organization_id + '">' + organization.organization_id + '</option>';
                });
                // หลังจากอัปเดต sub-business ก็โหลด organization ใหม่ตาม sub-business แรก
                $(organizationSelect).selectpicker('refresh');
                updateCompanies();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function updateCompanies(organizationId = '') {
        // ดึงข้อมูล company ตาม organization ที่เลือก
        fetch('get_company.php' + (organizationId ? '?organizationId=' + organizationId : ''))
            .then(response => response.json())
            .then(companys => {
                var companySelect = document.getElementById('companyID');
                companySelect.innerHTML = '<option value="">เลือกทั้งหมด</option>';
                companys.forEach(function (company) {
                    companySelect.innerHTML += '<option value="' + company.company_id + '">' + company.name_eng + '</option>';
                });
                // หลังจากอัปเดต organization ก็โหลด company ใหม่ตาม organization แรก
                $(companySelect).selectpicker('refresh');
                updateLocations();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function updateLocations(companyId = '') {
        // ดึงข้อมูล location ตาม company ที่เลือก
        fetch('get_location.php' + (companyId ? '?companyId=' + companyId : ''))
            .then(response => response.json())
            .then(locations => {
                var locationSelect = document.getElementById('locationID');
                locationSelect.innerHTML = '<option value="">เลือกทั้งหมด</option>';
                locations.forEach(function (location) {
                    locationSelect.innerHTML += '<option value="' + location.location_id + '">' + location.name_eng + '</option>';
                });
                // หลังจากอัปเดต company ก็โหลด location ใหม่ตาม company แรก
                $(locationSelect).selectpicker('refresh');
                updateDivisions();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function updateDivisions(locationId = '') {
        // ดึงข้อมูล division ตาม location ที่เลือก
        fetch('get_division.php' + (locationId ? '?locationId=' + locationId : ''))
            .then(response => response.json())
            .then(divisions => {
                var divisionSelect = document.getElementById('divisionID');
                divisionSelect.innerHTML = '<option value="">เลือกทั้งหมด</option>';
                divisions.forEach(function (division) {
                    divisionSelect.innerHTML += '<option value="' + division.division_id + '">' + division.name_eng + '</option>';
                });
                // หลังจากอัปเดต locztion ก็โหลด division ใหม่ตาม location แรก
                $(divisionSelect).selectpicker('refresh');
                updateDepartments();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function updateDepartments(divisionId = '') {
        fetch('get_department.php' + (divisionId ? '?divisionId=' + divisionId : ''))
            .then(response => response.json())
            .then(departments => {
                var departmentSelect = document.getElementById('departmentID');
                departmentSelect.innerHTML = '<option value="">เลือกทั้งหมด</option>';
                departments.forEach(function (department) {
                    departmentSelect.innerHTML += '<option value="' + department.department_id + '">' + department.name_eng + '</option>';
                });
                // หลังจากอัปเดต division ก็โหลด department ใหม่ตาม division แรก
                $(departmentSelect).selectpicker('refresh');
                updateSections();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function updateSections(departmentId = '') {
        fetch('get_section.php' + (departmentId ? '?departmentId=' + departmentId : ''))
            .then(response => response.json())
            .then(sections => {
                var sectionSelect = document.getElementById('sectionID');
                sectionSelect.innerHTML = '<option value="">เลือกทั้งหมด</option>';
                sections.forEach(function (section) {
                    sectionSelect.innerHTML += '<option value="' + section.section_id + '">' + section.name_thai + '</option>';
                });
                // หลังจากอัปเดต department ก็โหลด section ใหม่ตาม department แรก
                $(sectionSelect).selectpicker('refresh');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

</script>

</html>