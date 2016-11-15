<?php include('connect.php'); 
 
 
if (mysqli_connect_errno())
	{
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
	}
	$query = "SELECT  Task FROM tcs";
	$result = $mysqli->prepare($query);
	$result->execute();
	/* bind result variables */
	$result->bind_result($Task);
	
	while ($result->fetch())
		{
		$employees[] = array(
			'Task' => $Task
			
			
			
		);
		}
		echo json_encode($employees);

 ?>