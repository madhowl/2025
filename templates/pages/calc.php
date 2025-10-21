<?php include_once(TEMPLATES_PATH.'/header.php');?>

<div class="content">
    <div class="sidebar">
        <?php include_once(TEMPLATES_PATH.'/sidebar.php');?>
    </div>

    <div class="articles">
        <div class="calculator">
            <h2>Простой калькулятор</h2>
            <form method="post" action="">
                <input type="number" name="num1" placeholder="Число 1" required>
                <select name="operator" required>
                    <option value="+">+</option>
                    <option value="-">-</option>
                    <option value="*">*</option>
                    <option value="/">/</option>
                </select>
                <input type="number" name="num2" placeholder="Число 2" required>
                <button type="submit">Рассчитать</button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $num1 = $_POST['num1'];
                $operator = $_POST['operator'];
                $num2 = $_POST['num2'];

                switch ($operator) {
                    case '+':
                        $result = $num1 + $num2;
                        break;
                    case '-':
                        $result = $num1 - $num2;
                        break;
                    case '*':
                        $result = $num1 * $num2;
                        break;
                    case '/':
                        if ($num2 == 0) {
                            $result = "Ошибка: деление на ноль!";
                        } else {
                            $result = $num1 / $num2;
                        }
                        break;
                    default:
                        $result = "Неверный оператор!";
                }

                echo "<h3>Результат: $result</h3>";

                // Сохранение истории в файл
                $historyFile = 'calculator_history.txt';
                $historyData = date('Y-m-d H:i:s') . " - $num1 $operator $num2 = $result\n";
                file_put_contents($historyFile, $historyData, FILE_APPEND);
            }
            ?>

            <h3>История операций</h3>
            <?php
            // Чтение истории из файла и отображение ее
            if (file_exists('calculator_history.txt')) {
                $history = file_get_contents('calculator_history.txt');
                echo "<pre>$history</pre>";
            } else {
                echo "История операций пуста.";
            }
            ?>
        </div>
    </div>
</div>

<?php include_once(TEMPLATES_PATH.'/footer.php');?>

