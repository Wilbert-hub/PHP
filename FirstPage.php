<?php
  // index.php
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .btn-custom {
            width: 220px;
            font-size: 18px;
            padding: 12px;
            border-radius: 25px;
            transition: all 0.3s;
        }
        .btn-custom:hover {
            background-color: #007bff;
            color: white;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Выберите действие</h2>
        <button class="btn btn-light border btn-custom mb-2" onclick="location.href='add_city.php'">Добавить город</button><br>
        <button class="btn btn-light border btn-custom mb-2" onclick="location.href='add_employee.php'">Создать работника</button><br>
        <button class="btn btn-light border btn-custom mb-2" onclick="location.href='add_trip.php'">Создать командировку</button><br>
        <button class="btn btn-light border btn-custom" onclick="location.href='2.php'">Скачать PDF</button><br>
        <!-- Кнопка для перехода на страницу 1.php -->
        <button class="btn btn-light border btn-custom mt-3" onclick="location.href='1.php'">COOKIE</button>
    </div>
</body>
</html>
