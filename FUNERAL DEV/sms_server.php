<?php
require_once 'send_sms.php';

// Start built-in PHP server: php -S localhost:8000 sms_server.php
// Or use Apache/Nginx to serve this file

// Parse URL
$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

switch ($request) {
    case '/incoming' && $method === 'POST':
        // TODO: Handle incoming messages
        $input = json_decode(file_get_contents("php://input"), true);
        // Example: Log or respond
        echo json_encode([
            'status' => 'received',
            'data' => $input
        ]);
        break;

    case '/delivery' && $method === 'POST':
        // TODO: Handle delivery reports
        $report = json_decode(file_get_contents("php://input"), true);
        // Example: Log delivery report
        echo json_encode([
            'status' => 'delivered',
            'report' => $report
        ]);
        break;

    case '/send-sms' && $method === 'GET':
        // TODO: Call sendSMS on server "start" simulation
        $response = sendSMS("0714506855", "Hello from PHP SMS Server!");
        echo json_encode([
            'status' => 'sent',
            'response' => $response
        ]);
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
        break;
}