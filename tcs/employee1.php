<?php include('connect.php'); 
 
$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno())
	{
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
	}
	$query = "SELECT DisplayName FROM tcs";
	$result = $mysqli->prepare($query);
	$result->execute();
	/* bind result variables */
	$result->bind_result($DisplayName);
	
	while ($result->fetch())
		{
		$employees[] = array(
			'DisplayName' => $DisplayName
		);
		}
		echo json_encode($employees);
 ?>