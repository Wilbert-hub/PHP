<?php
// Стартуем сессию для работы с сессиями
session_start();

// Файл для записи данных
$file_path = 'user_input.txt';

// Если была отправка формы для записи, сохраняем введенные данные в файл
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_input'])) {
    $user_input = $_POST['user_input'];

    // Открываем файл для записи, если файл существует, данные дописываются в конец
    file_put_contents($file_path, $user_input . PHP_EOL, FILE_APPEND);

    // Сохраняем последнее введенное значение в cookie
    setcookie('last_input', $user_input, time() + 3600, '/'); // cookie хранится 1 час

    // Увеличиваем счетчик записей в сессии
    if (!isset($_SESSION['record_count'])) {
        $_SESSION['record_count'] = 0;
    }
    $_SESSION['record_count']++;
}

// Чтение содержимого файла (если нужно отображать его на странице)
$file_content = file_exists($file_path) ? file_get_contents($file_path) : '';

// Флаг, который проверяет, была ли нажата кнопка для вывода данных из файла
$show_content = false;

// Если была нажата кнопка для вывода данных
if (isset($_POST['show_content'])) {
    $show_content = true;
}

// Получаем количество дней в текущем месяце
$days_in_month = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));

// Проверяем, был ли пользователь на странице (по cookie)
$visited_message = '';
if (isset($_COOKIE['visited'])) {
    $visited_message = 'Вы уже были на этой странице.';
} else {
    $visited_message = 'Это ваш первый визит!';
}

// Получаем последнее записанное значение из cookie, если оно существует
$last_input = isset($_COOKIE['last_input']) ? $_COOKIE['last_input'] : 'Нет записей';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Запись и вывод данных из файла</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1, h2, h3 {
            color: #2c3e50;
        }
        .container {
            width: 80%;
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 0;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
        pre {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            white-space: pre-wrap;
        }
        .info {
            margin-top: 20px;
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 4px;
        }
        .visited-message {
            font-style: italic;
            color: #95a5a6;
        }
        .button-container {
            margin-top: 20px;
            text-align: center;
        }
        .button-container a {
            text-decoration: none;
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
        }
        .button-container a:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Введите текст и сохраните его в файл</h1>

    <!-- Форма для записи данных в файл -->
    <form method="POST">
        <textarea name="user_input" placeholder="Введите ваш текст..."></textarea><br>
        <input type="submit" value="Сохранить в файл">
    </form>

    <h1>Вывод содержимого файла</h1>

    <!-- Кнопка для отображения содержимого файла -->
    <form method="POST">
        <input type="submit" name="show_content" value="Показать содержимое файла">
    </form>

    <!-- Отображение содержимого файла, если была нажата кнопка для вывода -->
    <?php if ($show_content): ?>
        <h2>Содержимое файла:</h2>
        <pre><?php echo htmlspecialchars($file_content); ?></pre> <!-- Безопасный вывод содержимого -->
    <?php endif; ?>

    <div class="info">
        <!-- Выводим количество дней в текущем месяце -->
        <h2>Количество дней в текущем месяце: <?php echo $days_in_month; ?></h2>

        <!-- Выводим информацию о количестве записей в файл за сессию -->
        <h3>Количество записей, сделанных в файл за эту сессию: <?php echo isset($_SESSION['record_count']) ? $_SESSION['record_count'] : 0; ?></h3>

        <!-- Выводим информацию о визите пользователя -->
        <h3 class="visited-message"><?php echo $visited_message; ?></h3>

        <!-- Показываем последнее записанное значение из cookie -->
        <h3>Последнее записанное значение в файл: <?php echo htmlspecialchars($last_input); ?></h3>
    </div>

    <!-- Кнопка для перехода на другую страницу -->
    <div class="button-container">
        <a href="FirstPage.php">Перейти на страницу FirstPage.php</a>
    </div>
</div>

</body>
</html>
