<?php

# проверка автризации
session_start();
// вошел ли пользователь вообще
$isAuth = isset($_SESSION['user_id']);

// является ли пользователь администратором
$isAdmin = ($isAuth && isset($_SESSION['rights']) && $_SESSION['rights'] === 'a');

require_once("MySiteDB.php"); 
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Главная страница сайта</title>
    <link rel="stylesheet" href="static/default.css">

</head>
<body>

<!-- <nav>
    <a href="login.html">Вход</a>
    <a href="newnote.php">Новая заметка</a>
    <a href="email.php">Отправить сообщение</a>
    <a href="photo.php">Добавить фото</a>
    <a href="inform.php">Статистика</a>
    <a href="admin.php">Администратору</a>
    <a href="logout.php" class="logout">Выход</a>
</nav> -->


<!-- <nav class="top-nav">
    <div class="nav-left">
        <a href="login.html">Вход</a>
        <a href="newnote.php">Новая заметка</a>
        <a href="email.php">Отправить сообщение</a>
        <a href="photo.php">Добавить фото</a>
        <a href="inform.php">Статистика</a>
        <a href="admin.php">Администратору</a>
    </div>
    <div class="nav-right">
        <a href="logout.php">Выход</a>
    </div>
</nav> -->



<nav class="top-nav">
    <div class="nav-left">
        <?php if ($isAuth): ?>
            <a href="newnote.php">Новая заметка</a>
            <a href="email.php">Отправить сообщение</a>
            <a href="photo.php">Добавить фото</a>
            <a href="inform.php">Статистика</a>
            
            <?php if ($isAdmin): ?>
                <!-- Видно только админу (rights = 'a') -->
                <a href="admin.php">Администратору</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="nav-right">
        <?php if ($isAuth): ?>
            <!-- Если авторизован: имя и кнопка выхода -->
            <span>Привет, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            <a href="logout.php">Выход</a>
        <?php else: ?>
            <!-- Если гость: кнопка входа -->
            <a href="login.php">Вход</a>
        <?php endif; ?>
    </div>
</nav>

<hr>

<p>Рад приветствовать вас на страницах моего сайта, посвященного путешествиям.</p>

<h2>Все заметки:</h2>

<?php
// Запрос к базе данных: новые заметки сверху (п. 6.1 методички)
$query = "SELECT * FROM notes ORDER BY created DESC, id DESC";
$select_note = mysqli_query($link, $query);

$total_notes = mysqli_num_rows($select_note);
// логика для вывода не более5 последних заметок
// чтобы не нагружать БД на этом блядском бегете
if ($total_notes > 0) {
	$counter = 0;
	while ($note = mysqli_fetch_array($select_note)) {
	// logic 5 notes on page syka
	if ($total_notes > 5 && $counter >= 5) {
		break;
	}
	$counter++;
	
	// if (mysqli_num_rows($select_note) > 0) {
    
        echo "<div class='note'>";
        // Заголовок является ссылкой на страницу комментариев (п. 3.2 методички)
        echo "<h3><a href='comments.php?note=" . $note['id'] . "'>" . htmlspecialchars($note['title']) . "</a></h3>";
       
        # echo "<p>" . nl2br(htmlspecialchars($note['article'])) . "</p>";
        
        // Логика обрезки текста до 200 символов:
        $text = $note['article'];
        if (mb_strlen($text, 'UTF-8') > 200) {
            // Обрезаем до 200 символов и добавляем троеточие
            $shortText = mb_substr($text, 0, 200, 'UTF-8') . '...';
        } else {
            // Если текст короче 200 символов — выводим как есть
            $shortText = $text;
        }
        
        // Вывод анонса
        echo "<p>" . nl2br(htmlspecialchars($shortText)) . "</p>";
        
         echo "<small class='meta-date'>Дата публикации: " . htmlspecialchars($note['created']) . "</small>";
        echo "</div>";
    }
} else {
    echo "<p>Заметок пока нет.</p>";
}
?>

</body>
</html>