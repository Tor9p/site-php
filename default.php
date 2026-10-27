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

<nav class="top-nav">
    <div class="nav-left">
        <?php if ($isAuth): ?>
            <a href="newnote.php">Новая заметка</a>
            <a href="email.php">Отправить сообщение</a>
            <a href="photo.php">Добавить фото</a>
            <a href="inform.php">Статистика</a>
            
            <?php if ($isAdmin): ?>
                <!-- rights = 'a') -->
                <a href="admin.php">Администратору</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="nav-right">
        <?php if ($isAuth): ?>
            <span>Привет, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            <a href="logout.php">Выход</a>
        <?php else: ?>
            <!-- гость -->
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
        // Заголовок + link in text
        echo "<h3><a href='comments.php?note=" . $note['id'] . "'>" . htmlspecialchars($note['title']) . "</a></h3>";
       
        # echo "<p>" . nl2br(htmlspecialchars($note['article'])) . "</p>";
        
        // Логика обрезки текста до 200 символов:
        $text = $note['article'];
        if (mb_strlen($text, 'UTF-8') > 200) {
            // Обрезаем до 200 символов и ...
            $shortText = mb_substr($text, 0, 200, 'UTF-8') . '...';
        } else {
            $shortText = $text;
        }
			// мне не нравится такой подход
			// это лучше:
		// $text = $note['article'];
		//$shortText = (mb_strlen($text, 'UTF-8') > 200) ? mb_substr($text, 0, 200, 'UTF-8') . '...' : $text;



        
        // Вывод анонса
        echo "<p>" . nl2br(htmlspecialchars($shortText)) . "</p>";
        
         echo "<small class='meta-date'>Дата публикации: " . htmlspecialchars($note['created']) . "</small>";
        echo "</div>";
    }

} 
	# echo '<button type="button">Click Me pls</button>';
	echo '<button type="submit" name="count_zer_button">Click Me pls</button>';



	else {
    echo "<p>Заметок пока нет.</p>";
}
?>

<!-- AI generated syka blyat -->
</div> <!-- Конец контейнера заметок -->

<!-- 2. ИСПРАВИЛИ КНОПКУ: тип button, убрали форму, починили синтаксис else -->
<?php if ($total_notes > 5): ?>
    <button type="button" id="load-more-btn">Click Me pls</button>
<?php endif; ?>

<!-- 3. МАГИЯ JAVASCRIPT (Дописывает статьи снизу) -->
<script>
let currentOffset = 5; // Мы уже вывели первые 5 штук через PHP выше

document.getElementById('load-more-btn').addEventListener('click', function() {
    let btn = this;
    btn.innerText = 'Загрузка...';
    btn.disabled = true;

    // Скрыто запрашиваем твой файл load_more.php, передавая ему текущее смещение
    fetch('load_more.php?offset=' + currentOffset)
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'done') {
                btn.innerText = 'Статей больше нет';
                btn.disabled = true;
            } else {
                // Добавляем новые статьи строго в низ контейнера
                document.getElementById('notes-container').insertAdjacentHTML('beforeend', data);
                currentOffset += 5; // Увеличиваем смещение для следующего клика
                btn.innerText = 'Click Me pls';
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            btn.innerText = 'Ошибка загрузки';
            btn.disabled = false;
        });
});
</script>

</body>
</html>