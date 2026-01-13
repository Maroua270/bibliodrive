<?php
session_start();
session_unset();
session_destroy();


$redirect = $_SERVER['HTTP_REFERER'] ?? '/';
header('Location: ' . $redirect);
exit;