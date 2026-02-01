<?php

// Session stores data on server.



//login pase
session_start();

$_SESSION["username"] = "InternName";

echo "User logged in";
echo "<br>";

// dashboard
echo "Welcome " . $_SESSION["username"];
echo "<br>";

//LOGOUT

session_destroy();

echo "Logged out";
echo "<br>";

//How Session Works Internally

//Browser → Request → Server creates session
// Server → Stores data
// Server → Sends SessionID cookie
// Browser → Sends SessionID next request

// Professional Example (Login Flow)
$userValid = true;

if($userValid){
   $_SESSION["user_id"] = 12;
}

if(isset($_SESSION["user_id"])) {
   echo "Authenticated";
}
echo "<pre>";
print_r($_SESSION);
echo "</pre>"




?>