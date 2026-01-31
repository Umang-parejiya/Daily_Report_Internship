<?php

// Functions & Pre-defined Functions

// “Do not automatically convert data types for function arguments and return values.”
// Enforce strict type checking. nad it add in top of  php file only once at in file

declare(strict_types=1);   

// Global variable
//Defined outside all functions.
//Not accessible directly inside function.
$taxRate = 18;

function calculateTotal(float $price ,int $qty): float{    // float is return typa of function

// local variable
$subtotal = $price * $qty ;

return $subtotal;
}
echo calculateTotal(120.00,5);

// function useing global variable
function  calculateTotalWithTax(float $price,int $qty) : float  {

// import $taxRate using global key word
 global $taxRate;   // Access global variable   

 $total = ($price * $qty);

 $taxAmount = ($total * $taxRate) / 100;

 return $total + $taxAmount;

}
    
echo "<br>";
echo calculateTotalWithTax(500.50, 2);







?>