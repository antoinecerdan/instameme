<?php
if (isset($_SESSION["id"])) {
    session_start();
};

unset($_SESSION["id"]);
unset($_SESSION["sid"]);
unset($_SESSION["name"]);
session_destroy();

header("Location: index.php");
