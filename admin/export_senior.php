<?php
// Initialize session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in (optional - depending on your security requirements)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Include database connection
require_once '../connection/config.php';

// Include required libraries
require_once '../vendor/autoload.php';

// Set up namespaces for PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Check if format parameter exists
if (!isset($_GET['format']) || ($_GET['format'] != 'pdf' && $_GET['format'] != 'excel')) {
    header("Location: samplebutton.php?error=1");
    exit();
}

$format = $_GET['format'];
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build WHERE clause based on search
$where_conditions = [];
$params = [];
$types = "";

if (!empty($search)) {
    $searchValue = "%$search%";
    $where_conditions[] = "(r.first_name LIKE ? OR r.middle_name LIKE ? OR r.last_name LIKE ? OR r.address LIKE ? OR r.mobile LIKE ? OR r.user_id LIKE ?)";
    $params = array_merge($params, [$searchValue, $searchValue, $searchValue, $searchValue, $searchValue, $searchValue]);
    $types .= "ssssss";
}

// Combine WHERE conditions
$where_clause = !empty($where_conditions) ? implode(" AND ", $where_conditions) : "1=1";

// SQL query to fetch only Senior residents
$where_clause = "TIMESTAMPDIFF(YEAR, r.birthday, CURDATE()) >= 60";

$sql = "SELECT r.res_id, r.user_id, r.first_name, r.middle_name, r.last_name, 
               r.birthday, r.birthplace, r.civilStatus, r.mobile, r.gender, 
               r.address, r.precinctNumber, r.residentStatus, r.is_registered_voter, 
               r.bloodType, r.height, r.weight, r.typeOfID, r.IDNumber, 
               r.SSSGSIS_Number, r.TIN_number, r.barangay_number, 
               r.is_senior, u.email, u.account_status 
        FROM tbl_residents r 
        JOIN tbl_user u ON r.user_id = u.user_id 
        WHERE $where_clause 
        ORDER BY r.last_name ASC";

// Prepare and execute statement
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Get current date for filename
$currentDate = date('Y-m-d');

// Process based on format
if ($format == 'pdf') {
    // Create PDF export
    exportPDF($result, $currentDate);
} else {
    // Create Excel export (optional)
    exportExcel($result, $currentDate);
}

/**
 * Export data to PDF
 * @param mysqli_result $result
 * @param string $currentDate
 */
function exportPDF($result, $currentDate) {
    // Set up mPDF
    $mpdf = new \Mpdf\Mpdf([
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 15,
        'margin_bottom' => 15,
        'orientation' => 'L' // Landscape orientation
    ]);

    // Add a title page
    $mpdf->AddPage();
    $mpdf->SetTitle('Senior Citizens Residents Report');
    $mpdf->SetAuthor('Barangay 400 Management System');

    $titlePage = '
    <div style="text-align: center; padding: 20px;">
        <img src="../dist/assets/images/logos.png" style="max-width: 100px; margin-bottom: 20px;">
        <h1 style="margin-bottom: 10px;">Barangay 400 Zone 41</h1>
        <h2>Snior Citizens Residents Information Report</h2>
        <p>Generated on: ' . date('F j, Y, g:i a') . '</p>
    </div>';

    $mpdf->WriteHTML($titlePage);

    // Start the actual report
    $mpdf->AddPage();

    $html = '
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; font-size: 9pt; }
        th { background-color: #f2f2f2; text-align: left; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        h1 { text-align: center; font-size: 16pt; margin-bottom: 15px; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { text-align: center; font-size: 8pt; margin-top: 20px; }
    </style>

    <div class="header">
        <h1>PWD Residents of Barangay 400</h1>
        <p>Generated: ' . date('F j, Y, g:i a') . '</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Birthday</th>
                <th>Civil Status</th>
                <th>Address</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Status</th>
                <th>Voter</th>
                <th>Senior</th>
                <th>PWD</th>
            </tr>
        </thead>
        <tbody>';

    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        $fullName = $row['last_name'] . ', ' . $row['first_name'] . ' ' . 
                   ($row['middle_name'] ? substr($row['middle_name'], 0, 1) . '.' : '');

        $html .= '<tr>
            <td>' . $counter . '</td>
            <td>' . htmlspecialchars($fullName) . '</td>
            <td>' . htmlspecialchars($row['gender']) . '</td>
            <td>' . date('M d, Y', strtotime($row['birthday'])) . '</td>
            <td>' . htmlspecialchars($row['civilStatus']) . '</td>
            <td>' . htmlspecialchars($row['address']) . '</td>
            <td>' . htmlspecialchars($row['mobile']) . '</td>
            <td>' . htmlspecialchars($row['email']) . '</td>
            <td>' . htmlspecialchars($row['residentStatus']) . '</td>
            <td>' . ($row['is_registered_voter'] == 'Yes' ? 'Yes' : 'No') . '</td>
            <td>' . ($row['is_senior'] == 'Yes' ? 'Yes' : 'No') . '</td>
        </tr>';
        $counter++;
    }

    $html .= '</tbody>
    </table>

    <div class="footer">
        <p>© ' . date('Y') . ' Barangay 400 Zone 41 Sampaloc, Manila - All Rights Reserved</p>
    </div>';

    $mpdf->WriteHTML($html);

    // Output PDF
    $filename = 'Barangay400_PWD_Residents_' . $currentDate . '.pdf';
    $mpdf->Output($filename, 'D');
    exit;
}


