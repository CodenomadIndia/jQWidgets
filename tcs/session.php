<?php 
session_start();
$session_id = rand(1111111111,9999999999);
$_SESSION['id'] = $session_id;

?>