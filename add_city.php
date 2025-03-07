<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ride";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Add new city
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_city'])) {
    $country = $_POST['country'];
    $name = $_POST['city_name'];
    $is_capital = $_POST['is_capital'] == "yes" ? 1 : 0;
    $per_day = $_POST['per_day'];
    
    $city_id = rand(0, 9999);
    while ($conn->query("SELECT CityID FROM City WHERE CityID = $city_id")->num_rows > 0) {
        $city_id = rand(0, 9999);
    }
    
    if ($is_capital) {
        $per_day += $per_day * 0.2;
    }
    
    $sql = "INSERT INTO City (Country, Name, IsACapital, CityID, PerDay) VALUES ('$country', '$name', '$is_capital', '$city_id', '$per_day')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Город успешно добавлен!');</script>";
    } else {
        echo "Ошибка: " . $conn->error;
    }
}

// Delete city
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_city'])) {
    $city_id_to_delete = $_POST['city_id_to_delete'];
    
    $sql = "DELETE FROM City WHERE CityID = '$city_id_to_delete'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Город успешно удален!');</script>";
    } else {
        echo "Ошибка: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление городами</title>
    <style>
        /* Add styles here */
        body { font-family: Arial, sans-serif; }
        .container { width: 50%; margin: auto; }
        label, input, select { display: block; margin-top: 10px; width: 100%; }
        .buttons { margin-top: 20px; }
        button { background-color: #28a745; color: white; border: none; padding: 15px 20px; font-size: 18px; cursor: pointer; border-radius: 5px; width: 100%; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Добавить город</h2>
        <form method="POST">
            <label>Название города:</label>
            <input type="text" name="city_name" required>
            
            
            <label>Страна нахождения:</label>
            <select name="country">
                <option value="Афганистан">Афганистан</option>
                <option value="Албания">Албания</option>
                <option value="Алжир">Алжир</option>
                <option value="Андорра">Андорра</option>
                <option value="Ангола">Ангола</option>
                <option value="Антигуа">Антигуа</option>
                <option value="Аргентина">Аргентина</option>
                <option value="Армения">Армения</option>
                <option value="Австралия">Австралия</option>
                <option value="Австрия">Австрия</option>
                <option value="Азербайджан">Азербайджан</option>
                <option value="Багамы">Багамы</option>
                <option value="Бахрейн">Бахрейн</option>
                <option value="Бангладеш">Бангладеш</option>
                <option value="Барбадос">Барбадос</option>
                <option value="Беларусь">Беларусь</option>
                <option value="Бельгия">Бельгия</option>
                <option value="Белиз">Белиз</option>
                <option value="Бенин">Бенин</option>
                <option value="Болгария">Болгария</option>
                <option value="Боливия">Боливия</option>
                <option value="Босния и Герцеговина">Босния и Герцеговина</option>
                <option value="Ботсвана">Ботсвана</option>
                <option value="Бразилия">Бразилия</option>
                <option value="Бруней">Бруней</option>
                <option value="Буркина-Фасо">Буркина-Фасо</option>
                <option value="Бурунди">Бурунди</option>
                <option value="Бутан">Бутан</option>
                <option value="Бывшая Югославия">Бывшая Югославия</option>
                <option value="Вануату">Вануату</option>
                <option value="Венгрия">Венгрия</option>
                <option value="Виргинские острова">Виргинские острова</option>
                <option value="Вьетнам">Вьетнам</option>
                <option value="Габон">Габон</option>
                <option value="Гаити">Гаити</option>
                <option value="Гайана">Гайана</option>
                <option value="Гамбия">Гамбия</option>
                <option value="Гана">Гана</option>
                <option value="Гваделупа">Гваделупа</option>
                <option value="Гвинея">Гвинея</option>
                <option value="Гвинея-Бисау">Гвинея-Бисау</option>
                <option value="Германия">Германия</option>
                <option value="Гренада">Гренада</option>
                <option value="Греция">Греция</option>
                <option value="Грузия">Грузия</option>
                <option value="Дания">Дания</option>
                <option value="Джибути">Джибути</option>
                <option value="Доминика">Доминика</option>
                <option value="Доминиканская Республика">Доминиканская Республика</option>
                <option value="Египет">Египет</option>
                <option value="Замбия">Замбия</option>
                <option value="Зимбабве">Зимбабве</option>
                <option value="Израиль">Израиль</option>
                <option value="Индия">Индия</option>
                <option value="Индонезия">Индонезия</option>
                <option value="Иордания">Иордания</option>
                <option value="Ирак">Ирак</option>
                <option value="Иран">Иран</option>
                <option value="Ирландия">Ирландия</option>
                <option value="Исландия">Исландия</option>
                <option value="Испания">Испания</option>
                <option value="Италия">Италия</option>
                <option value="Казахстан">Казахстан</option>
                <option value="Камбоджа">Камбоджа</option>
                <option value="Камерун">Камерун</option>
                <option value="Канада">Канада</option>
                <option value="Катар">Катар</option>
                <option value="Кения">Кения</option>
                <option value="Кипр">Кипр</option>
                <option value="Кирибати">Кирибати</option>
                <option value="Китай">Китай</option>
                <option value="Колумбия">Колумбия</option>
                <option value="Коморы">Коморы</option>
                <option value="Конго">Конго</option>
                <option value="Коста-Рика">Коста-Рика</option>
                <option value="Кот-д'Ивуар">Кот-д'Ивуар</option>
                <option value="Куба">Куба</option>
                <option value="Кувейт">Кувейт</option>
                <option value="Лаос">Лаос</option>
                <option value="Латвия">Латвия</option>
                <option value="Лесото">Лесото</option>
                <option value="Либерия">Либерия</option>
                <option value="Ливан">Ливан</option>
                <option value="Ливия">Ливия</option>
                <option value="Литва">Литва</option>
                <option value="Лихтенштейн">Лихтенштейн</option>
                <option value="Люксембург">Люксембург</option>
                <option value="Маврикий">Маврикий</option>
                <option value="Мавритания">Мавритания</option>
                <option value="Мадагаскар">Мадагаскар</option>
                <option value="Малайзия">Малайзия</option>
                <option value="Мали">Мали</option>
                <option value="Мальдивы">Мальдивы</option>
                <option value="Мальта">Мальта</option>
                <option value="Марокко">Марокко</option>
                <option value="Мартиника">Мартиника</option>
                <option value="Мексика">Мексика</option>
                <option value="Микронезия">Микронезия</option>
                <option value="Мозамбик">Мозамбик</option>
                <option value="Молдова">Молдова</option>
                <option value="Монако">Монако</option>
                <option value="Монголия">Монголия</option>
                <option value="Монтсеррат">Монтсеррат</option>
                <option value="Намибия">Намибия</option>
                <option value="Науру">Науру</option>
                <option value="Непал">Непал</option>
                <option value="Нигер">Нигер</option>
                <option value="Нигерия">Нигерия</option>
                <option value="Нидерланды">Нидерланды</option>
                <option value="Никарагуа">Никарагуа</option>
                <option value="Новая Зеландия">Новая Зеландия</option>
                <option value="Норвегия">Норвегия</option>
                <option value="Оман">Оман</option>
                <option value="Панама">Панама</option>
                <option value="Папуа — Новая Гвинея">Папуа — Новая Гвинея</option>
                <option value="Парагвай">Парагвай</option>
                <option value="Перу">Перу</option>
                <option value="Польша">Польша</option>
                <option value="Португалия">Португалия</option>
                <option value="Пуэрто-Рико">Пуэрто-Рико</option>
                <option value="Республика Конго">Республика Конго</option>
                <option value="Республика Корея">Республика Корея</option>
                <option value="Румыния">Румыния</option>
                <option value="Руанда">Руанда</option>
                <option value="Сальвадор">Сальвадор</option>
                <option value="Самоа">Самоа</option>
                <option value="Сан-Марино">Сан-Марино</option>
                <option value="Сан-Томе и Принсипи">Сан-Томе и Принсипи</option>
                <option value="Сейшельские Острова">Сейшельские Острова</option>
                <option value="Сенегал">Сенегал</option>
                <option value="Сербия">Сербия</option>
                <option value="Сингапур">Сингапур</option>
                <option value="Сирия">Сирия</option>
                <option value="Словакия">Словакия</option>
                <option value="Словения">Словения</option>
                <option value="Соединенные Штаты Америки">Соединенные Штаты Америки</option>
                <option value="Соломоновы Острова">Соломоновы Острова</option>
                <option value="Сомали">Сомали</option>
                <option value="Судан">Судан</option>
                <option value="Суринам">Суринам</option>
                <option value="Сьерра-Леоне">Сьерра-Леоне</option>
                <option value="Тайланд">Тайланд</option>
                <option value="Таджикистан">Таджикистан</option>
                <option value="Таити">Таити</option>
                <option value="Танзания">Танзания</option>
                <option value="Того">Того</option>
                <option value="Тонга">Тонга</option>
                <option value="Тринидад и Тобаго">Тринидад и Тобаго</option>
                <option value="Тунис">Тунис</option>
                <option value="Туркменистан">Туркменистан</option>
                <option value="Турция">Турция</option>
                <option value="Тувалу">Тувалу</option>
                <option value="Уганда">Уганда</option>
                <option value="Узбекистан">Узбекистан</option>
                <option value="Украина">Украина</option>
                <option value="Уругвай">Уругвай</option>
                <option value="Фиджи">Фиджи</option>
                <option value="Филиппины">Филиппины</option>
                <option value="Финляндия">Финляндия</option>
                <option value="Франция">Франция</option>
                <option value="Хорватия">Хорватия</option>
                <option value="Центральноафриканская Республика">Центральноафриканская Республика</option>
                <option value="Чад">Чад</option>
                <option value="Черногория">Черногория</option>
                <option value="Чили">Чили</option>
                <option value="Швейцария">Швейцария</option>
                <option value="Швеция">Швеция</option>
                <option value="Шри-Ланка">Шри-Ланка</option>
                <option value="Эквадор">Эквадор</option>
                <option value="Экваториальная Гвинея">Экваториальная Гвинея</option>
                <option value="Эландские острова">Эландские острова</option>
                <option value="Эмирства">Эмирства</option>
                <option value="Эритрея">Эритрея</option>
                <option value="Эсватини">Эсватини</option>
                <option value="Эстония">Эстония</option>
                <option value="Эфиопия">Эфиопия</option>
                <option value="ЮАР">ЮАР</option>
                <option value="Южная Корея">Южная Корея</option>
                <option value="Южный Судан">Южный Судан</option>
                <option value="Ямайка">Ямайка</option>
                <option value="Япония">Япония</option>
            </select>
            
            <label>Является ли город столицей?</label>
            <select name="is_capital">
                <option value="yes">Да</option>
                <option value="no">Нет</option>
            </select>
            
            <label>Стоимость за день:</label>
            <input type="number" name="per_day" required>
            
            <button type="submit" name="add_city">Добавить город</button>
        </form>
        
        <h2>Удалить город</h2>
        <form method="POST">
            <label>Введите ID города для удаления:</label>
            <input type="number" name="city_id_to_delete" required>
            <button type="submit" name="delete_city">Удалить город</button>
        </form>
    </div>
</body>
</html>