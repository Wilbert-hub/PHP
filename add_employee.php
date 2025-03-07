<?php
require 'vendor/autoload.php';  // Подключаем автозагрузчик Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ride";

// Создание соединения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Обработка формы добавления сотрудника
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $secondname = $_POST['secondname'];
    $hire_date = $_POST['hire_date'];
    $passport = $_POST['passport'];
    $job_title = $_POST['job_title'];
    $is_accountant = ($job_title == "Бухгалтер") ? 1 : 0;

    // Генерация уникального EmployeeID
    do {
        $employee_id = rand(1000, 9999);
        $result = $conn->query("SELECT EmployeeID FROM Employee WHERE EmployeeID = $employee_id");
    } while ($result->num_rows > 0);

    $sql = "INSERT INTO Employee (Name, Surname, SecondName, HireDate, Passport, IsAnAccountant, JobTitle, EmployeeID) 
            VALUES ('$name', '$surname', '$secondname', '$hire_date', '$passport', '$is_accountant', '$job_title', '$employee_id')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success text-center'>Сотрудник успешно добавлен!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Ошибка: " . $conn->error . "</div>";
    }
}

// Обработка запроса на удаление сотрудника
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $employee_id = $_POST['employee_id'];
    
    // Удаление сотрудника по ID
    $sql = "DELETE FROM Employee WHERE EmployeeID = $employee_id";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success text-center'>Сотрудник успешно удалён!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Ошибка: " . $conn->error . "</div>";
    }
}

// Обработка запроса на экспорт в Excel
if (isset($_GET['export'])) {
    // Создание нового документа Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Получение данных из таблицы Employee
    $result = $conn->query("SELECT * FROM Employee");
    $sheet->setCellValue('A1', 'EmployeeID');
    $sheet->setCellValue('B1', 'Name');
    $sheet->setCellValue('C1', 'Surname');
    $sheet->setCellValue('D1', 'SecondName');
    $sheet->setCellValue('E1', 'HireDate');
    $sheet->setCellValue('F1', 'Passport');
    $sheet->setCellValue('G1', 'IsAnAccountant');
    $sheet->setCellValue('H1', 'JobTitle');

    $row = 2;  // Начинаем с 2 строки
    while ($data = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $data['EmployeeID']);
        $sheet->setCellValue('B' . $row, $data['Name']);
        $sheet->setCellValue('C' . $row, $data['Surname']);
        $sheet->setCellValue('D' . $row, $data['SecondName']);
        $sheet->setCellValue('E' . $row, $data['HireDate']);
        $sheet->setCellValue('F' . $row, $data['Passport']);
        $sheet->setCellValue('G' . $row, $data['IsAnAccountant']);
        $sheet->setCellValue('H' . $row, $data['JobTitle']);
        $row++;
    }

    // Установка заголовков для загрузки файла
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="employees.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить/Удалить сотрудника</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100" style="background-color: #f5f5f5;">
    <div class="container p-4 bg-white shadow rounded" style="max-width: 500px;">
        <h2 class="text-center mb-4">Добавить сотрудника</h2>
        <form method="post">
            <input type="hidden" name="action" value="add">
            <div class="mb-3">
                <label class="form-label">Имя</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Фамилия</label>
                <input type="text" name="surname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Отчество</label>
                <input type="text" name="secondname" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Дата приёма</label>
                <input type="date" name="hire_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Паспорт</label>
                <input type="text" name="passport" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Должность</label>
                <select name="job_title" class="form-control" required>
                    <option value="Рабочий">Рабочий</option>
                    <option value="Бухгалтер">Бухгалтер</option>
                    <option value="Менеджер">Менеджер</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Добавить</button>
        </form>

        <hr>

        <h2 class="text-center mb-4">Удалить сотрудника</h2>
        <form method="post">
            <input type="hidden" name="action" value="delete">
            <div class="mb-3">
                <label class="form-label">EmployeeID для удаления</label>
                <input type="number" name="employee_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-danger w-100">Удалить</button>
        </form>

        <button class="btn btn-light border m-2" onclick="location.href='FirstPage.php'">назад</button>
        <hr>

        <a href="?export=true" class="btn btn-success w-100 mt-3">Скачать Excel</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>
