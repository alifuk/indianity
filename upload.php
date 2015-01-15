
<?php require_once "/phpuploader/include_phpuploader.php" ?>
<?php session_start(); ?>
<html>
<head>
	<title>CZ9gag - Nejlepší zábava v česku!</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<script type="text/javascript" src="./js/jquery-1.9.1.min.js"></script>
	<div id="container">
		<div id="menu">
			<span id="logo"><a href="index.php"><img src="images/logo.png" alt="cz9gag.cz"></a></span>
			<!--
			<ul>

				<li>Nejlepší</li>
				<li>Všechno</li>

			</ul>
		-->
		<span><a href="upload.php">Nahraj</a></span>
	</div>
	<div id="levySloupec">
		<form action="upload.php" method="post" enctype="multipart/form-data">
			<input name="upload[]" type="file" multiple="multiple" />
			<input type="submit" value="odeslat"> 
		</form>
		<?php

		$uploader=new PhpUploader();
		
		$uploader->MultipleFilesUpload=true;
		$uploader->InsertText="Select multiple files (Max 1000M)";
		
		$uploader->MaxSizeKB=1024000;
		$uploader->AllowedFileExtensions="*.jpg,*.png,*.gif,*.bmp,*.txt,*.zip,*.rar";
		
		$uploader->SaveDirectory="savefiles";
		
		$uploader->FlashUploadMode="Partial";
		
		$uploader->Render();
		
	?>


	}


		//!!!
	$conn->close();


	?>



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