<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
http_response_code(200);
header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'component' => 'locator-sketch30',
    'time' => gmdate(DATE_ATOM),
]);
