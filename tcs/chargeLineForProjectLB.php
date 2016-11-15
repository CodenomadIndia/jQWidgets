<?php
$dbTCS = mysqli_connect( "localhost", "odbc", "odbc", "tcs" );;
if( !$dbTCS ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );

$projectID = strtolower( $_GET[ "ProjectID" ] );
$query = "SELECT ";
$query .=    "ChargeLine ";
$query .= "FROM ";
$query .=    "charge_line ";
$query .= "WHERE ";
$query .= "   (Common ";
$query .= "   OR ";
$query .= "   LOWER( ProjectID ) LIKE '%$projectID%')";
$query .= "   AND ";
$query .= "   ChargeLine != '---' ";

$result = $dbTCS->prepare( $query );

if( !$result->execute() ){
   trigger_error( $result->error, E_USER_ERROR );
}else{
   $result->bind_result(
      $chargeLine
   );
   while( $result->fetch() ){
      $dataArray[] = array(
         "ChargeLine" => $chargeLine
      );
   };
   echo json_encode( $dataArray );
}

$result->close();
$dbTCS->close();
?>
