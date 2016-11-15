<?php include('connect.php'); 

if (mysqli_connect_errno())
	{
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
	}
	$query = "SELECT  Project FROM tcs";
	$result = $mysqli->prepare($query);
	$result->execute();
	/* bind result variables */
	$result->bind_result($Project);
while ($result->fetch())
		{
		$employees[] = array(
			'Project' => $Project
					);
		}
echo json_encode($employees);
 ?>