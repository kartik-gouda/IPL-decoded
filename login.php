<?php
require_once __DIR__ . '/config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login — IPL Decoded</title>
  <style>body{font-family:Inter,Arial,sans-serif;background:#0B1220;color:#F5F3EE;display:flex;align-items:center;justify-content:center;height:100vh;margin:0} .panel{background:#111C30;padding:28px;border-radius:12px;border:1px solid rgba(245,243,238,0.06);width:360px} h1{margin-top:0;color:#F2B705} label{display:block;margin-top:12px;font-size:13px;color:#8B95A7} input{width:100%;padding:10px;margin-top:6px;border-radius:8px;border:1px solid rgba(245,243,238,0.06);background:#07101A;color:#F5F3EE} button{margin-top:14px;width:100%;padding:10px;border-radius:8px;border:none;background:#F2B705;color:#07101A;font-weight:700} .hint{font-size:13px;color:#8B95A7;margin-top:10px;text-align:center} .row{display:grid;grid-template-columns:1fr 1fr;gap:10px}</style>
</head>
<body>
  <div class="panel">
    <?php if (!empty($_SESSION['user_id'])): ?>
      <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
      <p class="hint">You are already logged in.</p>
      <form method="post" action="auth.php?action=logout">
        <button type="submit">Log out</button>
      </form>
    <?php else: ?>
      <h1>Sign in / Register</h1>
      <form id="loginForm" method="post" action="auth.php?action=login">
        <label>Username
          <input name="username" required>
        </label>
        <label>Password
          <input name="password" type="password" required>
        </label>
        <button type="submit">Sign in</button>
      </form>

      <form id="regForm" method="post" action="auth.php?action=register" style="margin-top:18px;border-top:1px solid rgba(245,243,238,0.04);padding-top:18px">
        <label>New username
          <input name="username" required>
        </label>
        <label>Email (optional)
          <input name="email" type="email">
        </label>
        <label>Password
          <input name="password" type="password" required>
        </label>
        <div class="row"><button type="submit">Create account</button><a href="index.php" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;color:#F2B705;background:#07101A;border-radius:8px;padding:10px">Back</a></div>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
