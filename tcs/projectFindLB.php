<?php
$dbTCS = mysqli_connect( "localhost", "odbc", "odbc", "tcs" );;
if( !$dbTCS ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );

$query  = "SELECT DISTINCT ";
$query .= "   ProjectID, ";
$query .= "   Project ";
$query .= "FROM ";
$query .= "   tcs ";
$query .= "WHERE ";
//$query .= "   NOT Closed ";
//$query .= "   AND ";
$query .= "   Enabled ";
$query .= "ORDER BY ";
$query .= "   Project";

$result = $dbTCS->prepare( $query );
if( !$result->execute() ){
   trigger_error( $result->error, E_USER_ERROR );
}else{
   $result->bind_result(
      $projectID,
      $project
   );

   while( $result->fetch() ){
      $dataArray[] = array(
         "ProjectID" => $projectID,
         "Project"   => $project
      );
   }
   echo json_encode( $dataArray );
}

$result->close();
$dbTCS->close();
?>
