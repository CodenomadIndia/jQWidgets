<?php

include('connect.php');
session_start();


$sess = $_SESSION['id'];
$name = $_POST['a'];
if($_POST['update'])
{
	if (mysqli_connect_errno())
	{
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
	}
	
	$query = "UPDATE session SET name=? WHERE session_id=?";
	$result = $mysqli->prepare($query);
	$result->bind_param('si', $name,$sess);
	$res = $result->execute() or trigger_error($result->error, E_USER_ERROR);
	echo $res;
	
	

}
else
{
	
	$query = "INSERT INTO session (session_id, name)
VALUES ('$sess' , '$name')";
	$res=mysqli_query($mysqli,$query);

	
	
}


?>