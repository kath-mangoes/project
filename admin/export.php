<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Include database connection and vendor libraries
include '../connection/config.php';
require_once '../vendor/autoload.php';

// Import necessary libraries
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Mpdf\Mpdf;

// Check if format parameter exists
if (!isset($_GET['format'])) {
    die("Export format not specified");
}

$format = $_GET['format'];
if (!in_array($format, ['pdf', 'excel'])) {
    header("Location: audit_logs.php?error=1");
    exit();
}

// Initialize variables
$search = $_GET['search'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build WHERE clause based on search
$where_conditions = [];
$params = [];
$types = "";

// Get all column names from the tbl_audit table for search
$columnsQuery = "SHOW COLUMNS FROM tbl_audit";
$columnsResult = $conn->query($columnsQuery);
$searchFields = [];

if ($columnsResult) {
    while ($column = $columnsResult->fetch_assoc()) {
        $searchFields[] = "a." . $column['Field'];
    }
}

if (!empty($search) && !empty($searchFields)) {
    $searchConditions = [];
    foreach ($searchFields as $field) {
        $searchConditions[] = "$field LIKE ?";
        $params[] = "%" . $search . "%";
        $types .= "s";
    }
    $where_conditions[] = "(" . implode(" OR ", $searchConditions) . ")";
}

// Add date range filter if provided
if (!empty($date_from) && !empty($date_to)) {
    $where_conditions[] = "(a.dateTimeCreated BETWEEN ? AND ?)";
    $params[] = $date_from . " 00:00:00";
    $params[] = $date_to . " 23:59:59";
    $types .= "ss";
}

// Combine WHERE conditions
$where_clause = !empty($where_conditions) ? implode(" AND ", $where_conditions) : "1=1";

// Fetch all audit logs for export (no limit)
$sql = "SELECT a.audit_id, a.res_id, a.brgyOfficer_id, a.requestType, 
        a.user_id, a.role, a.details, a.processedBy, 
        a.dateTimeCreated, a.status, a.lastEdited
        FROM tbl_audit a
        WHERE $where_clause
        ORDER BY a.dateTimeCreated DESC";

// Prepare and execute statement
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get current date for the filename
$currentDate = date('Y-m-d');

// Process based on format
if ($format === 'pdf') {
    // Create PDF export
    exportPDF($result, $currentDate);
} else {
    // Create Excel export
    exportExcel($result, $currentDate);
}

/**
 * Export data to PDF
 * @param mysqli_result $result
 * @param string $currentDate
 */
function exportPDF($result, $currentDate) {
    // Create new PDF document with landscape orientation for more columns
    $mpdf = new Mpdf([
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 15,
        'margin_bottom' => 15,
        'orientation' => 'L' // Landscape orientation for more columns
    ]);
    
    // Set document information
    $mpdf->SetTitle('Audit Logs Report');
    $mpdf->SetAuthor('Barangay Management System');
    
    // Title page content
    $titlePage = '
    <div style="text-align: center; padding: 20px;">
        <img src="../dist/assets/images/logos.png" style="max-width: 100px; margin-bottom: 20px;">
        <h1 style="margin-bottom: 10px;">Barangay 400 Zone 41</h1>
        <h2>Audit Logs Report</h2>
        <p>Generated on: ' . date('F j, Y, g:i a') . '</p>
    </div>';
    
    $mpdf->WriteHTML($titlePage);
    
    // Add a page break after the title page
    $mpdf->AddPage();
    
    // Build HTML content for data page
    $html = '
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }
        h1 {
            text-align: center;
            font-size: 16pt;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 9pt;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 8pt;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            font-size: 8pt;
            margin-top: 20px;
        }
    </style>
    
    <div class="header">
        <h1>Barangay 400 Audit Logs Information</h1>
        <p>Generated: ' . date('F j, Y, g:i a') . '</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Request Type</th>
                <th>User ID</th>
                <th>Role</th>
                <th>Details</th>
                <th>Processed By</th>
                <th>Date/Time</th>
                <th>Status</th>
                <th>Last Edited</th>
            </tr>
        </thead>
        <tbody>';
    
    // Add data rows
    $counter = 1;
    while ($log = $result->fetch_assoc()) {
        $html .= '<tr>
            <td>' . $counter . '</td>
            <td>' . htmlspecialchars($log['audit_id']) . '</td>
            <td>' . htmlspecialchars($log['requestType']) . '</td>
            <td>' . htmlspecialchars($log['user_id']) . '</td>
            <td>' . htmlspecialchars($log['role']) . '</td>
            <td>' . htmlspecialchars($log['details']) . '</td>
            <td>' . htmlspecialchars($log['processedBy']) . '</td>
            <td>' . htmlspecialchars($log['dateTimeCreated']) . '</td>
            <td>' . htmlspecialchars($log['status']) . '</td>
            <td>' . htmlspecialchars($log['lastEdited']) . '</td>
        </tr>';
        $counter++;
    }
    
    $html .= '
        </tbody>
    </table>
    
    <div class="footer">
        <p>© ' . date('Y') . ' Barangay 400 Zone 41 Sampaloc, Manila - All Rights Reserved</p>
    </div>';
    
    // Write HTML to PDF
    $mpdf->WriteHTML($html);
    
    // Output PDF for download
    $mpdf->Output('Audit_Logs_' . $currentDate . '.pdf', 'D');
    exit;
}

