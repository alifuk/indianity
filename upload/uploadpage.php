
<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>CZ9gag - Nejlepší zábava v česku!</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body><?php include_once("../analytics.php") ?>
	<script type="text/javascript" src="./js/jquery-1.9.1.min.js"></script>
	
	<div id="top">
		<div id="menu">
			<span id="logo"><a href="../index.php"><img src="../images/logo.png" alt="cz9gag.cz"></a></span>
			
			<ul>

				<li><a href="../index.php">Nejlepší</a></li>
				<li><a href="../index.php?tag=new">Nové</a></li>

			</ul>
			
			<span><a href="../upload/uploadpage.php" id="nahraj">Nahraj</a></span>
		</div>
	</div>
	<div id="container">
		<div id="levySloupec">

			<fieldset class="right">
				<legend>Nahrání obrázku</legend>
				<p>CZ9GAG je nejlepší zdroj zábavy v česku. Sdílejte vše co Vám připadá zajímavé, obdržte reakce a zjistěte, co Vás rozesměje. </p>
				<p>Lze nahrávat POUZE obrázky (jpeg, jpg, JPG, gif, bmp, png), jiný soubor se nenahraje. </p>
				<form name="form2" enctype="multipart/form-data" method="post" action="./upload.php" />
				<p><input type="file" size="32" name="my_field" value="" /></p><br>
				<p>Nadpis:</p><input type="text" value="" name="nadpis" maxlength="100" size="50"/>
				<p class="button"><input type="hidden" name="action" value="image" />
					<input type="submit" name="Submit" value="Nahrát!" /></p>
				</form>
			</fieldset>
			<script type="text/javascript">

			window.onload = function () {

				function xhr_send(f, e) {
					if (f) {
						xhr.onreadystatechange = function(){
							if(xhr.readyState == 4){
								document.getElementById(e).innerHTML = xhr.responseText;
							}
						}
						xhr.open("POST", "upload.php?action=xhr");
						xhr.setRequestHeader("Cache-Control", "no-cache");
						xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
						xhr.setRequestHeader("X-File-Name", f.name);
						xhr.send(f);
					}
				}

				function xhr_parse(f, e) {
					if (f) {
						document.getElementById(e).innerHTML = "File selected : " + f.name + "(" + f.type + ", " + f.size + ")";
					} else {
						document.getElementById(e).innerHTML = "No file selected!";
					}
				}

				function dnd_hover(e) {
					e.stopPropagation();
					e.preventDefault();
					e.target.className = (e.type == "dragover" ? "hover" : "");  
				}

				var xhr = new XMLHttpRequest();

				if (xhr && window.File && window.FileList) {

        // xhr example
        var xhr_file = null;
        document.getElementById("xhr_field").onchange = function () {
        	xhr_file = this.files[0];
        	xhr_parse(xhr_file, "xhr_status");
        }
        document.getElementById("xhr_upload").onclick = function (e) {
        	e.preventDefault();
        	xhr_send(xhr_file, "xhr_result");
        }

        // drag and drop example
        var dnd_file = null; 
        document.getElementById("dnd_drag").style.display = "block";
        document.getElementById("dnd_field").style.display = "none";
        document.getElementById("dnd_drag").ondragover = function (e) {
        	dnd_hover(e);
        }
        document.getElementById("dnd_drag").ondragleave = function (e) {
        	dnd_hover(e);
        }
        document.getElementById("dnd_drag").ondrop = function (e) {
        	dnd_hover(e);
        	var files = e.target.files || e.dataTransfer.files;
        	dnd_file = files[0];
        	xhr_parse(dnd_file, "dnd_status");
        }
        document.getElementById("dnd_field").onchange = function (e) {
        	dnd_file = this.files[0];
        	xhr_parse(dnd_file, "dnd_status");
        }
        document.getElementById("dnd_upload").onclick = function (e) {
        	e.preventDefault();
        	xhr_send(dnd_file, "dnd_result");
        }

    }
}
</script>










<script type='text/javascript'>
function CuteWebUI_AjaxUploader_OnTaskComplete(task)
{
	var div=document.createElement("DIV");
	var link=document.createElement("A");
	link.setAttribute("href","savefiles/"+task.FileName);
	link.innerHTML="You have uploaded file : savefiles/"+task.FileName;
	link.target="_blank";
	div.appendChild(link);
	document.body.appendChild(div);
}
</script>

</div>
</div>

</body>
</html>