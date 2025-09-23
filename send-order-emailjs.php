<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get the JSON data from the request
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON data']);
    exit;
}

// Extract order data
$orderData = $input['orderData'];
$customerInfo = $orderData['customer'];
$items = $orderData['items'];

// Build email body
$emailBody = "NEW ORDER REQUEST\n";
$emailBody .= "=================\n";
$emailBody .= "Order Number: {$orderData['orderNumber']}\n";
$emailBody .= "Date: {$orderData['orderDate']}\n\n";

$emailBody .= "CUSTOMER INFORMATION:\n";
$emailBody .= "--------------------\n";
$emailBody .= "Company: {$customerInfo['company']}\n";
$emailBody .= "Contact: {$customerInfo['contact']}\n";
$emailBody .= "Email: {$customerInfo['email']}\n";
$emailBody .= "Phone: " . ($customerInfo['phone'] ?: 'Not provided') . "\n\n";

$emailBody .= "ORDER DETAILS:\n";
$emailBody .= "--------------\n";

foreach ($items as $index => $item) {
    $itemNumber = $index + 1;
    $emailBody .= "\n{$itemNumber}. Product: {$item['productCode']} - {$item['description']}\n";
    $emailBody .= "   Category: {$item['category']}\n";
    $emailBody .= "   Quantity: {$item['quantity']}\n";
    $emailBody .= "   Color: {$item['color']}\n";
    $emailBody .= "   MOQ: {$item['moq']}\n";
    $emailBody .= "   Packing Qty: {$item['packingQty']}\n";
    $emailBody .= "   Status: {$item['status']}\n";
    
    // Add alternative requests if any
    if (!empty($item['requestedAlternatives'])) {
        $alternatives = [];
        $labels = [
            'color' => 'Different Color',
            'size' => 'Different Size',
            'similar-size' => 'Similar Size',
            'similar-product' => 'Similar Product & Solution',
            'remove' => 'Remove if Unavailable'
        ];
        
        foreach ($item['requestedAlternatives'] as $alt) {
            $alternatives[] = $labels[$alt] ?? $alt;
        }
        
        $emailBody .= "   ⚠️  ALTERNATIVE REQUESTED: " . implode(', ', $alternatives) . "\n";
    }
    
    $emailBody .= "   -------------------------\n";
}

// Add customer notes if any
if (!empty($customerInfo['notes'])) {
    $emailBody .= "\nADDITIONAL NOTES:\n";
    $emailBody .= "-----------------\n";
    $emailBody .= $customerInfo['notes'] . "\n";
}

// Save order to a JSON file for backup
$orderBackup = [
    'timestamp' => date('Y-m-d H:i:s'),
    'orderData' => $orderData,
    'emailBody' => $emailBody
];

$backupFile = 'orders/' . $orderData['orderNumber'] . '.json';
if (!is_dir('orders')) {
    mkdir('orders', 0755, true);
}
file_put_contents($backupFile, json_encode($orderBackup, JSON_PRETTY_PRINT));

// Log the order attempt
$logEntry = date('Y-m-d H:i:s') . " - Order {$orderData['orderNumber']} from {$customerInfo['company']} saved to backup\n";
file_put_contents('order_log.txt', $logEntry, FILE_APPEND | LOCK_EX);

// Return success with instructions for manual email
echo json_encode([
    'success' => true,
    'message' => 'Order saved successfully! Please check your email or contact support.',
    'orderNumber' => $orderData['orderNumber'],
    'backupFile' => $backupFile,
    'emailBody' => $emailBody,
    'note' => 'Order has been saved. You can manually send the email or contact support for assistance.'
]);
?> 