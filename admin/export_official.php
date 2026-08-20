<?php
// Initialize session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Include database connection
include '../connection/config.php';
require_once '../vendor/autoload.php';

// Import necessary libraries
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Mpdf\Mpdf;

// Get format from URL parameter
$format = isset($_GET['format']) ? $_GET['format'] : '';
if (!in_array($format, ['pdf', 'excel'])) {
    header("Location: officials.php?error=1");
    exit();
}

// Get search parameter if provided
$search = $_GET['search'] ?? '';

// Build WHERE clause based on search
$where_conditions = [];
$params = [];
$types = "";

if (!empty($search)) {
    $searchValue = "%$search%";
    $where_conditions[] = "(b.first_name LIKE ? OR b.middle_name LIKE ? OR b.last_name LIKE ? OR b.address LIKE ? OR b.mobile LIKE ? OR b.user_id LIKE ?)";
    $params = array_merge($params, [$searchValue, $searchValue, $searchValue, $searchValue, $searchValue, $searchValue]);
    $types .= "ssssss";
}

// Combine WHERE conditions
$where_clause = !empty($where_conditions) ? implode(" AND ", $where_conditions) : "1=1";

// Fetch data query - Using status from tbl_brgyofficer
$sql = "SELECT b.*, u.email
        FROM tbl_brgyofficer b 
        JOIN tbl_user u ON b.user_id = u.user_id 
        WHERE $where_clause 
        ORDER BY b.last_name ASC";

// Prepare and execute statement
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

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
    // Create new PDF document
    $mpdf = new Mpdf([
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 15,
        'margin_bottom' => 15,
        'orientation' => 'L' // Landscape orientation for more columns
    ]);
    
    // Set document information
    $mpdf->SetTitle('Barangay Officials List');
    $mpdf->SetAuthor('Barangay Management System');
    
    // Title page content
    $titlePage = '
    <div style="text-align: center; padding: 20px;">
        <img src="../dist/assets/images/logos.png" style="max-width: 100px; margin-bottom: 20px;">
        <h1 style="margin-bottom: 10px;">Barangay 400 Zone 41</h1>
        <h2>Officials Information Report</h2>
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
        <h1>Barangay 400 Officials Information</h1>
        <p>Generated: ' . date('F j, Y, g:i a') . '</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Position</th>
                <th>Email</th>
                <th>Mobile No.</th>
                <th>Address</th>
                <th>Username</th>
                <th>Status</th>
                <th>Date Added</th>
            </tr>
        </thead>
        <tbody>';
    
    // Add data rows
    $counter = 1;
    while ($official = $result->fetch_assoc()) {
        $fullName = $official['last_name'] . ', ' . $official['first_name'] . ' ' . 
                   ($official['middle_name'] ? substr($official['middle_name'], 0, 1) . '.' : '');
        
        // FIXED: Status is a VARCHAR field, so compare with string values
        // Pass through the status value directly since it already has "Active" or "Inactive"
        $status = $official['status'];
    
        $html .= '<tr>
            <td>' . $counter . '</td>
            <td>' . htmlspecialchars($fullName) . '</td>
            <td>' . htmlspecialchars($official['position']) . '</td>
            <td>' . htmlspecialchars($official['email']) . '</td>
            <td>' . htmlspecialchars($official['mobile']) . '</td>
            <td>' . htmlspecialchars($official['address']) . '</td>
            <td>' . htmlspecialchars($official['username']) . '</td>
            <td>' . htmlspecialchars($status) . '</td>
            <td>' . htmlspecialchars($official['created_at']) . '</td>
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
    $mpdf->Output('Barangay_Officials_' . $currentDate . '.pdf', 'D');
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
        ->setTitle('Barangay 400 Officials')
        ->setSubject('Barangay 400 Officials Report')
        ->setDescription('Officials information generated from Barangay Management System');
    
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
    $sheet->setCellValue('C2', 'OFFICIALS INFORMATION REPORT');
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
    $sheet->setCellValue('C' . $headerRow, 'Last Name');
    $sheet->setCellValue('D' . $headerRow, 'First Name');
    $sheet->setCellValue('E' . $headerRow, 'Middle Name');
    $sheet->setCellValue('F' . $headerRow, 'Position');
    $sheet->setCellValue('G' . $headerRow, 'Email');
    $sheet->setCellValue('H' . $headerRow, 'Mobile No.');
    $sheet->setCellValue('I' . $headerRow, 'Address');
    $sheet->setCellValue('J' . $headerRow, 'Username');
    $sheet->setCellValue('K' . $headerRow, 'Status');
    $sheet->setCellValue('L' . $headerRow, 'Date Added');
    
    // Apply header styling - matching the resident export style
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
                'rgb' => '2C3E50', // Dark blue color from resident export
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
    
    while ($official = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $counter);
        $sheet->setCellValue('B' . $row, $official['brgyOfficer_id']);
        $sheet->setCellValue('C' . $row, $official['last_name']);
        $sheet->setCellValue('D' . $row, $official['first_name']);
        $sheet->setCellValue('E' . $row, $official['middle_name']);
        $sheet->setCellValue('F' . $row, $official['position']);
        $sheet->setCellValue('G' . $row, $official['email']);
        $sheet->setCellValue('H' . $row, $official['mobile']);
        $sheet->setCellValue('I' . $row, $official['address']);
        $sheet->setCellValue('J' . $row, $official['username']);
        
        // FIXED: Status is a VARCHAR field, so use it directly
        $sheet->setCellValue('K' . $row, $official['status']);
        
        $sheet->setCellValue('L' . $row, $official['created_at']);
        
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
    $sheet->setTitle('Officials');
    
    // Clean output buffer
    if (ob_get_length()) {
        ob_end_clean();
    }
    
    // Set headers for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Barangay_Officials_' . $currentDate . '.xlsx"');
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