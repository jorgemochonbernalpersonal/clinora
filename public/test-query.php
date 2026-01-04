<?php
echo json_encode([
    'QUERY_STRING' => $_SERVER['QUERY_STRING'] ?? 'EMPTY',
    'GET' => $_GET,
    'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'EMPTY',
], JSON_PRETTY_PRINT);
