<?php
session_start();

abstract class Shape {
    abstract public function getArea(): float;
    abstract public function getPerimeter(): float;
}

class Square extends Shape {
    private float $side;

    public function __construct(float $side) {
        $this->side = $side;
    }

    public function getArea(): float {
        return $this->side * $this->side;
    }

    public function getPerimeter(): float {
        return 4 * $this->side;
    }
}

class Rectangle extends Shape {
    private float $width, $height;

    public function __construct(float $width, float $height) {
        $this->width = $width;
        $this->height = $height;
    }

    public function getArea(): float {
        return $this->width * $this->height;
    }

    public function getPerimeter(): float {
        return 2 * ($this->width + $this->height);
    }
}

class Circle extends Shape {
    private float $radius;

    public function __construct(float $radius) {
        $this->radius = $radius;
    }

    public function getArea(): float {
        return pi() * pow($this->radius, 2);
    }

    public function getPerimeter(): float {
        return 2 * pi() * $this->radius;
    }
}

class Triangle extends Shape {
    private float $a, $b, $c, $height;

    public function __construct(float $a, float $b, float $c, float $height) {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
        $this->height = $height;
    }

    public function getArea(): float {
        return ($this->b * $this->height) / 2;
    }

    public function getPerimeter(): float {
        return $this->a + $this->b + $this->c;
    }
}

$shape = null;
$result = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['shape'] ?? '';
    switch ($type) {
        case 'square':
            $shape = new Square(floatval($_POST['side']));
            break;
        case 'rectangle':
            $shape = new Rectangle(floatval($_POST['width']), floatval($_POST['height']));
            break;
        case 'circle':
            $shape = new Circle(floatval($_POST['radius']));
            break;
        case 'triangle':
            $shape = new Triangle(
                floatval($_POST['a']),
                floatval($_POST['b']),
                floatval($_POST['c']),
                floatval($_POST['height'])
            );
            break;
    }
    if ($shape) {
        $_SESSION['last_calculation'] = [
            'area' => $shape->getArea(),
            'perimeter' => $shape->getPerimeter()
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вычисление площади и периметра</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="text-center mb-4">Выберите фигуру и введите параметры</h2>
    <form method="post" class="mb-4">
        <select name="shape" class="form-select mb-3" required>
            <option value="square">Квадрат</option>
            <option value="rectangle">Прямоугольник</option>
            <option value="circle">Круг</option>
            <option value="triangle">Треугольник</option>
        </select>
        <div id="inputs"></div>
        <button type="submit" class="btn btn-primary">Вычислить</button>
    </form>
    
    <?php if (isset($_SESSION['last_calculation'])): ?>
        <div class="alert alert-info">
            <p><strong>Площадь:</strong> <?= $_SESSION['last_calculation']['area'] ?></p>
            <p><strong>Периметр:</strong> <?= $_SESSION['last_calculation']['perimeter'] ?></p>
        </div>
    <?php endif; ?>
    
    <script>
        document.querySelector('select[name="shape"]').addEventListener('change', function () {
            let shape = this.value;
            let inputsDiv = document.getElementById('inputs');
            inputsDiv.innerHTML = '';
            if (shape === 'square') {
                inputsDiv.innerHTML = '<label>Сторона:</label><input type="number" name="side" class="form-control" required>';
            } else if (shape === 'rectangle') {
                inputsDiv.innerHTML = '<label>Ширина:</label><input type="number" name="width" class="form-control" required>' +
                                      '<label>Высота:</label><input type="number" name="height" class="form-control" required>';
            } else if (shape === 'circle') {
                inputsDiv.innerHTML = '<label>Радиус:</label><input type="number" name="radius" class="form-control" required>';
            } else if (shape === 'triangle') {
                inputsDiv.innerHTML = '<label>Сторона A:</label><input type="number" name="a" class="form-control" required>' +
                                      '<label>Сторона B:</label><input type="number" name="b" class="form-control" required>' +
                                      '<label>Сторона C:</label><input type="number" name="c" class="form-control" required>' +
                                      '<label>Высота:</label><input type="number" name="height" class="form-control" required>';
            }
        });
    </script>
</body>
</html>
