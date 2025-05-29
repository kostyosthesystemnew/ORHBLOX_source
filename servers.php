<?php
session_start();

$ip1 = '26.234.10.68';
$port1 = 3333;
$name1 = "natural disasters survival";
$version1 = "2011L";

$ip2 = '26.234.10.68';
$port2 = 1111;
$name2 = "train crashing";
$version2 = "2012M";

$visitorFile = __DIR__ . '/visitors.txt';

if (!isset($_SESSION['visited'])) {
    $_SESSION['visited'] = true;
    if (!file_exists($visitorFile)) {
        file_put_contents($visitorFile, "0");
    }
    $count = (int)file_get_contents($visitorFile);
    $count++;
    file_put_contents($visitorFile, $count);
}

$visitorCount = (int)file_get_contents($visitorFile);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>ORHBLOX Servers</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<style>
  body {
    font-family: Arial, sans-serif;
    margin: 0; padding: 0;
    background: #f0f0f0;
    color: #222;
  }
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
  nav {
    text-align: center;
    margin: 20px 0;
  }
  nav a {
    margin: 0 15px;
    color: #3ea9f5;
    text-decoration: none;
    font-weight: bold;
  }
  nav a:hover {
    text-decoration: underline;
  }
  .content {
    max-width: 500px;
    margin: 40px auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
    text-align: center;
  }
  .server {
    background: white;
    max-width: 500px;
    margin: 20px auto;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
  }
  .server h2 {
    margin-top: 0;
  }
  .status {
    font-weight: bold;
  }
  .online {
    color: green;
  }
  .offline {
    color: red;
  }
  #visitor-count {
    text-align: center;
    margin: 30px 0;
    font-style: italic;
    color: #555;
  }
  #footer {
    text-align: center;
    font-size: 12px;
    color: #888;
    margin: 30px 0 10px 0;
  }
</style>

</head>
<body>
  <div id="topbar">ORHBLOX Servers</div>

  <nav>
    <a href="servers.php">Servers</a>
    <a href="download.php">Download</a>
    <a href="forum.php">Forum</a>
    <a href="/">Home</a>
  </nav>

  <div class="server">
    <h2 id="name1"><?php echo htmlspecialchars($name1); ?></h2>
    <p>IP: <span id="ip1"><?php echo htmlspecialchars($ip1); ?></span></p>
    <p>Port: <span id="port1"><?php echo htmlspecialchars($port1); ?></span></p>
    <p>Client version: <span id="version1"><?php echo htmlspecialchars($version1); ?></span></p>
  </div>

  <div class="server">
    <h2 id="name2"><?php echo htmlspecialchars($name2); ?></h2>
    <p>IP: <span id="ip2"><?php echo htmlspecialchars($ip2); ?></span></p>
    <p>Port: <span id="port2"><?php echo htmlspecialchars($port2); ?></span></p>
    <p>Client version: <span id="version2"><?php echo htmlspecialchars($version2); ?></span></p>
  </div>

  <div id="visitor-count">Visitors: <?php echo $visitorCount; ?></div>
  <div id="footer">ORHBLOX is not affiliated or related to the Roblox Corporation or any of its subsidaries.</div>
</body>
</html>
