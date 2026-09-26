<?php


session_start();
header('Content-Type: text/html; charset=utf-8')


// редирект
if (isset($_SESSION['user_id'])) {
    header("Location: default.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //$link = mysqli_connect("localhost", "d901193x_onedb", "11111", "d901193x_onedb");
    require_once("MySiteDB.php");
    
	// кодировка
    mysqli_set_charset($link, "utf8");

    $login = mysqli_real_escape_string($link, trim($_POST['login']));
    $password = trim($_POST['password']);

    // щем пользователя по логину
    $query = "SELECT * FROM authors WHERE login = '$login'";
    $result = mysqli_query($link, $query);
    
}
    
    
    if ($user = mysqli_fetch_assoc($result)) {
		
    //сверяем введенный пароль с хешем
		if (password_verify($password, $user['password'])) {
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['rights'] = $user['rights'];
			
			header("Location: default.php");
			exit();
			} else {
				$error = "Неверный логин или пароль";
			}
		} else {
			$error = "Пользователь не найден!";
		}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
</head>
<body>

    <h2>Вход в систему</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div>
            <label>Логин:</label><br>
            <input type="text" name="login" required>
        </div>
        <br>
        <div>
            <label>Пароль:</label><br>
            <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">Войти</button>
    </form>

    <br>
    <p>Нет аккаунта? не мои проблемы)</p>
    <a href="default.php">← На главную</a>

</body>
</html>