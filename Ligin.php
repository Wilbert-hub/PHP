<?php
session_start();

// Параметры подключения к базе данных
$host = 'localhost'; // Хост
$user = 'root';      // Пользователь
$password = '';      // Пароль
$database = 'mydb';  // Имя базы данных

// Подключение к базе данных
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Обработчик входа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Проверка существования пользователя в базе
    $sql = "SELECT * FROM Name WHERE Name = ? AND Pssword = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Пользователь найден, создаем сессию
        $_SESSION['user'] = $name;

        // Если выбрана опция "Запомнить меня", создаём cookie
        if (isset($_POST['remember'])) {
            setcookie('user', $name, time() + (86400 * 30), "/"); // Cookie на 30 дней
        }

        // Перенаправляем на главную страницу
        header("Location: FirstPage.php");
        exit();
    } else {
        echo "Неверные данные для входа!";
    }
}

// Обработчик регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Проверка, существует ли уже пользователь с таким именем
    $sql = "SELECT * FROM Name WHERE Name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Пользователь с таким именем уже существует!";
    } else {
        // Регистрация нового пользователя
        $sql = "INSERT INTO Name (Name, Pssword) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $password);
        $stmt->execute();
        
        echo "Регистрация успешна, теперь войдите!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход и регистрация</title>
</head>
<body>
    <h2>Вход</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Имя" required><br>
        <input type="password" name="password" placeholder="Пароль" required><br>
        <label><input type="checkbox" name="remember"> Запомнить меня</label><br>
        <button type="submit" name="login">Войти</button>
    </form>
    <h2>Регистрация</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Имя" required><br>
        <input type="password" name="password" placeholder="Пароль" required><br>
        <button type="submit" name="register">Зарегистрироваться</button>
    </form>
</body>
</html>
