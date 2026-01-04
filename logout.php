<?php
session_start();
session_unset();
session_destroy();

// Redirect back to the page that submitted the form
$redirect = $_SERVER['HTTP_REFERER'] ?? '/';
header('Location: ' . $redirect);
exit;