<?php
session_start();

const USERS_FILE = 'users.json';
const POSTS_FILE = 'posts.json';

// 256-bit AES key in hex (keep secret)
const AES_KEY_HEX = 'e2c3f451d5977f2d6a4a87dcefa8b79e9a2e1f7a8f7c5b1d59e4a97b6d2e7f4a';

function aes_encrypt($plaintext) {
    $key = hex2bin(AES_KEY_HEX);
    $iv = random_bytes(16);
    $ciphertext_raw = openssl_encrypt($plaintext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $ciphertext_raw);
}

function aes_decrypt($ciphertext_base64) {
    $key = hex2bin(AES_KEY_HEX);
    $ciphertext = base64_decode($ciphertext_base64);
    $iv = substr($ciphertext, 0, 16);
    $ciphertext_raw = substr($ciphertext, 16);
    return openssl_decrypt($ciphertext_raw, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
}

function loadUsers() {
    if (!file_exists(USERS_FILE)) return [];
    $json = file_get_contents(USERS_FILE);
    $users = json_decode($json, true);
    return is_array($users) ? $users : [];
}

function saveUsers($users) {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

function loadPosts() {
    if (!file_exists(POSTS_FILE)) return [];
    $json = file_get_contents(POSTS_FILE);
    $posts = json_decode($json, true);
    return is_array($posts) ? $posts : [];
}

function savePosts($posts) {
    file_put_contents(POSTS_FILE, json_encode($posts, JSON_PRETTY_PRINT));
}

// Logout handler
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Check session and ban status
if (isset($_SESSION['username'])) {
    $users = loadUsers();
    $username = $_SESSION['username'];

    if (!isset($users[$username])) {
        // User deleted — logout
        session_destroy();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // If user is banned, immediately logout and redirect without showing page
    if (isset($users[$username]['banned']) && $users[$username]['banned'] === true) {
        session_destroy();
        header("Location: ?logout");
        exit;
    }
}

// Registration handler
$errors = [];
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Please fill in both username and password.';
    } else {
        $users = loadUsers();
        if (isset($users[$username])) {
            $errors[] = 'Username already exists.';
        } else {
            $encrypted_password = aes_encrypt($password);
            $users[$username] = [
                'password' => $encrypted_password,
                'banned' => false,
                'ban_reason' => ''
            ];
            saveUsers($users);
            $_SESSION['username'] = $username;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Login handler
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Please fill in both username and password.';
    } else {
        $users = loadUsers();
        if (!isset($users[$username])) {
            $errors[] = 'Username does not exist.';
        } else {
            if (isset($users[$username]['banned']) && $users[$username]['banned'] === true) {
                $errors[] = 'You are banned. Reason: ' . htmlspecialchars($users[$username]['ban_reason'] ?? 'No reason specified');
            } else {
                $decrypted_password = aes_decrypt($users[$username]['password']);
                if ($decrypted_password === $password) {
                    $_SESSION['username'] = $username;
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;
                } else {
                    $errors[] = 'Incorrect password.';
                }
            }
        }
    }
}

// Posting handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'post') {
    if (!isset($_SESSION['username'])) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $username = $_SESSION['username'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $message = trim($_POST['message'] ?? '');
    $now = time();

    if ($message !== '') {
        $posts = loadPosts();

        $lastPost = null;
        for ($i = count($posts) - 1; $i >= 0; $i--) {
            if ($posts[$i]['author'] === $username || $posts[$i]['ip'] === $ip) {
                $lastPost = $posts[$i];
                break;
            }
        }

        if ($lastPost && ($now - $lastPost['time'] < 10)) {
            $errors[] = 'Please wait at least 10 seconds before posting again.';
        } else {
            $posts[] = [
                'author' => $username,
                // Save message as-is, allowing HTML (including images)
                'message' => $message,
                'ip' => $ip,
                'time' => $now
            ];
            savePosts($posts);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

$posts = loadPosts();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>ORHBLOX Forum</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<style>
  body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f0f0f0; color: #222; }
  #topbar {
    background: #3ea9f5 url('https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/i/9c7fcfc3-b932-4975-a76a-e286226aa965/d8e625w-e75341f8-a00d-4c9a-9eeb-cbab5e33e9ba.png') no-repeat 10px center;
    background-size: auto 40px;
    height: 50px;
    line-height: 50px;
    color: white;
    text-align: center;
    font-weight: bold;
    font-size: 18px;
    position: relative;
    padding-left: 60px;
    box-sizing: border-box;
  }
  nav { text-align: center; margin: 20px 0; }
  nav a { margin: 0 15px; color: #3ea9f5; font-weight: bold; text-decoration: none; }
  nav a:hover { text-decoration: underline; }
  #content { max-width: 600px; margin: 0 auto; background: white; padding: 20px; box-shadow: 0 0 10px #aaa; }
  .post { border-bottom: 1px solid #ddd; padding: 10px 0; }
  .post:last-child { border-bottom: none; }
  .author { font-weight: bold; color: #3ea9f5; }
  .time { color: #999; font-size: 12px; }
  form { margin-top: 20px; }
  textarea { width: 100%; height: 80px; }
  input[type="text"], input[type="password"] { width: 100%; padding: 6px; margin: 4px 0; }
  input[type="submit"] { background: #3ea9f5; color: white; border: none; padding: 10px 15px; cursor: pointer; font-weight: bold; }
  input[type="submit"]:hover { background: #337ecc; }
  .errors { background: #fdd; border: 1px solid #f99; padding: 10px; margin: 10px 0; }
  .message-content img { max-width: 100%; height: auto; }
</style>
</head>
<body>

<div id="topbar">ORHBLOX — Forum</div>

<nav>
  <a href="servers.php">Servers</a>
  <a href="download.php">Download</a>
  <a href="forum.php">Forum</a>
  <a href="/">Home</a>
</nav>

<div id="content">

<?php if (!empty($errors)): ?>
  <div class="errors">
    <?php foreach ($errors as $error): ?>
      <div><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php if (!isset($_SESSION['username'])): ?>
  <h2>Login</h2>
  <form method="POST" action="">
    <input type="hidden" name="action" value="login" />
    <input type="text" name="username" placeholder="Username" required autocomplete="off" />
    <input type="password" name="password" placeholder="Password" required autocomplete="off" />
    <input type="submit" value="Log In" />
  </form>

  <h2>Register</h2>
  <form method="POST" action="">
    <input type="hidden" name="action" value="register" />
    <input type="text" name="username" placeholder="Username" required autocomplete="off" />
    <input type="password" name="password" placeholder="Password" required autocomplete="off" />
    <input type="submit" value="Register" />
  </form>

<?php else: ?>
  <p>Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! <a href="?logout">Log out</a></p>

  <form method="POST" action="">
    <input type="hidden" name="action" value="post" />
    <textarea name="message" placeholder="Write your message here..."></textarea><br />
    <input type="submit" value="Send" />
  </form>

  <h2>Messages</h2>

  <?php if (empty($posts)): ?>
    <p>No messages yet.</p>
  <?php else: ?>
    <?php foreach ($posts as $post): ?>
      <div class="post">
        <div>
          <span class="author"><?php echo htmlspecialchars($post['author']); ?></span>
          <span class="time">(<?php echo date('d.m.Y H:i:s', $post['time']); ?>)</span>
        </div>
        <div class="message-content">
          <!-- Render message as HTML to support images -->
          <?php echo $post['message']; ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

<?php endif; ?>

</div>

</body>
</html>
