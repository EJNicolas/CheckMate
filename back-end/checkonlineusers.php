<?php
//initialize db connection
$db = mysqli_connect("localhost", "root", "", "chess-games");
if($db->connect_errno) {
    $msg = "Database connection failed: ";
    $msg .= mysqli_connect_error();
    $msg .= " (" . mysqli_connect_errno() . ")";
    exit($msg);
}

//looks for all users that are online. In our database, if their online_status variable is 1, they are online
$onlineUsersArray = [];
$queryString = "SELECT username, online_status FROM users WHERE online_status = '1'";
$result = $db->query($queryString);
while($row = $result->fetch_row()){
  $onlineUsersArray += [$row[0] => $row[1]];
}
//print to json to be retrieved by javascript file (online.js)
print(json_encode($onlineUsersArray));
?>
