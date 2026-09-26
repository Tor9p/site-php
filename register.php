<?php

// блокируем вход на страницу
http_response_code(403);
echo "Запрещено."
exit;


session_start();
header('Content-Type: text/html; charset=utf-8');


// если авторизован то редирект на дом
if (isset($_SESSION['user_id'])) {
    header("Location: default.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once("MySiteDB.php");
    
    // кодировка 
    mysqli_set_charset($link, "utf8");

    $login = mysqli_real_escape_string($link, trim($_POST['login']));
    $username = mysqli_real_escape_string($link, trim($_POST['username']));
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);

    // проверка заполнения полей
    if (empty($login) || empty($username) || empty($password)) {
        $error = "Заполните все поля!";
    } elseif ($password !== $password_confirm) {
        $error = "Пароли не совпадают!";
    } else {
        // не занят ли логин
        $check_query = "SELECT id FROM authors WHERE login = '$login'";
        $check_result = mysqli_query($link, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Пользователь с таким логином уже зарегистрирован!";
        } else {
            // Хешируем пароль через MD5 с солью
         // это хуевина устарела и перебором ломаетсяс, мне не нрав  
           //  $salt = 'piter_salt_228';
            // $hashed_password = md5($password . $salt);
			// блять я идиот, я шифрование забыл переписать 

			// ibahjdfybt 
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);


            $insert_query = "INSERT INTO authors (login, password, username, rights) 
                             VALUES ('$login', '$hashed_password', '$username', 'u')";
            
            if (mysqli_query($link, $insert_query)) {
                // Сразу авторизуем нового пользователя в сессии
                $_SESSION['user_id'] = mysqli_insert_id($link);
                $_SESSION['login'] = $login;
                $_SESSION['username'] = $username;
                $_SESSION['rights'] = 'u';

                header("Location: default.php");
                exit();
            } else {
                $error = "Ошибка при регистрации: " . mysqli_error($link);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>

    <h2>Регистрация нового пользователя</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div>
            <label>Логин (для входа):</label><br>
            <input type="text" name="login" required>
        </div>
        <br>
        <div>
            <label>Ваше имя (как отображать на сайте):</label><br>
            <input type="text" name="username" required>
        </div>
        <br>
        <div>
            <label>Пароль:</label><br>
            <input type="password" name="password" required>
        </div>
        <br>
        <div>
            <label>Повторите пароль:</label><br>
            <input type="password" name="password_confirm" required>
        </div>
        <br>
        <button type="submit">Зарегистрироваться</button>
    </form>

    <br>
    <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
    <a href="default.php">← На главную</a>

</body>
</html>