/**
 * Export data to Excel
 * @param mysqli_result $result
 * @param string $currentDate
 */
function exportExcel($result, $currentDate) {
    // Create new spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set document properties
    $spreadsheet->getProperties()
        ->setCreator('Barangay 400 Management System')
        ->setLastModifiedBy('Barangay 400 Management System')
        ->setTitle('Barangay 400 Audit Logs')
        ->setSubject('Barangay 400 Audit Logs Report')
        ->setDescription('Audit logs generated from Barangay Management System');
    
    // Add logo image at the top
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Logo');
    $drawing->setDescription('Barangay 400 Logo');
    $drawing->setPath('../dist/assets/images/logos.png'); // Path to your logo file
    $drawing->setCoordinates('A1');
    $drawing->setHeight(60);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    
    // Set title styles
    $titleStyle = [
        'font' => [
            'bold' => true,
            'size' => 16,
            'color' => ['rgb' => '000000'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
        ],
    ];
    
    $subtitleStyle = [
        'font' => [
            'bold' => true,
            'size' => 12,
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
        ],
    ];
    
    // Add title and subtitle (leaving space for logo)
    $sheet->setCellValue('C1', 'BARANGAY 400 ZONE 41');
    $sheet->mergeCells('C1:K1');
    $sheet->setCellValue('C2', 'AUDIT LOGS REPORT');
    $sheet->mergeCells('C2:K2');
    $sheet->setCellValue('C3', 'Generated on: ' . date('F j, Y, g:i a'));
    $sheet->mergeCells('C3:K3');
    
    // Apply styles to titles
    $sheet->getStyle('C1:K1')->applyFromArray($titleStyle);
    $sheet->getStyle('C2:K2')->applyFromArray($subtitleStyle);
    $sheet->getStyle('C3:K3')->applyFromArray([
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
        ],
    ]);
    
    // Set header row - starting at row 5 to leave space for the logo and title
    $headerRow = 5;
    $sheet->setCellValue('A' . $headerRow, '#');
    $sheet->setCellValue('B' . $headerRow, 'ID');
    $sheet->setCellValue('C' . $headerRow, 'Resident ID');
    $sheet->setCellValue('D' . $headerRow, 'Officer ID');
    $sheet->setCellValue('E' . $headerRow, 'Request Type');
    $sheet->setCellValue('F' . $headerRow, 'User ID');
    $sheet->setCellValue('G' . $headerRow, 'Role');
    $sheet->setCellValue('H' . $headerRow, 'Details');
    $sheet->setCellValue('I' . $headerRow, 'Processed By');
    $sheet->setCellValue('J' . $headerRow, 'Date/Time Created');
    $sheet->setCellValue('K' . $headerRow, 'Status');
    $sheet->setCellValue('L' . $headerRow, 'Last Edited');
    
    // Apply header styling - matching the officials export style
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '2C3E50', // Dark blue color from officials export
            ],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],
        ],
    ];
    
    $sheet->getStyle('A' . $headerRow . ':L' . $headerRow)->applyFromArray($headerStyle);
    
    // Populate data
    $row = $headerRow + 1;
    $counter = 1;
    
    while ($log = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $counter);
        $sheet->setCellValue('B' . $row, $log['audit_id']);
        $sheet->setCellValue('C' . $row, $log['res_id']);
        $sheet->setCellValue('D' . $row, $log['brgyOfficer_id']);
        $sheet->setCellValue('E' . $row, $log['requestType']);
        $sheet->setCellValue('F' . $row, $log['user_id']);
        $sheet->setCellValue('G' . $row, $log['role']);
        $sheet->setCellValue('H' . $row, $log['details']);
        $sheet->setCellValue('I' . $row, $log['processedBy']);
        $sheet->setCellValue('J' . $row, $log['dateTimeCreated']);
        $sheet->setCellValue('K' . $row, $log['status']);
        $sheet->setCellValue('L' . $row, $log['lastEdited']);
        
        $row++;
        $counter++;
    }
    
    // Apply border style to all data cells
    $borderStyle = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC'],
            ],
        ],
    ];
    
    $sheet->getStyle('A' . ($headerRow + 1) . ':L' . ($row - 1))->applyFromArray($borderStyle);
    
    // Add zebra striping for rows
    for ($i = $headerRow + 1; $i < $row; $i++) {
        if ($i % 2 == 0) {
            $sheet->getStyle('A' . $i . ':L' . $i)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('F9F9F9');
        }
    }
    
    // Auto-size columns
    foreach (range('A', 'L') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }
    
    // Freeze the header row
    $sheet->freezePane('A' . ($headerRow + 1));
    
    // Set sheet name
    $sheet->setTitle('Audit Logs');
    
    // Clean output buffer
    if (ob_get_length()) {
        ob_end_clean();
    }
    
    // Set headers for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Audit_Logs_' . $currentDate . '.xlsx"');
    header('Cache-Control: max-age=0');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    
    // Create Xlsx writer and save to output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
?>