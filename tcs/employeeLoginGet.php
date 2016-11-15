<?php
$emplID = $_GET[ "LoginID" ];
$dbHR = mysqli_connect( "localhost", "odbc", "odbc", "hr" );
if( !$dbHR ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );

$query = "SELECT Password FROM employee WHERE EmplID = '$emplID'";
$result = mysqli_query( $dbHR, $query );
$row = mysqli_fetch_assoc( $result );
$password = $row[ "Password" ];
echo $password;
mysqli_free_result( $result );
$dbHR->close();
?>
