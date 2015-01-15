<?php
include("connect.php");
include("cookies.php");

if(isset($_POST['lastId']) && isset($_POST['tag']) ){
	$lastId = $_POST['lastId'];
	$tag = $_POST['tag'];


	$cookieId = $conn->real_escape_string($cookieId);
	$lastId = $conn->real_escape_string($lastId);	
	$tag = $conn->real_escape_string($tag);

	$conn->query("INSERT INTO loadnext (user, tag)
				VALUES ('$cookieId', '$tag')");




	
		$sql = "SELECT Id, nadpis, foto,  IFNULL(votePrispevku.votes,0) as votes, IFNULL(komentare.komentaru ,0) as komentaru
			FROM prispevky 
			LEFT JOIN (
				SELECT SUM(type) as votes, prispevekId FROM voteprispevky
				GROUP BY prispevekId  
				) as votePrispevku ON votePrispevku.prispevekId = prispevky.Id
LEFT JOIN (
	SELECT COUNT(Id) as komentaru, prispevekId FROM komentare
	GROUP BY prispevekId  
	) as komentare ON komentare.prispevekId = prispevky.Id
WHere tag='". $tag ."' AND Id < '".$lastId."'
ORDER BY Id DESC
LIMIT 10";
$result = $conn->query($sql);
$lastnumma = 0;
$count = 1;
if ($result->num_rows > 0) {
    // output data of each row
	while($row = $result->fetch_assoc()) {
		

		$votesText = "bodů";
		if($row["votes"] == 1){
			$votesText = "bod";
		}
		if($row["votes"] == 2 ||$row["votes"] == 3 ||$row["votes"] == 4){
			$votesText = "body";
		}


		$komentaruText = "komentářů";
		if($row["komentaru"] == 1){
			$komentaruText = "komentář";
		}
		if($row["komentaru"] == 2 ||$row["komentaru"]== 3 ||$row["komentaru"]== 4){
			$komentaruText = "komentáře";
		}
		$predposledni = "";
		if($count = 8){
			$predposledni = "predposledni";
		}
		echo "<div class='prispevek ". $predposledni ."'><h3><a href='detail.php?Id=". $row["Id"]."'    > ". $row["nadpis"]."</a></h3>";
		echo "<a href='detail.php?Id=". $row["Id"]."'    ><img src='pics/". $row["foto"].".jpg' class='pic'></a>  <div class='stats'><a href='detail.php?Id=".$row["Id"]."' class='votes' idecko='". $row["Id"]."'>". $row["votes"]." ".$votesText."</a>";
		echo " · <a href='detail.php?Id=".$row["Id"]."#komentare' class='komentaru'>". $row["komentaru"]. " ".$komentaruText ."</a></div>  ";
		echo "<div class='votes'><span class='upvote' idecko='". $row["Id"]."'></span><span class='downvote' idecko='". $row["Id"]."'></span><a href='detail.php?Id=".$row["Id"]."#komentare' class='doKomentu'> </a>";
		//echo "<span class='sharebutton' postId='". $row["Id"]."'  idecko='". $row["foto"]."' nadpis='". $row["nadpis"]."'>Facebook</span></div></div>";
		
		$lastnumma = $row["Id"];
		$count++;
	}
} 

echo "kurvaaaa" . $lastnumma ;




		
		$conn->close();

	}
	









?>