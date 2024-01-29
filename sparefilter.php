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

       // $submittedData = "Submitted Data: star->{$startMonthDate}, end -> {$endMonthDate_Current}, department -> {$_POST['departmentID']}, section -> {$_POST['sectionID']}";
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
         /* ให้ row ใช้ flex display */
         .card-body {
        /* กำหนดความสูงขั้นต่ำหรือความสูง */
        min-height: 65px; 
        padding: 10px; /* ลดขนาดพื้นที่ภายในหากจำเป็น */
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* ช่วยให้เนื้อหากระจายตัวอย่างสม่ำเสมอ */
        }

        .form-control, .form-select {
        height: 35px; /* ลดขนาดความสูงของ input และ select */
        margin-bottom: 0.5rem; /* ปรับขนาดระยะห่างหากจำเป็น */
        padding: 5px 10px; /* ลดขนาดพื้นที่ภายใน input และ select */
        }

        .card-header {
        padding: 5px 10px; /* ลดขนาดพื้นที่ภายในส่วนหัวของ card */
        font-size: 14px; /* ปรับขนาดตัวอักษรของหัวข้อถ้าจำเป็น */
        }
	</style>
    
    <!-- <link rel="stylesheet" type="text/css" href="../../vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="../../src/plugins/jquery-steps/jquery.steps.css">
	<link rel="stylesheet" type="text/css" href="../../vendors/styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <?php include('../analytics/include/header.php'); ?>
  
</head>
<body>
            <!-- เริ่มต้นฟอร์มที่นี่ -->
            <form method="post" action="">
                <div class="container-fluid mt-2 fw-bolder">
                    <div id="search-form">
                        <div class="row">
                            <div class="col-lg-4 mb-2">
                                <div class="card h-40">
                                    <p class="card-header" style="color: #000">เลือกช่วงเดือนเริ่มต้นและสิ้นสุด</p> 
                                    <div class="card-body"> 
                                        <div class="row">
                                            <div class="col">
                                                <input type="month" id="start-month" name="startMonth" class="form-control">
                                                <!-- <label for="start-month" style="font-size: 12px">เดือนและปีเริ่มต้น</label> -->
                                            </div>
                                            <div class="col"> 
                                                <input type="month" id="end-month" name="endMonth" placeholder="From Date" class="form-control">
                                                <!-- <label for="end-month" style="font-size: 12px">เดือนและปีสิ้นสุด</label> -->
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <div class="col-lg-2 mb-2">
                                <div class="card h-40">
                                    <p class="card-header" style="color: #000">Business</p>
                                    <div class="card-body">
                                        <select class="form-select" name="businessID" id="businessID">
                                            <option selected value="">เลือกทั้งหมด</option>
                                            <!-- JavaScript จะเพิ่ม locations ที่นี่ -->
                                        </select>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-2">
                                <div class="card h-40">
                                    <p class="card-header" style="color: #000">Sub-busisness</p>
                                    <div class="card-body">
                                        <select class="form-select" name="sub_businessID" id="sub_businessID">
                                            <option selected value="">เลือกทั้งหมด</option>
                                            <!-- JavaScript จะเพิ่ม locations ที่นี่ -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-2">
                                <div class="card h-40">
                                    <p class="card-header" style="color: #000">Organization</p>
                                    <div class="card-body">
                                        <select class="form-select" name="organizationID" id="organizationID">
                                            <option selected value="">เลือกทั้งหมด</option>
                                            <!-- JavaScript จะเพิ่ม locations ที่นี่ -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-2">
                                <div class="card h-40">
                                    <p class="card-header" style="color: #000">Company</p>
                                    <div class="card-body">
                                        <select class="form-select" name="companyID" id="companyID">
                                            <option selected value="">เลือกทั้งหมด</option>
                                            <!-- JavaScript จะเพิ่ม locations ที่นี่ -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-2">
                                <div class="card h-40">
                                    <p class="card-header" style="color: #000">Location</p>
                                    <div class="card-body">
                                        <select class="form-select" name="locationID" id="locationID">
                                            <option selected value="">เลือกทั้งหมด</option>
                                            <!-- JavaScript จะเพิ่ม locations ที่นี่ -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-2">
                                <div class="card h-40">
                                    <p class="card-header" style="color: #000">Division</p>
                                    <div class="card-body">
                                        <select class="form-select" aria-label="Default select example" name="divisionID" id="divisionID">
                                            <option selected value="">เลือกทั้งหมด</option>
                                            <!-- JavaScript will add divisions here -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-2"> 
                                <div class="card h-40">
                                    <p class="card-header" style="color: #000">Department</p>
                                    <div class="card-body"> 
                                        <select class="form-select" aria-label="Default select example" name="departmentID" id="departmentID">
                                            <option selected value="">เลือกทั้งหมด</option>
                                            <!-- JavaScript add department -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2 mb-2">
                                <div class="card h-40">
                                    <p class="card-header" style="color: #000">Section</p>
                                    <div class="card-body">
                                        <select class="form-select" data-live-search="true"  name="sectionID" id="sectionID">
                                            <option selected value="">เลือกทั้งหมด</option>
                                            <!-- JavaScript add section from-->
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary" style="font-size: 20px; padding: 10px 20px;" name="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- สิ้นสุดฟอร์ม -->

    <?php
            // ตรวจสอบว่ามีข้อมูลที่ส่งมาหลังจากการ submit ฟอร์มหรือไม่
            if (!empty($submittedData)) {
                echo "<p>{$submittedData}</p>";
            }
            ?>
     


    <!-- js -->
    <?php include('../analytics/include/scripts.php') ?>
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // updateBusinesses();
    fetch('get_business.php')
        .then(response => response.json())
        .then(businesses => {
            var businessSelect = document.getElementById('businessID');
            businessSelect.innerHTML = '<option value="">เลือกทั้งหมด</option>';
            businesses.forEach(function(business) {
                businessSelect.innerHTML += '<option value="' + business.business_id + '">' + business.name_eng + '</option>';
            });
            $(businessSelect).selectpicker('refresh');  
        })
        .catch(error => {
            console.error('Error:', error);
        });

    // ตั้งค่า Event Listeners สำหรับ dropdown แต่ละตัว
    document.getElementById('businessID').addEventListener('change', function() {
        updateSubBusinesses(this.value);
    });

    document.getElementById('sub_businessID').addEventListener('change', function() {
        updateOrganizations(this.value);
    });

    document.getElementById('organizationID').addEventListener('change', function() {
        updateCompanies(this.value);
    });

    document.getElementById('companyID').addEventListener('change', function() {
        updateLocations(this.value);
    });

    document.getElementById('locationID').addEventListener('change', function() {
        updateDivisions(this.value);
    });

    document.getElementById('divisionID').addEventListener('change', function() {
        updateDepartments(this.value);
    });

    document.getElementById('departmentID').addEventListener('change', function() {
        updateSections(this.value);
    });

    updateSubBusinesses();
});

