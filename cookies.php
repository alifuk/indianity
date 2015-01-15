<?php







$cookie_nick = "nick";
$cookieId = -1;
if(!isset($_COOKIE[$cookie_nick])) {
    

$sql = "SELECT Id FROM users ORDER BY Id DESC LIMIT 1";
      $result = $conn->query($sql);
      $nazev = "0";
      if ($result->num_rows > 0) {
    // output data of each row
        while($row = $result->fetch_assoc()) {
          $cookieId = $row["Id"];
        }
      } else{
      	$cookieId = 1;
      }

      $cookieId = $conn->real_escape_string($cookieId);
      $ip = $conn->real_escape_string($_SERVER['REMOTE_ADDR']);
      $iphttp = $conn->real_escape_string(getenv('HTTP_X_FORWARDED_FOR'));
      $conn->query("INSERT INTO users (nick, ip, iphttp)
        VALUES ('$cookieId',  '$ip', '$iphttp')");

      if ($conn->query($sql) === TRUE) {
                //echo "New record created successfully";
      } else {
                //echo "Error: " . $sql . "<br>" . $conn->error;
      }




      setcookie($cookie_nick, $cookieId, time() + (86400 * 30 * 12), "/"); // 86400 = 1 day
      //echo "cookie set to:" + $cookieId;

} else {
	$cookieId = $_COOKIE[$cookie_nick];
    //echo "Cookie '" . $cookie_nick . "' is set!<br>";
    //echo "Value is: " . $_COOKIE[$cookie_nick];
}
















?>