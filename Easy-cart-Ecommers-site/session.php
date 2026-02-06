<?php
session_start();
 
echo "<h1>Session Data Debugger</h1>";
echo "<pre style='background: #f4f4f4; padding: 20px; border: 1px solid #ccc; border-radius: 5px;'>";
print_r($_SESSION);
echo "</pre>";
 
echo "<br><hr><br>";