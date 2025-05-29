<?php
// Устанавливаем HTTP статус 404
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>error - ORHBLOX</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      padding: 50px;
      background: #f0f0f0;
      color: #333;
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
      color: #007BFF;
      text-decoration: none;
      font-weight: bold;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <h1>error 404</h1>
  <p>not found apache</p>
  <p><a href="/">back to home</a></p>
</body>
</html>
