<?php
//Cookies & Sessions are core backend concepts


// Setting Cookie (Different Example)

// Must be before HTML
setcookie("user_preference", "dark_mode", time() + 3600, "/");
echo "Preference saved";
echo "<br>";
// Explanation

// name   → user_preference
// value  → dark_mode
// 3600   → 1 hour
// "/"    → entire website



// Reading Cookie

if(isset($_COOKIE["user_preference"])) {
    echo "Preference: " . $_COOKIE["user_preference"];
} else {
    echo "No preference set";
}

?>
