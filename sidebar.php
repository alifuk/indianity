
	
	<div id="pravySloupec">

		<h2>Oblíbené</h2>
		<?php

		$sql = "SELECT Id, nadpis, foto
FROM `prispevky` 
Left Join (
select sum(type) as votes , prispevekId 
    FROM voteprispevky
    GROUP BY prispevekId
    
    ) as voty ON voty.prispevekId = prispevky.Id
order by (IFNULL(voty.votes,0) + IFNULL(prispevky.votes,0)) DESC LIMIT 40";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	while($row = $result->fetch_assoc()) {

		echo " <div class='sidePrispevek'> <a href='detail.php?Id=". $row["Id"]."' ><img src='pics/". $row["foto"]."s.jpg' class='sidepic'></a><br><h4><a href='detail.php?Id=". $row["Id"]."' >". $row["nadpis"]." </a></h4></div> ";
	



	}
} else {
	echo "0 výsledků";
}
	




		?>

	</div>