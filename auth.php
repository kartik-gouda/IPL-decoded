<?php
require_once __DIR__ . '/config.php';
session_start();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

function json_input() {
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    return $data !== null ? $data : $_POST;
}

if ($method !== 'POST') {
    send_error('Only POST requests are allowed', 405);
}

$input = json_input();
if (!$action && isset($input['action'])) $action = $input['action'];

$mysqli = db_connect();

if ($action === 'register') {
    $username = trim($input['username'] ?? '');
    $password = $input['password'] ?? '';
    $email = trim($input['email'] ?? '');

    if (strlen($username) < 3 || strlen($password) < 6) {
        send_error('Username must be >=3 chars and password >=6 chars', 400);
    }

    $stmt = $mysqli->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        send_error('Username already taken', 409);
    }
    $stmt->close();

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = $mysqli->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
    $ins->bind_param('sss', $username, $email, $hash);
    $ins->execute();
    $id = $ins->insert_id;
    $ins->close();

    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $username;
    send_json(['id' => $id, 'username' => $username]);
} elseif ($action === 'login') {
    $username = trim($input['username'] ?? '');
    $password = $input['password'] ?? '';

    if ($username === '' || $password === '') send_error('Missing credentials', 400);

    $stmt = $mysqli->prepare('SELECT id, password_hash FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        send_error('Invalid username or password', 401);
    }

    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['username'] = $username;
    send_json(['id' => (int)$user['id'], 'username' => $username]);
} elseif ($action === 'logout') {
    session_unset();
    session_destroy();
    send_json(['logged_out' => true]);
} else {
    send_error('Invalid action', 400);
}

?>
