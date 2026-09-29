<?php
session_start();
session_unset();    // Очищает переменные $_SESSION
session_destroy();  // Уничтожает сессию

header("Location: default.php");
exit();