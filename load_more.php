<?php 
	require_once("MySiteDB.php");

	// смотрим какие смещение (прочитано кол-во статей)
	$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;


	// + next 5 statey
	$query = "SELECT * FROM notes  ORDER BY created DESC, id DESC LIMIT 5 OFFSET $offset";
	$select_note = mysqli_query($link, $query);

	if (mysqli_num_rows($select_note) > 0) {
		while ($note = mysqli_fetch_array($select_note)) {
			echo "<div class='note'>";

			echo "<h3><a href='comments.php?note=" . $note['id'] . "'>" . htmlspecialchars($note['title']) . "</a></h3>";
        
			$text = $note['article'];
			$shortText = (mb_strlen($text, 'UTF-8') > 200) ? mb_substr($text, 0, 200, 'UTF-8') . '...' : $text;
			
			echo "<p>" . nl2br(htmlspecialchars($shortText)) . "</p>";
			echo "<small class='meta-date'>Дата публикации: " . htmlspecialchars($note['created']) . "</small>";
			echo "</div>";
		}
		
	} else {
			echo "done";
		}
?>