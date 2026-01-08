<?php
// flash.php
function flash_get(string $key): ?string {
    if (!isset($_SESSION[$key])) return null;
    $msg = $_SESSION[$key];
    unset($_SESSION[$key]);
    return $msg;
}