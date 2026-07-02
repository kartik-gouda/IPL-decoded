<?php
require_once __DIR__ . '/config.php';

type_check();

$mysqli = db_connect();

switch ($_GET['type']) {
    case 'stats':
        send_json(fetch_stats($mysqli));
    case 'toss':
        send_json(fetch_toss($mysqli));
    case 'overs':
        send_json(fetch_overs($mysqli));
    case 'players':
        send_json(fetch_players($mysqli));
    case 'venues':
        send_json(fetch_venues($mysqli));
    case 'all':
        send_json([
            'stats' => fetch_stats($mysqli),
            'toss' => fetch_toss($mysqli),
            'overs' => fetch_overs($mysqli),
            'players' => fetch_players($mysqli),
            'venues' => fetch_venues($mysqli),
        ]);
}

function type_check() {
    $valid = ['stats', 'toss', 'overs', 'players', 'venues', 'all'];
    if (!isset($_GET['type']) || !in_array($_GET['type'], $valid, true)) {
        send_error('Invalid API request. Use ?type=stats|toss|overs|players|venues|all', 400);
    }
}

function fetch_stats($mysqli) {
    $result = $mysqli->query('SELECT key_name, key_value FROM meta_stats ORDER BY key_name');
    $stats = [];
    while ($row = $result->fetch_assoc()) {
        $value = is_numeric($row['key_value']) ? (int)$row['key_value'] : $row['key_value'];
        $stats[$row['key_name']] = $value;
    }
    return $stats;
}

function fetch_toss($mysqli) {
    $result = $mysqli->query('SELECT era_label, field_first_pct, bat_first_pct FROM toss_win_pct ORDER BY id');
    return $result->fetch_all(MYSQLI_ASSOC);
}

function fetch_overs($mysqli) {
    $result = $mysqli->query('SELECT over_number, run_rate, wickets FROM over_stats ORDER BY over_number');
    return $result->fetch_all(MYSQLI_ASSOC);
}

function fetch_players($mysqli) {
    $result = $mysqli->query('SELECT name, strike_rate, runs FROM players ORDER BY strike_rate DESC');
    return $result->fetch_all(MYSQLI_ASSOC);
}

function fetch_venues($mysqli) {
    $result = $mysqli->query('SELECT name, avg_first_innings_score FROM venues ORDER BY avg_first_innings_score DESC');
    return $result->fetch_all(MYSQLI_ASSOC);
}
