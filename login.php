<?php
session_start();  // Начинаем сессию

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $pass = trim($_POST['password']);
    
    if (!empty($name) && !empty($pass)) {
        $stmt = $conn->prepare("SELECT Pass FROM `Users` WHERE Name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->bind_result($hashed_pass);
        $stmt->fetch();
        $stmt->close();
        
        if ($hashed_pass && password_verify($pass, $hashed_pass)) {
            $name = $_SESSION['user_name'];
            $_SESSION['user_name'] = $name;  // Сохраняем имя пользователя в сессии
            header("Location: FirstPage.php");  // Перенаправляем на главную страницу
            exit();
        } else {
            $error_message = "Неверные данные.";
        }
    } else {
        $error_message = "Поля не должны быть пустыми.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .link-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .link-buttons button {
            background-color: transparent;
            color: #4CAF50;
            border: none;
            cursor: pointer;
        }
        .link-buttons button:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Вход в систему</h2>
        <?php if (isset($error_message)) { echo '<p class="error">' . $error_message . '</p>'; } ?>
        <form method="post" action="">
            <label for="name">Имя:</label>
            <input type="text" name="name" required>
            <label for="password">Пароль:</label>
            <input type="password" name="password" required>
            <button class="btn" type="submit">Войти</button>
        </form>
        <div class="link-buttons">
            <button onclick="window.history.back();">Назад</button>
            <button onclick="window.location.href='register.php'">Зарегистрироваться</button>
        </div>
    </div>
</body>
</html>
