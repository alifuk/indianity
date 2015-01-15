<?php
include("connect.php");
include("cookies.php");

if(isset($_POST['prispevekId']) && isset($_POST['type'])){
	$prispevekId = $_POST['prispevekId'];
	$type = $_POST['type'];


	$prispevekId = $conn->real_escape_string($prispevekId);
	$type = $conn->real_escape_string($type);
	$cookieId = $conn->real_escape_string($cookieId);


	if($type == "1" || $type == "-1"){


		$res = $conn->query("SELECT prispevekId, user FROM voteprispevky WHERE prispevekId='$prispevekId' AND user='$cookieId' ") ;
		$prazdny = true;
		$res->data_seek(0);
		while ($row = $res->fetch_assoc()) {

			$prazdny = false;

		}

		if($prazdny){

			$prispevekId = $conn->real_escape_string($prispevekId);
	$type = $conn->real_escape_string($type);
	$cookieId = $conn->real_escape_string($cookieId);


			$conn->query("INSERT INTO voteprispevky (prispevekId, type, user)
				VALUES ('$prispevekId', '$type', '$cookieId')");

		}




		
		$conn->close();

	}
	

}







?>