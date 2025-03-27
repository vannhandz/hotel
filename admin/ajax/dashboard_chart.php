<?php
require('../inc/db_config.php');
require('../inc/esentials.php');
adminLogin();

header('Content-Type: application/json');

// Get period from request
$period = isset($_GET['period']) ? $_GET['period'] : 1;

// Set date range based on period
$start_date = '';
$end_date = date('Y-m-d');

switch ($period) {
    case 1: // 30 days
        $start_date = date('Y-m-d', strtotime('-30 days'));
        $labels = generateDateLabels($start_date, $end_date, 'daily');
        break;
    case 2: // 90 days
        $start_date = date('Y-m-d', strtotime('-90 days'));
        $labels = generateDateLabels($start_date, $end_date, 'weekly');
        break;
    case 3: // 1 year
        $start_date = date('Y-m-d', strtotime('-1 year'));
        $labels = generateDateLabels($start_date, $end_date, 'monthly');
        break;
    default: // 30 days default
        $start_date = date('Y-m-d', strtotime('-30 days'));
        $labels = generateDateLabels($start_date, $end_date, 'daily');
}

// Initialize empty data arrays for each label
$bookings_data = array_fill(0, count($labels), 0);
$cancellations_data = array_fill(0, count($labels), 0);

// Query for bookings data based on period
if ($period == 3) { // Monthly data
    // Get bookings by month
    $booking_query = "SELECT 
        MONTH(booking_date) as month, 
        YEAR(booking_date) as year,
        COUNT(*) as bookings_count 
        FROM `booking_order` 
        WHERE booking_status='booked' AND booking_date BETWEEN ? AND ?
        GROUP BY YEAR(booking_date), MONTH(booking_date)
        ORDER BY year, month";
        
    $cancel_query = "SELECT 
        MONTH(booking_date) as month, 
        YEAR(booking_date) as year,
        COUNT(*) as cancel_count 
        FROM `booking_order` 
        WHERE booking_status='cancelled' AND booking_date BETWEEN ? AND ?
        GROUP BY YEAR(booking_date), MONTH(booking_date)
        ORDER BY year, month";
        
    // Process booking data
    $stmt = mysqli_prepare($con, $booking_query);
    mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $month_year = date('M', mktime(0, 0, 0, $row['month'], 1, $row['year']));
        $index = array_search($month_year, $labels);
        if ($index !== false) {
            $bookings_data[$index] = $row['bookings_count'];
        }
    }
    
    // Process cancellation data
    $stmt = mysqli_prepare($con, $cancel_query);
    mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $month_year = date('M', mktime(0, 0, 0, $row['month'], 1, $row['year']));
        $index = array_search($month_year, $labels);
        if ($index !== false) {
            $cancellations_data[$index] = $row['cancel_count'];
        }
    }
} 
else if ($period == 2) { // Weekly data
    // For weekly data, use the helper function to generate date ranges
    $week_ranges = generateWeekRanges($start_date, $end_date);
    
    // Process each week range
    foreach ($week_ranges as $index => $range) {
        // Query bookings for this week range
        $booking_query = "SELECT COUNT(*) as bookings_count 
                        FROM `booking_order` 
                        WHERE booking_status='booked' 
                        AND booking_date BETWEEN ? AND ?";
        
        $stmt = mysqli_prepare($con, $booking_query);
        mysqli_stmt_bind_param($stmt, "ss", $range['start_date'], $range['end_date']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $bookings_data[$index] = $row['bookings_count'];
        }
        
        // Query cancellations for this week range
        $cancel_query = "SELECT COUNT(*) as cancel_count 
                        FROM `booking_order` 
                        WHERE booking_status='cancelled' 
                        AND booking_date BETWEEN ? AND ?";
        
        $stmt = mysqli_prepare($con, $cancel_query);
        mysqli_stmt_bind_param($stmt, "ss", $range['start_date'], $range['end_date']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $cancellations_data[$index] = $row['cancel_count'];
        }
    }
}
else { // Daily data
    // Query bookings for each day
    $booking_query = "SELECT 
        DATE(booking_date) as book_date,
        COUNT(*) as bookings_count 
        FROM `booking_order` 
        WHERE booking_status='booked' AND booking_date BETWEEN ? AND ?
        GROUP BY DATE(booking_date)
        ORDER BY book_date";
        
    $stmt = mysqli_prepare($con, $booking_query);
    mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $date_format = date('M d', strtotime($row['book_date']));
        $index = array_search($date_format, $labels);
        if ($index !== false) {
            $bookings_data[$index] = $row['bookings_count'];
        }
    }
    
    // Query cancellations for each day
    $cancel_query = "SELECT 
        DATE(booking_date) as cancel_date,
        COUNT(*) as cancel_count 
        FROM `booking_order` 
        WHERE booking_status='cancelled' AND booking_date BETWEEN ? AND ?
        GROUP BY DATE(booking_date)
        ORDER BY cancel_date";
        
    $stmt = mysqli_prepare($con, $cancel_query);
    mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $date_format = date('M d', strtotime($row['cancel_date']));
        $index = array_search($date_format, $labels);
        if ($index !== false) {
            $cancellations_data[$index] = $row['cancel_count'];
        }
    }
}

// Prepare response
$response = [
    'labels' => $labels,
    'bookings' => $bookings_data,
    'cancellations' => $cancellations_data
];

// Send JSON response
echo json_encode($response);

// Helper function to generate date labels based on period
function generateDateLabels($start_date, $end_date, $interval = 'daily') {
    $labels = [];
    
    if ($interval === 'monthly') {
        // Monthly labels
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);
        
        foreach ($period as $dt) {
            $labels[] = $dt->format('M');
        }
    } else if ($interval === 'weekly') {
        // Weekly labels
        $ranges = generateWeekRanges($start_date, $end_date);
        foreach ($ranges as $range) {
            $labels[] = date('M d', strtotime($range['start_date'])) . ' - ' . date('M d', strtotime($range['end_date']));
        }
    } else {
        // Daily labels
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $end->modify('+1 day');
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($start, $interval, $end);
        
        foreach ($period as $dt) {
            $labels[] = $dt->format('M d');
        }
    }
    
    return $labels;
}

// Helper function to generate week ranges
function generateWeekRanges($start_date, $end_date) {
    $ranges = [];
    $current = new DateTime($start_date);
    $end = new DateTime($end_date);
    
    while ($current <= $end) {
        $week_start = clone $current;
        $week_end = clone $current;
        $week_end->modify('+6 days');
        
        // Ensure we don't go past the end date
        if ($week_end > $end) {
            $week_end = clone $end;
        }
        
        $ranges[] = [
            'start_date' => $week_start->format('Y-m-d'),
            'end_date' => $week_end->format('Y-m-d')
        ];
        
        $current->modify('+7 days');
    }
    
    return $ranges;
} 