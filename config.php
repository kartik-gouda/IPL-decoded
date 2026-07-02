<?php

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'ipl_decoded');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

function db_connect() {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_error) {
        send_error('Database connection failed: ' . $mysqli->connect_error, 500);
    }
    $mysqli->set_charset(DB_CHARSET);
    return $mysqli;
}

function send_json($data) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    exit;
}

function send_error($message, $code = 400) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => $message], JSON_UNESCAPED_SLASHES);
    exit;
}
