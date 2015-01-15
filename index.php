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
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
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


		var voleno = 0;
		$(".upvote").click(function(){
			if(voleno == 0){
				/*voleno = 1;*/
				vote($(this).attr("idecko"), "1");
				$(this).addClass("clickedUp");

				var htmlString = $("a[idecko='" + $(this).attr("idecko") + "']").html();
				var res = htmlString.split(" ");
				var numma = parseInt(res[0]) +1;
				$("a[idecko='" + $(this).attr("idecko") + "']").html(numma + " " + res[1]);
			}
		});

		$(".downvote").click(function(){
			if(voleno == 0){
				/*voleno = 1;*/
				vote($(this).attr("idecko"), "-1");
				$(this).addClass("clickedDown");

				var htmlString = $("a[idecko='" + $(this).attr("idecko") + "']").html();
				var res = htmlString.split(" ");
				var numma = parseInt(res[0]) -1;
				$("a[idecko='" + $(this).attr("idecko") + "']").html(numma + " " + res[1]);

			}
		});

		function nactidalsi(){

			var vystup;
			$.ajax({ url: 'loadnext.php',

				data: {lastId: $("#next").attr("lastId"), tag: $("#next").attr("tag") },
				type: 'POST',
				success: function(output) {	
					vystup = output.split("kurvaaaa");				
					$(".empty").append(vystup[0]);
					$("#next").attr("lastid", vystup[1]);
					nacitani = false;
				}
			});
		}

		/*
		$("#next").click(function(){

			nactidalsi();		
			
		});*/
		var nacitani = false;

		$(window).scroll(function() {
			var y_scroll_pos = window.pageYOffset;          // set to whatever you want it to be

    		if(y_scroll_pos > $( ".predposledni" ).last().position().top && nacitani == false) {
        		nactidalsi();
        		nacitani = true;
    		}
			

		});




		function vote(prispevekId, type){

			$.ajax({ url: 'addvote.php',
				data: {prispevekId: prispevekId, type: type},
				type: 'POST',
				
			});
		}



		$(document).keydown(function(e) {	

  			if(e.which == 37 || e.which == 75) { // left arrow  or k pressed  


  				var y_scroll_pos = window.pageYOffset; 
  				$( ".prispevek" ).each(function( index, element ) {
    // element == this
    //$( element ).css( "backgroundColor", "yellow" );
    				if ( y_scroll_pos +60  < $( this ).position().top ) {
      					//alert(index);

      					$('html, body').animate({
    						scrollTop: $(element).offset().top -60
						}, 100);

      					return false;
    				}
  				});




  				
  			}


  			else if(e.which == 39 || e.which == 74) { // right  or j arrow pressed   
  				$('html, body').animate({
    				scrollTop: $(".prispevek").offset().top
				}, 100);
  			}
  		});



	});
</script>





<div id="top">
	<div id="menu">
		<span id="logo"><a href="index.php"><img src="images/logo.png" alt="cz9gag.cz"></a></span>

		<ul>
			<?php


			if(isset($_GET["tag"]) && $_GET["tag"] == "new"){
				echo '<li ><a href="index.php">Nejlepší</a></li>';
				echo '<li class="active"><a href="index.php?tag=new">Nové</a></li>';
				
			}else{
				echo '<li class="active"><a href="index.php">Nejlepší</a></li>';
				echo '<li><a href="index.php?tag=new">Nové</a></li>';
			}


			?>



		</ul>

		<span><a href="upload/uploadpage.php" id="nahraj">Nahraj</a></span>
	</div>
</div>


<div id="container">

	<div id="levySloupec">


		<?php
		$dbtag = "main";
		if(isset($_GET["tag"])){
			$tag = $_GET["tag"];

			if($tag == "new"){
				$dbtag = "new";
			}
		}

		$sql = "SELECT Id, nadpis, foto,  (IFNULL(votePrispevku.votes,0) + IFNULL(prispevky.votes,0)) as votes, IFNULL(komentare.komentaru ,0) as komentaru
		FROM prispevky 
		LEFT JOIN (
			SELECT SUM(type) as votes, prispevekId FROM voteprispevky
			GROUP BY prispevekId  
			) as votePrispevku ON votePrispevku.prispevekId = prispevky.Id
LEFT JOIN (
	SELECT COUNT(Id) as komentaru, prispevekId FROM komentare
	GROUP BY prispevekId  
	) as komentare ON komentare.prispevekId = prispevky.Id
WHere tag='". $dbtag ."'
ORDER BY datum DESC
LIMIT 10";
$result = $conn->query($sql);
$count = 0;
if ($result->num_rows > 0) {
    // output data of each row
	while($row = $result->fetch_assoc()) {
		$count++;

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
		if($count == 8){
			$predposledni = "predposledni";
		}

		echo "<div class='prispevek ". $predposledni ."'><h3><a href='detail.php?Id=". $row["Id"]."'    > ". $row["nadpis"]."</a></h3>";
		echo "<a href='detail.php?Id=". $row["Id"]."'    ><img src='pics/". $row["foto"].".jpg' class='pic'></a>  <div class='stats'><a href='detail.php?Id=".$row["Id"]."' class='votes' idecko='". $row["Id"]."'>". $row["votes"]." ".$votesText."</a>";
		echo " · <a href='detail.php?Id=".$row["Id"]."#komentare' class='komentaru'>". $row["komentaru"]. " ".$komentaruText ."</a></div>  ";
		echo "<div class='votes'><span class='upvote' idecko='". $row["Id"]."'></span><span class='downvote' idecko='". $row["Id"]."'></span><a href='detail.php?Id=".$row["Id"]."#komentare' class='doKomentu'> </a>";
		echo "<span class='sharebutton' postId='". $row["Id"]."'  idecko='". $row["foto"]."' nadpis='". $row["nadpis"]."'>Facebook</span></div></div>";

		/*if($count == 10){
			echo "<div class='empty'></div>";
			echo "<span id='next' lastid='". $row["Id"]."' tag='".$dbtag."'>Načíst další</span>";
		}*/

	}
} else {
	echo "0 výsledků";
}



?>



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