<?php
    setcookie("loggedin", "", time() - 3600, "/");
    setcookie("username", "", time() - 3600, "/");
    header("Location: index.html");
    exit;
?>
