<?php


define('DB_HOST', 'localhost');
define('DB_USER', 'root');    
define('DB_PASS', '');         
define('DB_NAME', 'mini_library');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);


if (!$conn) {
    die("<div style='font-family:sans-serif;padding:40px;color:#c0392b;'>
        <h2>❌ Database Connection Failed</h2>
        <p>" . mysqli_connect_error() . "</p>
        <p>Make sure XAMPP MySQL is running and the database <strong>mini_library</strong> exists.</p>
        <p>Run the SQL file in <code>database/schema.sql</code> first.</p>
    </div>");
}
?>
