<?php
// Устанавливаем HTTP статус 403
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>Access not granted</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      padding: 50px;
      background: #f8d7da;
      color: #721c24;
    }
    h1 {
      font-size: 48px;
      margin-bottom: 20px;
    }
    p {
      font-size: 20px;
      margin-bottom: 30px;
    }
    a {
      color: #721c24;
      text-decoration: none;
      font-weight: bold;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <h1>Access not granted</h1>
  <p>you not have access to thing page.</p>
  <p><a href="/">Home</a></p>
</body>
</html>
