<?php
$emplID = $_GET[ "LoginID" ];
$password = $_GET[ "Password" ];

$dbHR = mysqli_connect( "localhost", "odbc", "odbc", "hr" );
if( !$dbHR ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );

$query = "UPDATE employee SET Password = '$password' WHERE EmplID = '$emplID'";
mysqli_query( $dbHR, $query );
$dbHR->close();
?>
