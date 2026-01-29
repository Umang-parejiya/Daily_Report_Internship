<!--  Understanding Arrays and Debugging -->

<?php    
$employee = [
    "name" => "Romil",
    "age" => 28,
    "department" => "IT",
    "skills" => ["JavaScript", "React", "Node.js"]
];

echo "<pre>";
print_r($employee);   // showing key,value,nesting
echo "</pre>";

echo "<pre>";
var_dump($employee);  // showing Type,Length,value
echo "</pre>";         // in <pre>tag Formatting for readability

var_dump($employee);   // print in one-line 


echo "<br>";
echo $employee["name"];


echo "<br>";
echo $employee["skills"][1]



?>