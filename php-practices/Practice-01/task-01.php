<!-- A variable is a named container used to store data in memory. -->
<?php
$variableName = "value";


// Name starts with letter or underscore

$_age = 22;  //underscore
$age = 22;   // lower_Case 
$AGE = 22;   // upper_Case

//do not declare type explicitly:

$value = 10;
$str = "umang";



// core data type in php

$greeting = "Hello";   // String

$year = 2024;          // integer

$price = 450.50;       // float

$isActive = true;     // boolean  

$colors = ["Red", "Blue", "Green"];   // array

$empty = null;  // null



// Echo vs Print

echo "Hello";
echo "<br>";
print "Hello ";
echo "<br>";
print_r($colors) ;   // array should be print by "print_r"
echo "<br>";
echo $empty;
// echo "<br>";


//String Concatenation (.)

$concat = "umang" . " parejiya";
echo $concat;

// practice 

$name = "umang parejiya";

$from = "surendranagar";

echo "<br/>";    // for line breking

echo "My name is " . $name . ", I am from ". $from . ".";

// Boolean
$isActive = true;

 echo "<br/>"; 

echo "Active Status: " . $isActive;   // true  → 1   && false → "" (empty)


// Way To Display Boolean

echo "<br/>"; 

echo $isActive ? "true" : "false";

echo "<br/>";

echo "Active Status: " . ($isActive ? "true" : "false");


// But only works inside double quotes
echo "<br/>";

echo "My name is " . $name ;

echo "<br/>";

echo "My name is $name";



$productName = "Laptop"; // String

$price = 450.5;         // Float(double)

$quantity = 5;           // Integer

$inStock = true;         // Boolean
 
echo "<br>";

echo "The product " . $productName . " costs $" . $price;

// Debugging Variable Types
echo "<br>";

var_dump($productName);  // string(6) "Laptop"
echo "<br>";
echo gettype($productName);   // string  

echo "<br>";
echo gettype($price);    // double

$name = "Phone";
$price = 500;
$active = true;

var_dump($name);
echo "<br>";

var_dump($colors);
echo "<br>";

echo gettype($price);
echo "<br>";






















?>