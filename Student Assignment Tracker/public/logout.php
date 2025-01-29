<?php

session_start();
session_destroy();
header("Location: authentication.html?status=success&action=logout");
exit;

?>