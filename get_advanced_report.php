<?php
include("db.php");

$start_date = $_GET['start'] ?? date('Y-m-d', strtotime('-7 days'));
$end_date = $_GET['end'] ?? date('Y-m-d');

// Get all menu items for low selling items comparison
$all_menu_items = [];
$menu_result = mysqli_query($conn, "SELECT id, name, category FROM menu_items");
while($item = mysqli_fetch_assoc($menu_result)) {
    $all_menu_items[$item['id']] = [
        'name' => $item['name'],
        'category' => $item['category'],
        'quantity' => 0,
        'revenue' => 0
    ];
}

// Get sales data from orders
$sales_result = mysqli_query($conn, "SELECT oi.product_id, oi.product_name, SUM(oi.quantity) as quantity, SUM(oi.subtotal) as revenue 
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    WHERE DATE(o.order_date) BETWEEN '$start_date' AND '$end_date'
    GROUP BY oi.product_id, oi.product_name
    ORDER BY revenue DESC");

$sales_data = [];
$total_revenue = 0;
$total_orders = 0;
$total_items = 0;

while($row = mysqli_fetch_assoc($sales_result)) {
    $sales_data[] = [
        'name' => $row['product_name'],
        'quantity' => intval($row['quantity']),
        'revenue' => floatval($row['revenue'])
    ];
    $total_revenue += $row['revenue'];
    $total_items += $row['quantity'];
}

// Get total orders count
$order_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE DATE(order_date) BETWEEN '$start_date' AND '$end_date'");
$total_orders = mysqli_fetch_assoc($order_result)['total'];

// Top Selling Items (Top 5)
$top_items = array_slice($sales_data, 0, 5);

// Low Selling Items (items with 0 or low sales)
$low_items = [];
foreach($all_menu_items as $item) {
    if($item['quantity'] <= 2) {
        $low_items[] = $item;
    }
}
$low_items = array_slice($low_items, 0, 5);

// Get daily breakdown for charts
$labels = [];
$revenueData = [];
$orderData = [];
$details = [];

$current = strtotime($start_date);
$end = strtotime($end_date);
$days_diff = ($end - $current) / (60 * 60 * 24);

if($days_diff <= 31) {
    // Daily breakdown
    while($current <= $end) {
        $date = date('Y-m-d', $current);
        $labels[] = date('d M', $current);
        
        $sql = "SELECT COALESCE(SUM(total_amount),0) as rev, COUNT(*) as ord FROM orders WHERE DATE(order_date) = '$date'";
        $res = mysqli_fetch_assoc(mysqli_query($conn, $sql));
        
        $revenueData[] = floatval($res['rev']);
        $orderData[] = intval($res['ord']);
        
        $details[] = [
            'period' => date('d-m-Y', $current),
            'orders' => intval($res['ord']),
            'revenue' => number_format($res['rev'], 2),
            'items' => 0,
            'avg_order' => $res['ord'] > 0 ? number_format($res['rev'] / $res['ord'], 2) : 0
        ];
        
        $current = strtotime('+1 day', $current);
    }
} else {
    // Monthly breakdown for longer periods
    $currentMonth = date('Y-m', strtotime($start_date));
    $endMonth = date('Y-m', strtotime($end_date));
    
    while($currentMonth <= $endMonth) {
        $labels[] = date('M Y', strtotime($currentMonth . '-01'));
        
        $start = $currentMonth . '-01';
        $end_date_month = date('Y-m-t', strtotime($start));
        
        $sql = "SELECT COALESCE(SUM(total_amount),0) as rev, COUNT(*) as ord FROM orders WHERE DATE(order_date) BETWEEN '$start' AND '$end_date_month'";
        $res = mysqli_fetch_assoc(mysqli_query($conn, $sql));
        
        $revenueData[] = floatval($res['rev']);
        $orderData[] = intval($res['ord']);
        
        $details[] = [
            'period' => date('M Y', strtotime($currentMonth . '-01')),
            'orders' => intval($res['ord']),
            'revenue' => number_format($res['rev'], 2),
            'items' => 0,
            'avg_order' => $res['ord'] > 0 ? number_format($res['rev'] / $res['ord'], 2) : 0
        ];
        
        $currentMonth = date('Y-m', strtotime($currentMonth . '-01 +1 month'));
    }
}

// Prepare response
$response = [
    'total_revenue' => $total_revenue,
    'total_orders' => $total_orders,
    'avg_order_value' => $total_orders > 0 ? $total_revenue / $total_orders : 0,
    'total_items' => $total_items,
    'top_items' => $top_items,
    'low_items' => $low_items,
    'details' => $details,
    'chart_labels' => $labels,
    'chart_revenue' => $revenueData,
    'chart_orders' => $orderData
];

echo json_encode($response);
?>