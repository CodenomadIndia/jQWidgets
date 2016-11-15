<?php

include('connect.php');
 
$emp = $_POST['emp'];
$pro = $_POST['pro'];
$cha = $_POST['cha'];
$sta = $_POST['sta'];
$end = $_POST['end'];
$task = $_POST['tas'];
$time = $_POST['time'];


$query = "INSERT INTO tcs (DisplayName, Project, ChargeLine, Task, AlphaClock, OmegaClock, DisplayTime)
VALUES ('$emp' , '$pro', '$cha', '$task', '$sta', '$end', '$time')";
	$res=mysqli_query($mysqli,$query);


?>