<?php
session_start();
if (!isset($_SESSION['matricula'])) {
    header("Location: login.html");
    exit();
}
?>
