<?php 
// Control Structures & Loops

// IF – ELSEIF – ELSE (Grade System Example)

$marks = 82;

if($marks >= 85){
    $grade = "A";
}
elseif($marks >= 75){
    $grade = "B";
}
elseif($marks >= 50){
    $grade = "C";
}
else{
    $grade = "Fail";
}

echo "Your Grade:  " . $grade;

echo "<br>";


// SWITCH Statement (Day Type Example)

$day = "friday";


switch($day){
    
    case "Saturday":
    case "sunday":
        echo "Weekend"; 
        break;

    case "monday":
    case "tuesday":
    case "wednesday":
    case "thursday":
    case "friday" :
        echo "Weekday";
        break;
        
    default : 
        echo "Invalid Day";
}

echo "<br>";


//Authentication Example

$userType = "manager";

if($userType == "admin"){
    echo "Fully Access";
}
elseif($userType == "manager"){
    echo "Manage Team";
}
else{
    echo "view only";
}

echo "<br>";

// Boolean Conditions


$isLoggedIn = "";
if ($isLoggedIn) {
   echo "Dashboard";
}
else {
   echo "Login Page";
}

// Ternary Operator ?


$age = 20;
echo ($age >= 18) ? "Adult" : "Minor";
echo "<br>";


//Login Access System (Multiple Conditions)

$isEmailVerified = true;
$isActive = true;
$isBanned = false;

if($isEmailVerified && $isActive && !$isBanned){
    echo "Login Successful";
}
else{
    echo "Acees Denied";
}
echo "<br>";
 

// Nested If else 

$loggedIn = true;
$role = "editor";

if($loggedIn){
    if($role == "admin"){
        echo "Admin Panel";
    }
    elseif($role == "editor"){
        echo "Editor Dashboarder";
    }
    else{
        echo "user Dashboarder";
    }
}else{
    echo "Please login";
}

echo "<br>";

$membership = "gold";
$years = 1;

if ($membership == "gold") {
    if ($years > 2) {
        echo "Price: 500";
    } else {
        echo "Price: 700";
    }
}
elseif ($membership == "silver") {
    echo "Price: 900";
}
else {
    echo "Price: 1200";
}

echo "<br>";

// switch case Example 
$salary = 65000;

switch (true) {

    case ($salary >= 80000):
        echo "Senior Level";
        break;

    case ($salary >= 50000):
        echo "Mid Level";
        break;

    case ($salary >= 30000):
        echo "Junior Level";
        break;

    default:
        echo "Intern";
}


?>