<?php
	if (isset($_GET['MyRadio'])) {
		$var = $_GET['MyRadio'];
		switch($var) {
			case "Rome":
			case "Paris":
			case "Moscow":
			// case "Russia":
				echo "You choose $var <br>";
				break;
		}
	}


?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.2">
	<title>additional_task</title>
</head>
<body>
	<h1>Задания из методички</h1>
	<br>

	<!-- -----------1 task 4.3--------------------- -->
	<h3>4.3 Обработка HTML-переключателей</h3>

	<form action="lab.php" method="GET">
        <p> Выберите Ваш любимый город: </p><br />
        <p><input type="radio" name="MyRadio" value="Rome">Рим</p>
        <p><input type="radio" name="MyRadio" value="Paris">Париж</p>
        <p><input type="radio" name="MyRadio" value="Moscow">Москва</p>
		<!-- <p><inpit type="radio" name="MyRadio" value="Russia">Россия</p> -->
        <p><input type="submit" name="submit_radio" value="Отправить"></p>
    </form>
	<br>
	<!-- ------------------------------------------- -->


	
</body>
</html>