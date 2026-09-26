<?php

// блокируем вход на страницу
http_response_code(403);
echo "Запрещено."
exit;


session_start();
$error = '';


if (isset($_SESSION['user_id'])) {
    header("Location: default.php");
    exit();
}

// инициализация счётчика
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

// проверка блокировки по времени
if ($_SESSION['attempts'] > 5 && (time() - $_SESSION['last_attempt_time']) < 90) {
    $blocked = true;
    $error = "Слишком много попыток. Подождите несколько минут.";
} else {
    // сброс после таймаута
    if ($_SESSION['attempts'] > 5 && (time() - $_SESSION['last_attempt_time']) >= 90) {
        $_SESSION['attempts'] = 3;
    }
    $blocked = false;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$blocked) {
    require_once("MySiteDB.php");
    mysqli_set_charset($link, "utf8");

    $login = mysqli_real_escape_string($link, trim($_POST['login']));
    $password = mysqli_real_escape_string($link, trim($_POST['password'])); 

    $query = "SELECT * FROM authors WHERE login = '$login'";
    $result = mysqli_query($link, $query);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['attempts'] = 0;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['rights'] = $user['rights'];
            
            header("Location: default.php");
            exit();

        } else {
            $_SESSION['attempts']++;
            $_SESSION['last_attempt_time'] = time();
            $error = "Неверный логин или пароль";
        }

    } else {
        $_SESSION['attempts']++;
        $_SESSION['last_attempt_time'] = time();
        $error = "Неверный логин или пароль";
    }
}
?>