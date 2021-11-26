<?php
//session_start();
//include("onlineheader.php");
$db = mysqli_connect("localhost", "root", "", "chess-games");
if($db->connect_errno) {
    $msg = "Database connection failed: ";
    $msg .= mysqli_connect_error();
    $msg .= " (" . mysqli_connect_errno() . ")";
    exit($msg);
}

$onlineUsersArray = [];
$queryString = "SELECT username, online_status FROM users WHERE online_status = '1'";
$result = $db->query($queryString);
while($row = $result->fetch_row()){
  $onlineUsersArray += [$row[0] => $row[1]];
}

print(json_encode($onlineUsersArray));

?>