/**
 * Export data to Excel
 * @param mysqli_result $result
 * @param string $currentDate
 */
function exportExcel($result, $currentDate) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set document properties
    $spreadsheet->getProperties()
        ->setCreator('Barangay 400 Management System')
        ->setLastModifiedBy('Barangay 400 Management System')
        ->setTitle('Senior Citizens Residents')
        ->setSubject('Senior Citizens Residents Report')
        ->setDescription('Senior Citizens Residents information from Barangay 400 Management System');

    // Add logo image
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Logo');
    $drawing->setDescription('Barangay 400 Logo');
    $drawing->setPath('../dist/assets/images/logos.png');
    $drawing->setCoordinates('A1');
    $drawing->setHeight(60);
    $drawing->setWorksheet($sheet);

    // Title and subtitle
    $sheet->setCellValue('C1', 'BARANGAY 400 ZONE 41');
    $sheet->mergeCells('C1:K1');
    $sheet->setCellValue('C2', 'Senior Citizens RESIDENTS INFORMATION REPORT');
    $sheet->mergeCells('C2:K2');
    $sheet->setCellValue('C3', 'Generated on: ' . date('F j, Y, g:i a'));
    $sheet->mergeCells('C3:K3');

    // Style
    $sheet->getStyle('C1:K1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 16],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
    ]);
    $sheet->getStyle('C2:K2')->applyFromArray([
        'font' => ['bold' => true, 'size' => 12],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
    ]);
    $sheet->getStyle('C3:K3')->applyFromArray([
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
    ]);

    // Header row
    $headerRow = 5;
    $headers = [
        'A' => '#', 'B' => 'User ID', 'C' => 'Last Name', 'D' => 'First Name',
        'E' => 'Middle Name', 'F' => 'Gender', 'G' => 'Birthday', 'H' => 'Age',
        'I' => 'Marital Status', 'J' => 'Mobile', 'K' => 'Email', 'L' => 'Address',
        'M' => 'Resident Status', 'N' => 'Voter', 'O' => 'Blood Type', 'P' => 'Height', 'Q' => 'Weight'
    ];

    foreach ($headers as $col => $text) {
        $sheet->setCellValue($col . $headerRow, $text);
    }

    $sheet->getStyle('A' . $headerRow . ':Q' . $headerRow)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2C3E50']],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);

    // Populate rows
    $row = $headerRow + 1;
    $counter = 1;

    while ($data = $result->fetch_assoc()) {
        $birthDate = new DateTime($data['birthday']);
        $today = new DateTime();
        $age = $birthDate->diff($today)->y;

        $sheet->setCellValue('A' . $row, $counter);
        $sheet->setCellValue('B' . $row, $data['user_id']);
        $sheet->setCellValue('C' . $row, $data['last_name']);
        $sheet->setCellValue('D' . $row, $data['first_name']);
        $sheet->setCellValue('E' . $row, $data['middle_name']);
        $sheet->setCellValue('F' . $row, $data['gender']);
        $sheet->setCellValue('G' . $row, date('m/d/Y', strtotime($data['birthday'])));
        $sheet->setCellValue('H' . $row, $age);
        $sheet->setCellValue('I' . $row, $data['civilStatus']);
        $sheet->setCellValue('J' . $row, $data['mobile']);
        $sheet->setCellValue('K' . $row, $data['email']);
        $sheet->setCellValue('L' . $row, $data['address']);
        $sheet->setCellValue('M' . $row, $data['residentStatus']);
        $sheet->setCellValue('N' . $row, $data['is_registered_voter']);
        $sheet->setCellValue('O' . $row, $data['bloodType']);
        $sheet->setCellValue('P' . $row, $data['height']);
        $sheet->setCellValue('Q' . $row, $data['weight']);


        $row++;
        $counter++;
    }

    // Borders
    $sheet->getStyle('A' . ($headerRow + 1) . ':Q' . ($row - 1))->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);

    // Auto-size
    foreach (range('A', 'V') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $sheet->freezePane('A' . ($headerRow + 1));
    $sheet->setTitle('Senior Citizens Residents');

    // Output
    $filename = 'Barangay400_Senior_Citizens_Residents_' . $currentDate . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
