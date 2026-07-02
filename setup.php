<?php
require_once __DIR__ . '/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS);
    $mysqli->set_charset(DB_CHARSET);

    $mysqli->query('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET ' . DB_CHARSET . ' COLLATE utf8mb4_unicode_ci');
    $mysqli->select_db(DB_NAME);

    $mysqli->query(
        'CREATE TABLE IF NOT EXISTS meta_stats (
            key_name VARCHAR(64) NOT NULL PRIMARY KEY,
            key_value VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=' . DB_CHARSET
    );

    $mysqli->query(
        'CREATE TABLE IF NOT EXISTS toss_win_pct (
            id INT AUTO_INCREMENT PRIMARY KEY,
            era_label VARCHAR(32) NOT NULL,
            field_first_pct DECIMAL(5,2) NOT NULL,
            bat_first_pct DECIMAL(5,2) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=' . DB_CHARSET
    );

    $mysqli->query(
        'CREATE TABLE IF NOT EXISTS over_stats (
            over_number TINYINT UNSIGNED NOT NULL PRIMARY KEY,
            run_rate DECIMAL(4,2) NOT NULL,
            wickets DECIMAL(4,2) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=' . DB_CHARSET
    );

    $mysqli->query(
        'CREATE TABLE IF NOT EXISTS players (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(128) NOT NULL,
            runs INT UNSIGNED NOT NULL,
            strike_rate DECIMAL(5,2) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=' . DB_CHARSET
    );

    $mysqli->query(
        'CREATE TABLE IF NOT EXISTS venues (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(128) NOT NULL,
            avg_first_innings_score INT UNSIGNED NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=' . DB_CHARSET
    );

    $mysqli->query(
        'CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(255) DEFAULT NULL,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=' . DB_CHARSET
    );

    if (table_is_empty($mysqli, 'meta_stats')) {
        $mysqli->query("INSERT INTO meta_stats (key_name, key_value) VALUES
            ('matches_analyzed', '1095'),
            ('seasons_covered', '17'),
            ('franchises_tracked', '15'),
            ('deliveries_parsed', '238')
        ");
    }

    if (table_is_empty($mysqli, 'toss_win_pct')) {
        $mysqli->query("INSERT INTO toss_win_pct (era_label, field_first_pct, bat_first_pct) VALUES
            ('2008–12', 46, 54),
            ('2013–16', 51, 49),
            ('2017–20', 55, 45),
            ('2021–24', 60, 40)
        ");
    }

    if (table_is_empty($mysqli, 'over_stats')) {
        $mysqli->query("INSERT INTO over_stats (over_number, run_rate, wickets) VALUES
            (1, 7.20, 0.10),
            (2, 7.60, 0.14),
            (3, 7.90, 0.17),
            (4, 8.10, 0.19),
            (5, 7.80, 0.20),
            (6, 7.30, 0.22),
            (7, 7.00, 0.24),
            (8, 6.80, 0.23),
            (9, 6.90, 0.22),
            (10, 7.10, 0.24),
            (11, 7.00, 0.25),
            (12, 7.20, 0.26),
            (13, 7.40, 0.27),
            (14, 7.60, 0.29),
            (15, 8.00, 0.31),
            (16, 8.60, 0.34),
            (17, 9.40, 0.38),
            (18, 10.10, 0.42),
            (19, 10.80, 0.47),
            (20, 11.40, 0.53)
        ");
    }

    if (table_is_empty($mysqli, 'players')) {
        $mysqli->query("INSERT INTO players (name, runs, strike_rate) VALUES
            ('A. de Villiers', 5162, 151.70),
            ('C.H. Gayle', 4965, 148.30),
            ('J. Bairstow', 2317, 142.90),
            ('D. Warner', 6565, 139.90),
            ('S. Dhawan', 6769, 127.10),
            ('V. Kohli', 7263, 130.40)
        ");
    }

    if (table_is_empty($mysqli, 'venues')) {
        $mysqli->query("INSERT INTO venues (name, avg_first_innings_score) VALUES
            ('M. Chinnaswamy, Bengaluru', 178),
            ('Wankhede, Mumbai', 172),
            ('Eden Gardens, Kolkata', 168),
            ('Arun Jaitley, Delhi', 165),
            ('Narendra Modi, Ahmedabad', 170),
            ('MA Chidambaram, Chennai', 158),
            ('Rajiv Gandhi, Hyderabad', 174),
            ('Sawai Mansingh, Jaipur', 163)
        ");
    }

    $message = 'Database setup complete. Database "' . DB_NAME . '" is ready.';
} catch (Exception $e) {
    $message = 'Setup failed: ' . $e->getMessage();
}

function table_is_empty(mysqli $mysqli, string $table): bool {
    $result = $mysqli->query('SELECT 1 FROM `' . $mysqli->real_escape_string($table) . '` LIMIT 1');
    return $result->num_rows === 0;
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPL Decoded Setup</title>
    <style>body{font-family:Arial,sans-serif;background:#0B1220;color:#F5F3EE;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;padding:24px;} .panel{max-width:560px;background:#111C30;border:1px solid rgba(245,243,238,0.12);border-radius:20px;padding:28px;} h1{margin-top:0;color:#F2B705;} a{color:#F2B705;}</style>
</head>
<body>
    <div class="panel">
        <h1>IPL Decoded setup</h1>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Open <a href="index.php">index.php</a> once setup finishes.</p>
        <p>If the database user or password is not <code>root</code> with no password, update <code>config.php</code>.</p>
    </div>
</body>
</html>
