<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ride";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
require_once('tcpdf/tcpdf.php');
if (isset($_POST['generate_pdf'])) {
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('My App');
    $pdf->SetTitle('City and Employee List');
    $pdf->SetMargins(10, 10, 10);
    
    // Используем шрифт, поддерживающий кириллицу
    $pdf->SetFont('dejavusans', '', 12);
    
    // Устанавливаем кодировку UTF-8
    $pdf->setLanguageArray(array('a_meta_charset' => 'UTF-8'));
    
    $pdf->AddPage();

    // Город
    $html = '<h2>Список городов</h2><table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;"><tr><th style="background-color: #f2f2f2;">Страна</th><th style="background-color: #f2f2f2;">Город</th><th style="background-color: #f2f2f2;">Столица</th><th style="background-color: #f2f2f2;">Цена в день</th></tr>';
    
    $result = $conn->query("SELECT Country, Name, IsACapital, PerDay FROM City");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . $row['Country'] . '</td>';
            $html .= '<td>' . $row['Name'] . '</td>';
            $html .= '<td>' . ($row['IsACapital'] ? 'Да' : 'Нет') . '</td>';
            $html .= '<td>' . $row['PerDay'] . '</td>';
            $html .= '</tr>';
        }
    }
    $html .= '</table>';
    
    $pdf->writeHTML($html, true, false, true, false, '');
    
    // Сотрудники
    $html = '<h2>Список сотрудников</h2><table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;"><tr><th style="background-color: #f2f2f2;">Имя</th><th style="background-color: #f2f2f2;">Фамилия</th><th style="background-color: #f2f2f2;">Отчество</th><th style="background-color: #f2f2f2;">Дата найма</th><th style="background-color: #f2f2f2;">Паспорт</th><th style="background-color: #f2f2f2;">Должность</th><th style="background-color: #f2f2f2;">Бухгалтер</th></tr>';
    
    $result = $conn->query("SELECT Name, Surname, SecondName, HireDate, Passport, JobTitle, IsAnAccountant FROM Employee");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . $row['Name'] . '</td>';
            $html .= '<td>' . $row['Surname'] . '</td>';
            $html .= '<td>' . $row['SecondName'] . '</td>';
            $html .= '<td>' . $row['HireDate'] . '</td>';
            $html .= '<td>' . $row['Passport'] . '</td>';
            $html .= '<td>' . $row['JobTitle'] . '</td>';
            $html .= '<td>' . ($row['IsAnAccountant'] ? 'Да' : 'Нет') . '</td>';
            $html .= '</tr>';
        }
    }
    $html .= '</table>';
    
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('city_and_employee_list.pdf', 'D');
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Города и Сотрудники</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h2 {
            color: #2c3e50;
        }
        form {
            margin: 20px 0;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        .btn-back {
            background-color: #ecf0f1;
            color: #2c3e50;
            border: 1px solid #bdc3c7;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn-back:hover {
            background-color: #bdc3c7;
        }
    </style>
</head>
<body>
    <h2>Сохранить список городов и сотрудников в PDF</h2>
    <form method="POST">
        <button type="submit" name="generate_pdf">Сохранить в PDF</button>
    </form>
    <button class="btn-back" onclick="location.href='FirstPage.php'">Назад</button>
</body>
</html>
