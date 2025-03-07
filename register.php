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
    $pass = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    
    if (!empty($name) && !empty($pass)) {
        $stmt = $conn->prepare("INSERT INTO `Users` (`Name`, `Pass`) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $pass);
        
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $name;  // Сохраняем имя пользователя в сессии после регистрации
            $success_message = "Пользователь успешно зарегистрирован.";
            header("Location: FirstPage.php");  // Перенаправляем на главную страницу
            exit();
        } else {
            $error_message = "Ошибка: " . $stmt->error;
        }
        
        $stmt->close();
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
    <title>Регистрация</title>
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
        .success, .error {
            font-size: 14px;
            text-align: center;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Регистрация</h2>
        <?php
        if (isset($success_message)) {
            echo '<p class="success">' . $success_message . '</p>';
        }
        if (isset($error_message)) {
            echo '<p class="error">' . $error_message . '</p>';
        }
        ?>
        <form method="post" action="">
            <label for="name">Имя:</label>
            <input type="text" name="name" required>
            <label for="password">Пароль:</label>
            <input type="password" name="password" required>
            <button class="btn" type="submit">Зарегистрироваться</button>
        </form>
        <div class="link-buttons">
            <button onclick="window.location.href='login.php'">Назад</button>
        </div>
    </div>
</body>
</html>