// function updateBusinesses() {
//     // ดึงข้อมูล business และปรับปรุง dropdown
// }

function updateSubBusinesses(businessId = '') {
    // ดึงข้อมูล sub-business ตาม business ที่เลือก
    fetch('get_sub_business.php' + (businessId ? '?businessId=' + businessId : ''))
        .then(response => response.json())
        .then(sub_businesses => {
            var sub_businessSelect = document.getElementById('sub_businessID');
            sub_businessSelect.innerHTML = '<option value="">เลือกทั้งหมด</option>';
            sub_businesses.forEach(function(sub_business) {
                sub_businessSelect.innerHTML += '<option value="' + sub_business.sub_business_id + '">' + sub_business.name_eng + '</option>';
            });
            $(sub_businessSelect).selectpicker('refresh'); 
            // หลังจากอัปเดต department ก็โหลด sections ใหม่ตาม department แรก  
            updateOrganizations();
        })
        .catch(error => {   
            console.error('Error:', error);
        });

}

function updateOrganizations(sub_businessId  = '') {
    // ดึงข้อมูล organization ตาม sub-business ที่เลือก
    fetch('get_organization.php' + (sub_businessId ? '?sub_businessId=' + sub_businessId : ''))
        .then(response => response.json())
        .then(organizations => {
            var organizationSelect = document.getElementById('organizationID');
            organizationSelect.innerHTML = '<option value="">เลือกทั้งหมด</option>';
            organizations.forEach(function(organization) {
                organizationSelect.innerHTML += '<option value="' + organization.organization_id + '">' + organization.organization_id + '</option>';
            });
            // หลังจากอัปเดต department ก็โหลด sections ใหม่ตาม department แรก
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
            companys.forEach(function(company) {
                companySelect.innerHTML += '<option value="' + company.company_id + '">' + company.name_eng + '</option>';
            });
            // หลังจากอัปเดต department ก็โหลด sections ใหม่ตาม department แรก
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
            locations.forEach(function(location) {
                locationSelect.innerHTML += '<option value="' + location.location_id + '">' + location.name_eng + '</option>';
            });
            // หลังจากอัปเดต department ก็โหลด sections ใหม่ตาม department แรก
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
            divisions.forEach(function(division) {
                divisionSelect.innerHTML += '<option value="' + division.division_id + '">' + division.name_eng + '</option>';
            });
            // หลังจากอัปเดต department ก็โหลด sections ใหม่ตาม department แรก
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
            departments.forEach(function(department) {
                departmentSelect.innerHTML += '<option value="' + department.department_id + '">' + department.name_eng + '</option>';
            });
            // หลังจากอัปเดต department ก็โหลด sections ใหม่ตาม department แรก
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
            sections.forEach(function(section) {
                sectionSelect.innerHTML += '<option value="' + section.section_id + '">' + section.name_thai + '</option>';
            });
            $(sectionSelect).selectpicker('refresh'); 
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

</script>
</html>
