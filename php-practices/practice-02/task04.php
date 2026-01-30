

<?php

// Iteration using for, while, and foreach.

// FOR Loop

for ($i = 1; $i <= 10; $i++) {
    echo "5 x $i = " . (5 * $i) . "<br>";
}

echo "<br>";

// While Loop
$num = 1;

while ($num <= 5) {
    echo "Number: $num <br>";
    $num++;
}


echo "<br>";

// FOREACH Loop

$bowlers = [
    "Bumrah" => 120,
    "Shami" => 95,
    "Ashwin" => 150
];

foreach ($bowlers as $bowler => $wickets) {
    echo "Bowler: $bowler , Wickets: $wickets <br>";
}



echo "<br>";

$marks = [80,85,92,84,78];

for ($i = 0; $i < count($marks); $i++) {
    echo "Marks:- $marks[$i]" . "<br>";
}

?>