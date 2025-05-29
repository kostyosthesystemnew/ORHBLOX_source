<?php
session_start();

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
<title>ORHBLOX Download</title>
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
  #footer {
    text-align: center;
    font-size: 12px;
    color: #888;
    margin: 30px 0 10px 0;
  }
  a.download-button {
    display: inline-block;
    background: #3ea9f5;
    color: white;
    padding: 12px 25px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
  }
  a.download-button:hover {
    background: #328dc1;
  }

  #visitor-count {
    text-align: center;
    margin: 30px 0;
    font-style: italic;
    color: #555;
  }
</style>
</head>
<body>
  <div id="topbar">ORHBLOX Download</div>

  <nav>
    <a href="servers.php">Servers</a>
    <a href="download.php">Download</a>
    <a href="forum.php">Forum</a>
    <a href="/">Home</a>
  </nav>

  <div class="content">
    <h2>Download ORHBLOX Client</h2>
    <p>Click the button below to download the client.</p>
    <a href="https://download2290.mediafire.com/ji4z44lgc3jgEHrekemMGB5gZwn2la516dQ90i-rZySYaO9266bNt5v4zWeSX7Q5BVlm48H8LkcWChZ2AITHJP-wgMSowugL0RM98lfniTtpHydaTO_MOB5PvJCda9t-3HQKHAsGSdA03bhCLh-Knm8hVpyV9DMWZDI5HSoN3C2fow/h0ekdr4ccviokgq/OnlyRetroRobloxHere-v1.2.0.1.7z" class="download-button" download>Download ORRH</a>
  </div>

  <div id="visitor-count">Visitors: <?php echo $visitorCount; ?></div>
  <div id="footer">ORHBLOX is not affiliated or related to the Roblox Corporation or any of its subsidaries.</div>
</body>
</html>
