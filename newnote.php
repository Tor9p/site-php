<?php
session_start();

$is_Auth = isset($_SESSION['user_id']);

if (!$is_Auth) {
	http_response_code(401);
	// echo "Не авторизован!";
	header('Location: default.php');
	exit;
}


// добавить логику проверку авторизации пользователя
// если не авторизован редирект --> логин.php 
// completes



if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
	require_once('MySiteDB.php');
	mysqli_set_charset($link, "utf8");

	// trim line !abuse sqli
	$title_save = mysqli_real_escape_string($link, trim($_POST['title']));
	$article_save = mysqli_real_escape_string($link, trim($_POST['article']));

	$query = "INSERT INTO notes (title, article) VALUES ('$title_save', '$article_save')";

	$created_note = mysqli_query($link, $query);

	// redirect on default page site
	header('Location: default.php' );
	exit;

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=sad, initial-scale=1.0">
	<title>Новая заметка</title>
</head>
<body>
	 <h1>Добавление новой заметки</h1>

	<!-- form -->
    <form action="newnote.php" method="POST">
        
	<!-- title -->
        <div>
            <label for="title">Заголовок заметки:</label>
            <br>
            <input type="text" id="title" name="title" required placeholder="Введите заголовок">
			<!-- // $title_page	= trim($_POST['title']);
			// $article = trimm($_POST['title']); -->

        </div>

        <br>

		 <!-- article )=(text) -->
        <div>
            <label for="article">Текст заметки:</label>
            <br>
            <textarea id="article" name="article" rows="10" cols="50" required placeholder="Напишите текст вашей заметки..."></textarea>
        </div>

        <br>

        <!-- Кнопка отправки формы -->
        <div>
            <button type="submit" name="add_note_btn">Опубликовать заметку</button>
        </div>

    </form>

    <br>
</body>
</html>

