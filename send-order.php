<?php
// Load configuration
require_once 'config.php';

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

// Email configuration
$to = ORDER_EMAIL;
$subject = "Order Request {$orderData['orderNumber']} from {$customerInfo['company']}";

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

// Email headers
$headers = [
    'From: ' . $customerInfo['email'],
    'Reply-To: ' . $customerInfo['email'],
    'Content-Type: text/plain; charset=UTF-8',
    'X-Mailer: PHP/' . phpversion()
];

// Send email
$mailSent = mail($to, $subject, $emailBody, implode("\r\n", $headers));

if ($mailSent) {
    // Also save to a log file for backup
    if (LOG_ORDERS) {
        $logEntry = date('Y-m-d H:i:s') . " - Order {$orderData['orderNumber']} from {$customerInfo['company']} sent to {$to}\n";
        file_put_contents(LOG_FILE, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Order sent successfully!',
        'orderNumber' => $orderData['orderNumber']
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to send email',
        'message' => 'There was an error sending your order. Please try again or contact support.'
    ]);
}
?> 