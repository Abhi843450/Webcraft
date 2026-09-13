<?php
header('Content-Type: application/json');
echo json_encode([
    'CONTENT_TYPE' => $_SERVER['CONTENT_TYPE'] ?? null,
    'HTTP_CONTENT_TYPE' => $_SERVER['HTTP_CONTENT_TYPE'] ?? null,
    'REQUEST_CONTENT_TYPE' => $_SERVER['HTTP_CONTENT_TYPE'] ?? $_SERVER['CONTENT_TYPE'] ?? null,
    'ALL_SERVER' => array_filter($_SERVER, function($k) { return stripos($k, 'content') !== false; }, ARRAY_FILTER_USE_KEY)
]);
