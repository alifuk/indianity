<?php

include("connect.php");
include("cookies.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>CZ9gag - Nejlepší zábava v česku!</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<?php include_once("analytics.php") ?>

	<div id="fb-root"></div>
	<script>
	window.fbAsyncInit = function() {
		FB.init({appId: '1374722609495964', status: true, cookie: true,
			xfbml: true});
	};
	(function() {
		var e = document.createElement('script'); e.async = true;
		e.src = document.location.protocol +
		'//connect.facebook.net/en_US/all.js';
		document.getElementById('fb-root').appendChild(e);
	}());
	</script>


	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>

	<script type="text/javascript">
	


	$(document).ready(function(){
		$('.sharebutton').click(function(e){
			e.preventDefault();
			FB.ui(
			{
				method: 'feed',
				name: $(this).attr("nadpis"),
				link: ' http://www.cz9gag.cz/detail.php?Id='+ $(this).attr("postId"),
				picture: 'http://www.cz9gag.cz/pics/'+ $(this).attr("idecko")+'s.jpg',
				caption: 'cz9gag.cz Nejlepší zábava v česku!',
				/*description: 'cz9gag.cz Nejlepší zábava v česku!',*/
				message: ''
			});
		});


		$.ajaxSetup({ cache: false });


		var nickcisty = 0;
		$("#textnick").click(function(){

			if(nickcisty == 0){
				$("#textnick").attr("value"," ");
				nickcisty = 1;
			}
		});

		var areacisty = 0;
		$("#areakoment").click(function(){

			if(areacisty == 0){
				$("#areakoment").attr("value"," ");
				areacisty = 1;
			}
			

		});


		$(".upvote").click(function(){
			vote($(this).attr("idecko"), "1");
			$(this).addClass("clickedUp");

			var htmlString = $("a[idecko='" + $(this).attr("idecko") + "']").html();
			var res = htmlString.split(" ");
			var numma = parseInt(res[0]) +1;
			$("a[idecko='" + $(this).attr("idecko") + "']").html(numma + " " + res[1]);

		});

		$(".downvote").click(function(){
			vote($(this).attr("idecko"), "-1");
			$(this).addClass("clickedDown");

			var htmlString = $("a[idecko='" + $(this).attr("idecko") + "']").html();
			var res = htmlString.split(" ");
			var numma = parseInt(res[0]) -1;
			$("a[idecko='" + $(this).attr("idecko") + "']").html(numma + " " + res[1]);


		});

		function vote(prispevekId, type){

			$.ajax({ url: 'addvote.php',
				data: {prispevekId: prispevekId, type: type},
				type: 'POST',
				
			});
		}





		$("#buttonodeslat").click(function(){
			koment();
		});

		function koment(){

			$.ajax({ url: 'addkomentar.php',
				data: {textkomentare: $("#areakoment").val(), textnick: $("#textnick").val(), idecko: $("#idecko").val()},
				type: 'POST',
				
			});
			//alert($("#textnick").val());
			setTimeout(function() {
      	// Do something after 5 seconds
      	location.reload();
      	
      }, 500);			

		}


		<?php

  				if(!isset($_GET['Id'])){
  					header("Location: index.php");
  				}
  				$Id = $_GET['Id'];


  				$Id = $conn->real_escape_string($Id);

  				//najdeme nižší Id
		$res = $conn->query("SELECT Id 
			FROM prispevky 
			WHERE Id<'$Id'
			ORDER BY Id DESC
			LIMIT 1") ;

		$res->data_seek(0);

		$lastId = -1;
		$nextId = -1;


		while ($row = $res->fetch_assoc()) {
			$lastId = $row["Id"] ;
		}

		//najdeme vyšší Id
		$res = $conn->query("SELECT Id
			FROM prispevky 
			WHERE Id>'$Id'
			ORDER BY Id ASC
			LIMIT 1") ;
			$res->data_seek(0);
			while ($row = $res->fetch_assoc()) {

			$nextId = $row["Id"] ;

			}



			//pokud nižší id nebylo nalezeno, najdeme maximální Id
		if ($lastId == -1){

			$res = $conn->query("SELECT MAX(Id) as Idecko
			FROM prispevky 
			LIMIT 1") ;
			$res->data_seek(0);
			while ($row = $res->fetch_assoc()) {

			$lastId = $row["Idecko"] ;

			}
		}

		//pokud vyšší id nebylo nalezeno, najdeme minimální Id
		if ($nextId == -1){

			$res = $conn->query("SELECT MIN(Id) as Idecko
			FROM prispevky 
			LIMIT 1") ;
			$res->data_seek(0);
			while ($row = $res->fetch_assoc()) {

			$nextId = $row["Idecko"] ;

			}
		}



  				?>  


  		


		$(document).keydown(function(e) {
			

  			if(e.which == 37 || e.which == 75) { // left or k  pressed    				 
  				window.location.href = './detail.php?Id=<?php echo $nextId; ?>';
  			}
  			else if(e.which == 39 || e.which == 74) { // right  or j  pressed   
  				window.location.href = './detail.php?Id=<?php echo $lastId; ?>';
  			}
  		});





	});
</script>





<div id="top">
	<div id="menu">
		<span id="logo"><a href="index.php"><img src="images/logo.png" alt="cz9gag.cz"></a></span>

		<ul>

			<li class="active"><a href="index.php">Nejlepší</a></li>
			<li><a href="../index.php?tag=new">Nové</a></li>

		</ul>

		<span><a href="upload/uploadpage.php" id="nahraj">Nahraj</a></span>
	</div>
</div>



<div id="container">

	<div id="detail">


		<?php

		$Id = $conn->real_escape_string($Id);

		$res = $conn->query("SELECT Id, nadpis, foto,  (IFNULL(votePrispevku.votes,0) + IFNULL(prispevky.votes,0)) as votes, IFNULL(komentare.komentaru ,0) as komentaru
			FROM prispevky 
			LEFT JOIN (
				SELECT SUM(type) as votes, prispevekId FROM voteprispevky
				GROUP BY prispevekId  
				) as votePrispevku ON votePrispevku.prispevekId = prispevky.Id
		LEFT JOIN (
			SELECT COUNT(Id) as komentaru, prispevekId FROM komentare
			GROUP BY prispevekId  
			) as komentare ON komentare.prispevekId = prispevky.Id

		WHERE Id='$Id'
		LIMIT 1") ;

		$res->data_seek(0);
		while ($row = $res->fetch_assoc()) {


			$votesText = "bodů";
			if($row["votes"] == 1){
				$votesText = "bod";
			}
			if($row["votes"] == 2 ||$row["votes"] == 3 ||$row["votes"] == 4){
				$votesText = "body";
			};


			$komentaruText = "komentářů";
			if($row["komentaru"] == 1){
				$komentaruText = "komentář";
			}
			if($row["komentaru"] == 2 ||$row["komentaru"]== 3 ||$row["komentaru"]== 4){
				$komentaruText = "komentáře";
			}

			echo "<div class='prispevekDetail'><h3>". $row["nadpis"]."</h3>";
			echo "<img src='pics/". $row["foto"].".jpg' class='detailpic'>  <div id='stats'><a href='detail.php?Id=".$row["Id"]."' class='votes'>". $row["votes"]."  ".$votesText."</a> · ";
			echo $row["komentaru"]. " ".$komentaruText ."</div>  ";
			echo "<div class='votes bigvotes'><span class='upvote' idecko='". $row["Id"]."'></span><span class='downvote' idecko='". $row["Id"]."'></span><a href='detail.php?Id=".$row["Id"]."#komentare' class='doKomentu'> </a>";
			echo "<span class='sharebutton bigsharebutton' postId='". $row["Id"]."' idecko='". $row["foto"]."' nadpis='". $row["nadpis"]."'>Sdílet na Facebook</span></div></div>";




		}








		?>

		<br>
		<div id="komentare">
			<h3>Komentáře</h3>

			<form action="addcoment.php" method="post" >				
				<textarea name="koment" id="areakoment">Vložte text komentáře...</textarea>
				<input type="text" value="Vložte nick" id="textnick">
				<input type="hidden" id="idecko" value="<?php echo $Id ?>"> 
				<input type="button" value="Odeslat" id="buttonodeslat">
			</form>

			<?php


			$Id = $conn->real_escape_string($Id);
			$res = $conn->query("SELECT nick, obsah
				FROM komentare	

				WHERE prispevekId='$Id'") ;

			$res->data_seek(0);
			while ($row = $res->fetch_assoc()) {

				echo "<div id='komentar'> ";
				echo "<h2>".$row["nick"]."</h2>";
				echo "<h5>".$row["obsah"]."</h5>";
				echo "</div>";

			}

			?>


		</div>

	</div>




	<?php


	include("sidebar.php");


	?>




</div>

</body>
</html>

<?php 
//!!!
$conn->close();
?>