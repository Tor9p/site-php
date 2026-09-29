<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


session_start();
require_once("MySiteDB.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $art_id = isset($_POST['art_id']) ? (int)$_POST['art_id'] : 0;
    $comment = trim($_POST['comment'] ?? '');
    
    // Если пользователь залогинен — берем его ID из сессии, если нет — автора по умолчанию (ID=1)
    $author_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
    $created = date("Y-m-d");

    if ($art_id > 0 && $comment !== '') {
        $comment_esc = mysqli_real_escape_string($link, $comment);
        
        $query = "INSERT INTO comments (created, author_id, comment, art_id) 
                  VALUES ('$created', $author_id, '$comment_esc', $art_id)";
        
        mysqli_query($link, $query);
    }
    
    // Возвращаем пользователя обратно к заметке
    header("Location: comments.php?note=" . $art_id);
    exit;
}