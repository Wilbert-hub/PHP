<?php
require_once('tcpdf/tcpdf.php');

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8"); // Устанавливаем кодировку UTF-8

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
require_once __DIR__ . '/tcpdf/tcpdf.php';
// Создание PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Отчет');
$pdf->SetSubject('Данные из базы');
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();

// Заголовок
$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Cell(0, 10, 'Список данных', 0, 1, 'C');

// Установка шрифта
$pdf->SetFont('dejavusans', '', 10);

// Запрос данных из БД
$sql = "SELECT id, Name, Age FROM users";
$result = $conn->query($sql);

// Проверяем, есть ли данные
if ($result->num_rows > 0) {
    // Заголовки таблицы
    $pdf->Cell(20, 10, "ID", 1, 0, 'C');
    $pdf->Cell(80, 10, "Имя", 1, 0, 'C');
    $pdf->Cell(30, 10, "Возраст", 1, 1, 'C');

    // Вывод данных
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(20, 10, $row['id'], 1, 0, 'C');
        $pdf->Cell(80, 10, $row['Name'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['Age'], 1, 1, 'C');
    }
} else {
    $pdf->Cell(0, 10, "Нет данных.", 1, 1, 'C');
}

$conn->close();

// Вывод PDF
$pdf->Output('report.pdf', 'I');
?>
