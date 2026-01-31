<?php

//Master commonly used built-in functions.

$text = "  Hello World  ";

// Remove spaces
$trimmed = trim($text);

// Convert to lowercase
$lowercase = strtolower($trimmed);

// Replace word
$result = str_replace("world", "php", $lowercase);

echo $result;  // hello php

echo "<br>";



$numbers = [1, 2, 3, 4];

// Check if 5 exists
if (in_array(5, $numbers)) {
    echo "5 exists<br>";
} else {
    echo "5 not found<br>";
}

// Add a number
array_push($numbers, 5);

// Another array
$moreNumbers = [6, 7];

// Merge arrays
$merged = array_merge($numbers, $moreNumbers);

// Print result
echo "<pre>";
print_r($merged);
echo "</pre>";



// Many in build function 

// 1. strlen() → String Length

echo "<br>";

$name = "Umang";
echo strlen($name);   // 5


//2. trim() → Remove Extra Spaces

echo "<br>";
$text = "    Hello umang   ";
echo  trim($text);     // Hello umang


// 3. explode() → String → Array

echo "<br>";

$skills = "PHP,HTML,CSS";
$arr = explode(",", $skills);

print_r($arr) ; 

// 4. implode() → Array → String

echo "<br>";

$colors = ["Red", "Green", "Blue"];
echo implode("-", $colors);

// 5. array_map() → Apply Function to Each Element

echo "<br>";

$numbers = [1, 2, 3, 4];

$result = array_map(function($n) {
    return $n * 2;
}, $numbers);

print_r($result);

//6. date() → Current Date
echo "<br>";

echo date("y-m-d");   

// 7. password_hash() → Secure Password
echo "<br>";

$password = "admin123";
echo password_hash($password, PASSWORD_DEFAULT);

// 8. json_encode() → Array → JSON
echo "<br>";

$user = ["name" => "Amit", "age" => 22];
echo json_encode($user);

// 9. filter_var() → Validate Email
echo "<br>";

$email = "test@gmail.com";

if(filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo "Valid Email";
}else{
    echo "Invalid Email";
}

?>

