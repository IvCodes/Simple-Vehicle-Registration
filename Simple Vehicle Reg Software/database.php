<?php

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "vehicledb";
$conn = ""; 

try{
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

}
//this is an object

catch(mysqli_sql_exception){
    echo "Connection failed: " .$exception->getMessage();
}

// if($conn){ 
//     echo "you are connected!";
// }

// else { 
//     echo "could not connect!"; 

// }

?> 