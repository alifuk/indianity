<?php

error_reporting(0);

// we first include the upload class, as we will need it here to deal with the uploaded file
include('class.upload.php');
include("../connect.php");


// we have three forms on the test page, so we redirect accordingly
if (isset($_POST['action']) ) {

  $foo = new Upload($_FILES['my_field']); 

  if ($foo->uploaded) {
    $foo->mime_check = true;
    if($foo->file_src_name_ext == 'jpeg' ||$foo->file_src_name_ext == 'jpg' ||$foo->file_src_name_ext == 'JPG' ||$foo->file_src_name_ext == 'gif' || $foo->file_src_name_ext == 'bmp' || $foo->file_src_name_ext == 'png' ){




   // save uploaded image with a new name,
   // resized to 100px wide

      $sql = "SELECT Id FROM prispevky ORDER BY Id DESC LIMIT 1";
      $result = $conn->query($sql);
      $nazev = "0";
      if ($result->num_rows > 0) {
    // output data of each row
        while($row = $result->fetch_assoc()) {
          $nazev = $conn->real_escape_string($row["Id"]);
          $foo->file_new_name_body = $nazev ;
        }
      } 

      $nadpis = $conn->real_escape_string(mb_strtoupper(mb_substr($_POST['nadpis'], 0, 1)) . mb_substr($_POST['nadpis'], 1));
      $votes = $conn->real_escape_string(rand ( 2 , 100));


      $conn->query("INSERT INTO prispevky (nadpis, foto, tag, votes)
        VALUES ('$nadpis',  '$nazev', 'new', '$votes')");

      if ($conn->query($sql) === TRUE) {
                //echo "New record created successfully";
      } else {
                //echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $foo->image_resize = true;
      $foo->image_convert = jpg;
      $foo->jpeg_size = 100072;
      $foo->image_x = 450;
      $foo->image_y = 700;
      $foo->image_ratio_y = true;
      $foo->image_watermark       = '../images/cz9gagoverlay.png';
      $foo->image_watermark_x     = 0;
      $foo->image_watermark_y     = 150;

      $foo->image_watermark_no_zoom_in = true;


      $foo->Process('../pics');
      if ($foo->processed) {
             //echo 'střední obrázek vytvořen';
      } else {
             //echo 'error : ' . $foo->error;
      } 


      $foo->file_new_name_body = $nazev."s" ;
      $foo->image_resize          = true;
      $foo->image_ratio_crop      = false;
      $foo->image_ratio_fill = true;
      $foo->image_y               = 260;
      $foo->image_x               = 490;
      $foo->image_convert = jpg;
      $foo->jpeg_size = 20072;
      $foo->Process('../pics');
      if ($foo->processed) {
             //echo 'Miniatura vytvořena';
       $foo->Clean();

     } else {
             //echo 'error : ' . $foo->error;
     }
   } 




 }

}

$conn->close();

            //echo "redirect";
header("Location: /index.php?tag=new");
?>