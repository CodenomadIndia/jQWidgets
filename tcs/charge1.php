<?php include('connect.php'); 
  
if (mysqli_connect_errno())
	{
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
	}
	$query = "SELECT  ChargeLine FROM tcs";
	$result = $mysqli->prepare($query);
	$result->execute();
	/* bind result variables */
	$result->bind_result($ChargeLine);
	
	while ($result->fetch())
		{
		$employees[] = array(
			'ChargeLine' => $ChargeLine
			
			
			
		);
		}
		echo json_encode($employees);

 ?>