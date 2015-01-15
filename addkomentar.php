<?php
include("connect.php");
include("cookies.php");

if(isset($_POST['textkomentare']) && isset($_POST['textnick']) && isset($_POST['idecko'])){
	$textkomentare = $_POST['textkomentare'];
	$textnick = $_POST['textnick'];
	$idecko = $_POST['idecko'];


	$textkomentare = $conn->real_escape_string($textkomentare);
	$textnick = $conn->real_escape_string($textnick);
	$idecko = $conn->real_escape_string($idecko);





	$conn->query("INSERT INTO komentare (nick, obsah, prispevekId)
		VALUES ('$textnick', '$textkomentare', '$idecko')");
	$conn->close();

	
	

}







?>