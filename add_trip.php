<?php
// Параметры подключения к базе данных
$host = 'localhost'; // Хост
$user = 'root';      // Пользователь
$password = '';      // Пароль
$database = 'Ride';  // Имя базы данных

// Подключение к базе данных
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение списка городов
$cities = [];
$employees = [];

$sqlCities = "SELECT * FROM City";
$resultCities = $conn->query($sqlCities);
if ($resultCities->num_rows > 0) {
    while ($row = $resultCities->fetch_assoc()) {
        $cities[] = $row;
    }
}

$sqlEmployees = "SELECT * FROM Employee";
$resultEmployees = $conn->query($sqlEmployees);
if ($resultEmployees->num_rows > 0) {
    while ($row = $resultEmployees->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Закрытие соединения
$conn->close();

// Обработка формы отправки почты
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $subject = "Информация о командировке";
    $message = "Данные о командировке были успешно обработаны. Пожалуйста, проверьте информацию в вашем аккаунте.";

    // Отправка письма
    if (mail($email, $subject, $message)) {
        echo "<p class='mt-3 text-success'>Письмо успешно отправлено на {$email}</p>";
    } else {
        echo "<p class='mt-3 text-danger'>Ошибка при отправке письма.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать командировку</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Создать командировку</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="city" class="form-label">Выберите город:</label>
            <select class="form-select" name="city" required>
                <?php foreach ($cities as $city): ?>
                    <option value="<?php echo $city['CityID']; ?>">
                        <?php echo $city['Name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="employee" class="form-label">Выберите работника:</label>
            <select class="form-select" name="employee" required>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?php echo $employee['EmployeeID']; ?>">
                        <?php echo $employee['Name'] . ' ' . $employee['Surname']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="startDate" class="form-label">Выберите дату начала:</label>
            <input type="date" class="form-control" name="startDate" required>
        </div>
        <div class="mb-3">
            <label for="endDate" class="form-label">Выберите дату окончания:</label>
            <input type="date" class="form-control" name="endDate" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Введите вашу почту:</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Вычислить</button>
        <button type="submit" name="sendEmail" class="btn btn-success">Отправить информацию на почту</button>
    </form>
    <button class="btn btn-light border m-2" onclick="location.href='FirstPage.php'">назад</button>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sendEmail'])) {
        // Логика для отправки почты была вынесена выше
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['sendEmail'])) {
        // Выбор города и сотрудника
        $selectedCity = array_filter($cities, fn($c) => $c['CityID'] == $_POST['city']);
        $selectedEmployee = array_filter($employees, fn($e) => $e['EmployeeID'] == $_POST['employee']);
        
        if (!empty($selectedCity) && !empty($selectedEmployee)) {
            $selectedCity = array_values($selectedCity)[0];
            $selectedEmployee = array_values($selectedEmployee)[0];

            // Получение дат
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];

            // Вычисление разницы в днях между датами
            $startTimestamp = strtotime($startDate);
            $endTimestamp = strtotime($endDate);
            $daysDiff = ($endTimestamp - $startTimestamp) / (60 * 60 * 24);

            // Вычисление суммы
            $sum = $daysDiff * $selectedCity['PerDay'];

            // Вывод всех данных о городе и сотруднике
            echo "<h3 class='mt-4'>Выбранные данные:</h3>";
            echo "<p><strong>Город:</strong> ";
            echo "Название: {$selectedCity['Name']}, ";
            echo "Страна: {$selectedCity['Country']}, ";
            echo "Является столицей: {$selectedCity['IsACapital']}, ";
            echo "Стоимость за день: {$selectedCity['PerDay']} руб.</p>";

            echo "<p><strong>Работник:</strong> ";
            echo "Имя: {$selectedEmployee['Name']} {$selectedEmployee['Surname']}, ";
            echo "Отчество: {$selectedEmployee['SecondName']}, ";
            echo "Должность: {$selectedEmployee['JobTitle']}, ";
            echo "Дата найма: {$selectedEmployee['HireDate']}, ";
            echo "Паспорт: {$selectedEmployee['Passport']}, ";
            echo "Является бухгалтером: " . ($selectedEmployee['IsAnAccountant'] ? 'Да' : 'Нет') . "</p>";

            // Вывод суммы
            echo "<p><strong>Сумма командировки:</strong> {$sum} руб.</p>";

            // Создание JSON-файла
            $tripData = [
                'employee' => [
                    'name' => $selectedEmployee['Name'],
                    'surname' => $selectedEmployee['Surname'],
                    'secondName' => $selectedEmployee['SecondName'],
                    'jobTitle' => $selectedEmployee['JobTitle'],
                    'passport' => $selectedEmployee['Passport']
                ],
                'city' => [
                    'name' => $selectedCity['Name'],
                    'country' => $selectedCity['Country'],
                    'isCapital' => $selectedCity['IsACapital'],
                    'perDay' => $selectedCity['PerDay']
                ],
                'startDate' => $startDate,
                'endDate' => $endDate,
                'days' => $daysDiff,
                'sum' => $sum
            ];

            // Сохранение в JSON
            $jsonData = json_encode($tripData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            file_put_contents('trip_data.json', $jsonData);

            echo "<p>Данные успешно записаны в файл <a href='trip_data.json' download>trip_data.json</a></p>";
        }
    }
    ?>
</body>
</html>
