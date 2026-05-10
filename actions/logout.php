<?php

session_start();
session_unset();
session_destroy();
header("Location: /mini-library/login.php");
exit();
?>
